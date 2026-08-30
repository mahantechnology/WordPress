<?php
/**
 * Breadcrumb trail with JSON-LD output.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Breadcrumb {

	/**
	 * Prints the trail, or defers to Yoast/RankMath when one of them is active.
	 */
	public static function render() {
		if ( is_front_page() ) {
			return;
		}

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<nav class="mahan-breadcrumb" aria-label="' . esc_attr__( 'مسیر صفحه', 'mahan' ) . '">', '</nav>' );
			return;
		}

		if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			echo '<nav class="mahan-breadcrumb" aria-label="' . esc_attr__( 'مسیر صفحه', 'mahan' ) . '">';
			rank_math_the_breadcrumbs();
			echo '</nav>';
			return;
		}

		$items = self::build();

		if ( count( $items ) < 2 ) {
			return;
		}

		echo '<nav class="mahan-breadcrumb" aria-label="' . esc_attr__( 'مسیر صفحه', 'mahan' ) . '"><ol>';

		$last = count( $items ) - 1;

		foreach ( $items as $index => $item ) {
			echo '<li class="mahan-breadcrumb__item">';

			if ( $item['url'] && $index !== $last ) {
				printf( '<a href="%1$s">%2$s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
			} else {
				printf( '<span aria-current="page">%s</span>', esc_html( $item['label'] ) );
			}

			if ( $index !== $last ) {
				echo mahan_icon( 'chevron-left', 14, 'mahan-breadcrumb__sep' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
			}

			echo '</li>';
		}

		echo '</ol></nav>';

		self::schema( $items );
	}

	/**
	 * Builds the trail for the current view.
	 *
	 * @return array<int,array{label:string,url:string}>
	 */
	public static function build() {
		$items = array(
			array(
				'label' => __( 'خانه', 'mahan' ),
				'url'   => home_url( '/' ),
			),
		);

		if ( mahan_has_woocommerce() && ( is_shop() || is_product_taxonomy() || is_product() ) ) {
			$shop_id = wc_get_page_id( 'shop' );

			if ( $shop_id > 0 && ! is_shop() ) {
				$items[] = array(
					'label' => get_the_title( $shop_id ),
					'url'   => get_permalink( $shop_id ),
				);
			}
		}

		if ( is_singular() ) {
			$post_type = get_post_type();

			if ( 'post' === $post_type ) {
				$blog_id = (int) get_option( 'page_for_posts' );

				if ( $blog_id ) {
					$items[] = array(
						'label' => get_the_title( $blog_id ),
						'url'   => get_permalink( $blog_id ),
					);
				}

				$items = array_merge( $items, self::term_trail( get_the_ID(), 'category' ) );
			} elseif ( 'product' === $post_type ) {
				$items = array_merge( $items, self::term_trail( get_the_ID(), 'product_cat' ) );
			} elseif ( 'page' === $post_type ) {
				foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
					$items[] = array(
						'label' => get_the_title( $ancestor ),
						'url'   => get_permalink( $ancestor ),
					);
				}
			} else {
				$object = get_post_type_object( $post_type );

				if ( $object && ! empty( $object->has_archive ) ) {
					$items[] = array(
						'label' => $object->labels->name,
						'url'   => get_post_type_archive_link( $post_type ),
					);
				}
			}

			$items[] = array(
				'label' => get_the_title(),
				'url'   => '',
			);

			return $items;
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) ) as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, $term->taxonomy );

					if ( $ancestor instanceof WP_Term ) {
						$items[] = array(
							'label' => $ancestor->name,
							'url'   => get_term_link( $ancestor ),
						);
					}
				}

				$items[] = array(
					'label' => $term->name,
					'url'   => '',
				);
			}

			return $items;
		}

		if ( is_search() ) {
			$items[] = array(
				/* translators: %s: search term. */
				'label' => sprintf( __( 'نتایج جستجو برای «%s»', 'mahan' ), get_search_query() ),
				'url'   => '',
			);

			return $items;
		}

		if ( is_author() ) {
			$items[] = array(
				/* translators: %s: author name. */
				'label' => sprintf( __( 'نوشته‌های %s', 'mahan' ), get_the_author() ),
				'url'   => '',
			);

			return $items;
		}

		if ( is_404() ) {
			$items[] = array(
				'label' => __( 'صفحه پیدا نشد', 'mahan' ),
				'url'   => '',
			);

			return $items;
		}

		if ( is_home() ) {
			$items[] = array(
				'label' => single_post_title( '', false ),
				'url'   => '',
			);

			return $items;
		}

		if ( is_post_type_archive() ) {
			$items[] = array(
				'label' => post_type_archive_title( '', false ),
				'url'   => '',
			);

			return $items;
		}

		if ( is_date() ) {
			$items[] = array(
				'label' => mahan_fa_numbers( get_the_date( 'F Y' ) ),
				'url'   => '',
			);
		}

		return $items;
	}

	/**
	 * Builds the ancestor chain for a post's primary term.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $taxonomy Taxonomy to walk.
	 * @return array
	 */
	private static function term_trail( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}

		$primary = reset( $terms );
		$trail   = array();

		foreach ( array_reverse( get_ancestors( $primary->term_id, $taxonomy ) ) as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, $taxonomy );

			if ( $ancestor instanceof WP_Term ) {
				$trail[] = array(
					'label' => $ancestor->name,
					'url'   => get_term_link( $ancestor ),
				);
			}
		}

		$trail[] = array(
			'label' => $primary->name,
			'url'   => get_term_link( $primary ),
		);

		return $trail;
	}

	/**
	 * Prints the BreadcrumbList structured data.
	 *
	 * @param array $items Trail items.
	 */
	private static function schema( array $items ) {
		$elements = array();
		$position = 1;

		foreach ( $items as $item ) {
			$element = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => wp_strip_all_tags( $item['label'] ),
			);

			if ( $item['url'] ) {
				$element['item'] = $item['url'];
			}

			$elements[] = $element;
			++$position;
		}

		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $elements,
		);

		printf(
			'<script type="application/ld+json">%s</script>',
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded.
		);
	}
}
