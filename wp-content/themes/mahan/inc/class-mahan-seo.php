<?php
/**
 * Light SEO output that steps aside when a dedicated plugin is active.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Seo {

	/**
	 * Hooks the metadata output.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'meta_tags' ), 5 );
		add_action( 'wp_head', array( $this, 'article_schema' ), 6 );
		add_filter( 'document_title_separator', array( $this, 'title_separator' ) );
	}

	/**
	 * Whether an SEO plugin is handling the head already.
	 *
	 * @return bool
	 */
	private function plugin_active() {
		return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'SEOPRESS_VERSION' ) || class_exists( 'All_in_One_SEO_Pack' );
	}

	/**
	 * Prints Open Graph and Twitter card tags.
	 */
	public function meta_tags() {
		if ( $this->plugin_active() ) {
			return;
		}

		$title       = wp_get_document_title();
		$description = $this->description();
		$image       = $this->image();

		$tags = array(
			'og:site_name' => get_bloginfo( 'name' ),
			'og:title'     => $title,
			'og:type'      => is_singular() ? 'article' : 'website',
			'og:url'       => $this->current_url(),
			'og:locale'    => get_locale(),
		);

		if ( $description ) {
			$tags['og:description'] = $description;
			printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
		}

		if ( $image ) {
			$tags['og:image'] = $image;
		}

		foreach ( $tags as $property => $content ) {
			if ( $content ) {
				printf( '<meta property="%1$s" content="%2$s" />' . "\n", esc_attr( $property ), esc_attr( $content ) );
			}
		}

		printf( '<meta name="twitter:card" content="%s" />' . "\n", $image ? 'summary_large_image' : 'summary' );
		printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );

		if ( $description ) {
			printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $description ) );
		}

		if ( $image ) {
			printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $image ) );
		}
	}

	/**
	 * Prints Article structured data on single posts.
	 */
	public function article_schema() {
		if ( $this->plugin_active() || ! is_singular( array( 'post', 'mahan_portfolio' ) ) ) {
			return;
		}

		$schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'mainEntityOfPage' => get_permalink(),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author(),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
			),
			'wordCount'        => mahan_count_words( get_post_field( 'post_content', get_the_ID() ) ),
		);

		$description = $this->description();

		if ( $description ) {
			$schema['description'] = $description;
		}

		$image = $this->image();

		if ( $image ) {
			$schema['image'] = $image;
		}

		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded.
		);
	}

	/**
	 * A short description of the current view.
	 *
	 * @return string
	 */
	private function description() {
		if ( is_singular() ) {
			$excerpt = get_the_excerpt();

			if ( $excerpt ) {
				return wp_strip_all_tags( $excerpt );
			}

			return wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', get_the_ID() ) ) ), 30, '…' );
		}

		if ( is_category() || is_tag() || is_tax() ) {
			return wp_strip_all_tags( term_description() );
		}

		return get_bloginfo( 'description' );
	}

	/**
	 * The share image for the current view.
	 *
	 * @return string
	 */
	private function image() {
		if ( is_singular() && has_post_thumbnail() ) {
			return get_the_post_thumbnail_url( get_the_ID(), 'mahan-wide' );
		}

		$custom_logo = get_theme_mod( 'custom_logo' );

		return $custom_logo ? wp_get_attachment_image_url( $custom_logo, 'full' ) : '';
	}

	/**
	 * The canonical URL of the current request.
	 *
	 * @return string
	 */
	private function current_url() {
		if ( is_singular() ) {
			return get_permalink();
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$link = get_term_link( get_queried_object() );

			return is_wp_error( $link ) ? home_url( '/' ) : $link;
		}

		return home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	}

	/**
	 * Uses a separator that reads well in RTL.
	 *
	 * @param string $separator Default separator.
	 * @return string
	 */
	public function title_separator( $separator ) {
		return '|';
	}
}
