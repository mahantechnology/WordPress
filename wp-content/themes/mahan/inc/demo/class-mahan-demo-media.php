<?php
/**
 * Imports the bundled artwork into the media library and hands it back to the
 * demo packs.
 *
 * The images ship inside the theme, so importing copies them into uploads and
 * registers an attachment for each. Every attachment is tagged, which lets a
 * second import reuse what is already there and lets the rollback clean up.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Demo_Media {

	/**
	 * Attachment meta key marking an image this class imported.
	 */
	const META_KEY = '_mahan_demo_media';

	/**
	 * How many images of each kind ship with the theme.
	 *
	 * @var array<string,int>
	 */
	private static $sets = array(
		'wide'     => 6,
		'card'     => 12,
		'portrait' => 6,
		'square'   => 8,
		'logo'     => 6,
	);

	/**
	 * Attachment IDs already resolved this request, keyed by file name.
	 *
	 * @var array<string,int>
	 */
	private $cache = array();

	/**
	 * Builds the instance and warms the cache from what is already imported.
	 */
	public function __construct() {
		$this->cache = self::existing();
	}

	/**
	 * The file names the theme ships, grouped by kind.
	 *
	 * @return array<string,string[]>
	 */
	public static function manifest() {
		$manifest = array();

		foreach ( self::$sets as $kind => $count ) {
			$manifest[ $kind ] = array();

			for ( $index = 1; $index <= $count; $index++ ) {
				$manifest[ $kind ][] = sprintf( 'mahan-%s-%d.webp', $kind, $index );
			}
		}

		return $manifest;
	}

	/**
	 * Attachment IDs for artwork already in the library, keyed by file name.
	 *
	 * @return array<string,int>
	 */
	public static function existing() {
		$attachments = get_posts(
			array(
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'posts_per_page'         => 200,
				'meta_key'               => self::META_KEY, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Bounded, admin-only import.
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
			)
		);

		$map = array();

		foreach ( $attachments as $id ) {
			$name = get_post_meta( $id, self::META_KEY, true );

			if ( $name ) {
				$map[ $name ] = (int) $id;
			}
		}

		return $map;
	}

	/**
	 * Copies bundled images that are not in the library yet.
	 *
	 * Each image needs its intermediate sizes generated, which is the slowest
	 * part of an import, so the work is done a batch at a time and the caller
	 * repeats the step until nothing is left.
	 *
	 * @param int $limit How many images to copy in this pass. 0 means all.
	 * @return array{created:int,remaining:int}
	 */
	public function import( $limit = 8 ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$created   = 0;
		$remaining = 0;

		foreach ( self::manifest() as $kind => $files ) {
			foreach ( $files as $file ) {
				if ( isset( $this->cache[ $file ] ) && get_post( $this->cache[ $file ] ) ) {
					continue;
				}

				if ( $limit > 0 && $created >= $limit ) {
					++$remaining;
					continue;
				}

				$id = $this->sideload( $kind, $file );

				if ( $id ) {
					$this->cache[ $file ] = $id;
					++$created;
				}
			}
		}

		return array(
			'created'   => $created,
			'remaining' => $remaining,
		);
	}

	/**
	 * Copies one bundled file into uploads and registers the attachment.
	 *
	 * @param string $kind Image kind, used for the alt text.
	 * @param string $file File name inside assets/images/demo-content/.
	 * @return int Attachment ID, or 0 on failure.
	 */
	private function sideload( $kind, $file ) {
		$source = MAHAN_DIR . 'assets/images/demo-content/' . $file;

		if ( ! file_exists( $source ) ) {
			return 0;
		}

		$uploads = wp_upload_dir();

		if ( ! empty( $uploads['error'] ) ) {
			return 0;
		}

		$filename    = wp_unique_filename( $uploads['path'], $file );
		$destination = trailingslashit( $uploads['path'] ) . $filename;

		if ( ! copy( $source, $destination ) ) {
			return 0;
		}

		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/webp',
				'post_title'     => $this->title( $kind, $file ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$destination
		);

		if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
			// Leaving a stray file behind would clutter uploads.
			wp_delete_file( $destination );

			return 0;
		}

		wp_update_attachment_metadata(
			$attachment_id,
			wp_generate_attachment_metadata( $attachment_id, $destination )
		);

		update_post_meta( $attachment_id, self::META_KEY, $file );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $this->title( $kind, $file ) );

		return (int) $attachment_id;
	}

	/**
	 * A readable title for one bundled image.
	 *
	 * @param string $kind Image kind.
	 * @param string $file File name.
	 * @return string
	 */
	private function title( $kind, $file ) {
		$labels = array(
			'wide'     => __( 'تصویر عریض نمونه', 'mahan' ),
			'card'     => __( 'تصویر کارت نمونه', 'mahan' ),
			'portrait' => __( 'تصویر عمودی نمونه', 'mahan' ),
			'square'   => __( 'تصویر مربع نمونه', 'mahan' ),
			'logo'     => __( 'لوگوی نمونه', 'mahan' ),
		);

		$label  = isset( $labels[ $kind ] ) ? $labels[ $kind ] : __( 'تصویر نمونه', 'mahan' );
		$number = (int) preg_replace( '/\D/', '', substr( $file, strrpos( $file, '-' ) ) );

		return trim( $label . ' ' . mahan_fa_numbers( $number ) );
	}

	/**
	 * One image of a kind, picked by index and wrapping round the set.
	 *
	 * @param string $kind  wide, card, portrait, square or logo.
	 * @param int    $index Zero-based index; wraps.
	 * @return array{id:int,url:string} Empty id and url when nothing is imported.
	 */
	public function get( $kind, $index = 0 ) {
		$manifest = self::manifest();

		if ( empty( $manifest[ $kind ] ) ) {
			return array(
				'id'  => 0,
				'url' => '',
			);
		}

		$files = $manifest[ $kind ];
		$file  = $files[ abs( (int) $index ) % count( $files ) ];

		if ( empty( $this->cache[ $file ] ) ) {
			// Nothing imported yet: fall back to the file shipped in the theme so
			// a preview still shows artwork rather than a broken image.
			return array(
				'id'  => 0,
				'url' => MAHAN_URI . 'assets/images/demo-content/' . $file,
			);
		}

		$id  = $this->cache[ $file ];
		$url = wp_get_attachment_url( $id );

		return array(
			'id'  => $id,
			'url' => $url ? $url : '',
		);
	}

	/**
	 * Shorthand accessors the demo packs read.
	 *
	 * @param int $index Zero-based index.
	 * @return array{id:int,url:string}
	 */
	public function wide( $index = 0 ) {
		return $this->get( 'wide', $index );
	}

	/**
	 * One card image.
	 *
	 * @param int $index Zero-based index.
	 * @return array{id:int,url:string}
	 */
	public function card( $index = 0 ) {
		return $this->get( 'card', $index );
	}

	/**
	 * One portrait image.
	 *
	 * @param int $index Zero-based index.
	 * @return array{id:int,url:string}
	 */
	public function portrait( $index = 0 ) {
		return $this->get( 'portrait', $index );
	}

	/**
	 * One square image.
	 *
	 * @param int $index Zero-based index.
	 * @return array{id:int,url:string}
	 */
	public function square( $index = 0 ) {
		return $this->get( 'square', $index );
	}

	/**
	 * One logo image.
	 *
	 * @param int $index Zero-based index.
	 * @return array{id:int,url:string}
	 */
	public function logo( $index = 0 ) {
		return $this->get( 'logo', $index );
	}

	/**
	 * An Elementor MEDIA control value for one image.
	 *
	 * @param string $kind  Image kind.
	 * @param int    $index Zero-based index.
	 * @return array{id:int,url:string}
	 */
	public function media( $kind, $index = 0 ) {
		return $this->get( $kind, $index );
	}

	/**
	 * An Elementor GALLERY control value.
	 *
	 * @param string $kind  Image kind.
	 * @param int    $count How many images.
	 * @param int    $start Index to start from.
	 * @return array<int,array{id:int,url:string}>
	 */
	public function gallery( $kind, $count, $start = 0 ) {
		$gallery = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$gallery[] = $this->get( $kind, $start + $i );
		}

		return $gallery;
	}

	/**
	 * Attaches an image to a post as its featured image.
	 *
	 * @param int    $post_id Post to set the thumbnail on.
	 * @param string $kind    Image kind.
	 * @param int    $index   Zero-based index.
	 * @return bool Whether a thumbnail was set.
	 */
	public function set_thumbnail( $post_id, $kind, $index = 0 ) {
		$image = $this->get( $kind, $index );

		if ( empty( $image['id'] ) ) {
			return false;
		}

		return (bool) set_post_thumbnail( $post_id, $image['id'] );
	}

	/**
	 * Attaches an image to a term, using the meta key WooCommerce reads.
	 *
	 * @param int    $term_id Term to set the thumbnail on.
	 * @param string $kind    Image kind.
	 * @param int    $index   Zero-based index.
	 * @return bool
	 */
	public function set_term_thumbnail( $term_id, $kind, $index = 0 ) {
		$image = $this->get( $kind, $index );

		if ( empty( $image['id'] ) ) {
			return false;
		}

		update_term_meta( $term_id, 'thumbnail_id', $image['id'] );
		update_term_meta( $term_id, '_mahan_term_image', $image['id'] );

		return true;
	}

	/**
	 * Deletes every attachment this class imported.
	 *
	 * @return int Number of attachments removed.
	 */
	public static function rollback() {
		$removed = 0;

		foreach ( self::existing() as $id ) {
			if ( wp_delete_attachment( $id, true ) ) {
				++$removed;
			}
		}

		return $removed;
	}
}
