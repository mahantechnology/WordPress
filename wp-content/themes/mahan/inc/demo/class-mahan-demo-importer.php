<?php
/**
 * Installs one of the bundled starter sites.
 *
 * The import runs in steps so a slow host does not time out, and every object
 * it creates is tagged so the same demo can be re-imported or rolled back.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Demo_Importer {

	/**
	 * Option holding the record of what the last import created.
	 */
	const LOG_OPTION = 'mahan_demo_import_log';

	/**
	 * Meta key marking every object an import created.
	 */
	const OWNER_META = '_mahan_demo';

	/**
	 * The steps an import runs through, in order.
	 *
	 * @return array<string,string> Step key mapped to its label.
	 */
	public static function steps() {
		return array(
			'settings' => __( 'اعمال رنگ‌ها و تنظیمات', 'mahan' ),
			'content'  => __( 'ساخت نوشته‌ها و محتوای نمونه', 'mahan' ),
			'pages'    => __( 'ساخت برگه‌ها', 'mahan' ),
			'menus'    => __( 'ساخت منوها', 'mahan' ),
			'widgets'  => __( 'چیدن ابزارک‌ها', 'mahan' ),
			'finish'   => __( 'تنظیمات پایانی', 'mahan' ),
		);
	}

	/**
	 * Registers the AJAX endpoint the wizard drives the import with.
	 */
	public function __construct() {
		add_action( 'wp_ajax_mahan_import_demo', array( $this, 'ajax_step' ) );
		add_action( 'wp_ajax_mahan_rollback_demo', array( $this, 'ajax_rollback' ) );
	}

	/**
	 * Runs one import step for the wizard.
	 */
	public function ajax_step() {
		if ( ! current_user_can( 'import' ) || ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'شما اجازهٔ درون‌ریزی ندارید.', 'mahan' ) ), 403 );
		}

		check_ajax_referer( 'mahan_setup_wizard', 'nonce' );

		$demo_id = isset( $_POST['demo'] ) ? sanitize_key( wp_unslash( $_POST['demo'] ) ) : '';
		$step    = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';

		if ( ! Mahan_Demo_Library::exists( $demo_id ) ) {
			wp_send_json_error( array( 'message' => __( 'قالب آمادهٔ انتخاب‌شده پیدا نشد.', 'mahan' ) ), 404 );
		}

		$steps = self::steps();

		if ( ! isset( $steps[ $step ] ) ) {
			wp_send_json_error( array( 'message' => __( 'مرحلهٔ نامعتبر.', 'mahan' ) ), 400 );
		}

		$pack   = Mahan_Demo_Library::get( $demo_id );
		$method = 'import_' . $step;

		try {
			$result = $this->$method( $pack );
		} catch ( Exception $exception ) {
			wp_send_json_error(
				array(
					'message' => sprintf(
						/* translators: %s: error message. */
						__( 'خطا در مرحلهٔ درون‌ریزی: %s', 'mahan' ),
						$exception->getMessage()
					),
				),
				500
			);
		}

		$keys = array_keys( $steps );
		$next = array_search( $step, $keys, true );
		$next = ( false !== $next && isset( $keys[ $next + 1 ] ) ) ? $keys[ $next + 1 ] : '';

		wp_send_json_success(
			array(
				'step'    => $step,
				'label'   => $steps[ $step ],
				'next'    => $next,
				'created' => $result,
				'homeUrl' => home_url( '/' ),
			)
		);
	}

	/**
	 * Removes everything the last import created.
	 */
	public function ajax_rollback() {
		if ( ! current_user_can( 'delete_others_pages' ) || ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'شما اجازهٔ حذف محتوای درون‌ریزی‌شده را ندارید.', 'mahan' ) ), 403 );
		}

		check_ajax_referer( 'mahan_setup_wizard', 'nonce' );

		$removed = $this->rollback();

		wp_send_json_success(
			array(
				'removed' => $removed,
				'message' => sprintf(
					/* translators: %s: number of removed items. */
					__( '%s مورد حذف شد.', 'mahan' ),
					mahan_fa_numbers( $removed )
				),
			)
		);
	}

	/**
	 * Applies the pack's palette and option overrides.
	 *
	 * @param array $pack Demo pack.
	 * @return int
	 */
	private function import_settings( array $pack ) {
		Mahan_Options::apply_palette( $pack['palette'] );

		if ( ! empty( $pack['options'] ) && is_array( $pack['options'] ) ) {
			Mahan_Options::merge( $pack['options'] );
		}

		$this->log( 'demo', $pack['id'] );

		return 1;
	}

	/**
	 * Creates the sample posts, testimonials, services, portfolio and team.
	 *
	 * @param array $pack Demo pack.
	 * @return int Number of objects created.
	 */
	private function import_content( array $pack ) {
		$shared  = Mahan_Demo_Library::shared_content();
		$created = 0;

		foreach ( $shared['posts'] as $post ) {
			$created += $this->create_post(
				array(
					'post_type'    => 'post',
					'post_title'   => $post['title'],
					'post_excerpt' => $post['excerpt'],
					'post_content' => $post['content'],
				),
				array( 'category' => array( $post['category'] ) )
			) ? 1 : 0;
		}

		foreach ( $shared['testimonials'] as $testimonial ) {
			$id = $this->create_post(
				array(
					'post_type'    => 'mahan_testimonial',
					'post_title'   => $testimonial['name'],
					'post_content' => $testimonial['text'],
				)
			);

			if ( $id ) {
				update_post_meta( $id, '_mahan_testimonial_role', $testimonial['role'] );
				update_post_meta( $id, '_mahan_testimonial_rating', $testimonial['rating'] );
				++$created;
			}
		}

		foreach ( array( 'services' => 'mahan_service', 'portfolio' => 'mahan_portfolio', 'team' => 'mahan_team' ) as $key => $post_type ) {
			if ( empty( $pack[ $key ] ) ) {
				continue;
			}

			foreach ( $pack[ $key ] as $index => $item ) {
				$id = $this->create_post(
					array(
						'post_type'    => $post_type,
						'post_title'   => $item['title'],
						'post_excerpt' => isset( $item['excerpt'] ) ? $item['excerpt'] : '',
						'post_content' => isset( $item['content'] ) ? $item['content'] : '',
						'menu_order'   => $index,
					),
					isset( $item['terms'] ) ? $item['terms'] : array()
				);

				if ( ! $id ) {
					continue;
				}

				if ( ! empty( $item['meta'] ) ) {
					foreach ( $item['meta'] as $meta_key => $meta_value ) {
						update_post_meta( $id, $meta_key, $meta_value );
					}
				}

				++$created;
			}
		}

		return $created;
	}

	/**
	 * Creates the pack's pages, building the Elementor content for each.
	 *
	 * @param array $pack Demo pack.
	 * @return int Number of pages created.
	 */
	private function import_pages( array $pack ) {
		if ( empty( $pack['pages'] ) ) {
			return 0;
		}

		$created = 0;
		$ids     = array();

		foreach ( $pack['pages'] as $slug => $page ) {
			$existing = get_page_by_path( $slug, OBJECT, 'page' );

			$postarr = array(
				'post_type'    => 'page',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_content' => isset( $page['content'] ) ? $page['content'] : '',
			);

			if ( $existing ) {
				$postarr['ID'] = $existing->ID;
				$page_id       = wp_update_post( $postarr, true );
			} else {
				$page_id = wp_insert_post( $postarr, true );
			}

			if ( is_wp_error( $page_id ) ) {
				continue;
			}

			update_post_meta( $page_id, self::OWNER_META, $pack['id'] );

			if ( ! empty( $page['sections'] ) && is_callable( $page['sections'] ) ) {
				$this->attach_elementor( $page_id, call_user_func( $page['sections'] ) );
			}

			if ( ! empty( $page['meta'] ) ) {
				foreach ( $page['meta'] as $key => $value ) {
					update_post_meta( $page_id, $key, $value );
				}
			}

			$ids[ $slug ] = $page_id;
			++$created;
		}

		$this->log( 'pages', $ids );

		// Point the front page and the blog page at what we just built.
		if ( isset( $ids['home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $ids['home'] );
		}

		if ( isset( $ids['blog'] ) ) {
			update_option( 'page_for_posts', $ids['blog'] );
		}

		return $created;
	}

	/**
	 * Stores an Elementor document on a page.
	 *
	 * @param int   $page_id  Page ID.
	 * @param array $sections Element tree from the builder.
	 */
	private function attach_elementor( $page_id, array $sections ) {
		if ( ! $sections ) {
			return;
		}

		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );

		// Elementor expects the data slashed, because it unslashes on read.
		update_post_meta(
			$page_id,
			'_elementor_data',
			wp_slash( wp_json_encode( $sections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) )
		);

		if ( mahan_has_elementor() ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}

	/**
	 * Builds the pack's menus from its page list.
	 *
	 * @param array $pack Demo pack.
	 * @return int Number of menus created.
	 */
	private function import_menus( array $pack ) {
		if ( empty( $pack['menus'] ) ) {
			return 0;
		}

		$log       = $this->get_log();
		$page_ids  = isset( $log['pages'] ) ? $log['pages'] : array();
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		$locations = is_array( $locations ) ? $locations : array();
		$created   = 0;
		$menu_ids  = array();

		foreach ( $pack['menus'] as $location => $menu ) {
			$name    = $menu['name'];
			$existing = wp_get_nav_menu_object( $name );

			if ( $existing ) {
				// Start from a clean slate so re-importing does not duplicate items.
				foreach ( wp_get_nav_menu_items( $existing->term_id ) as $item ) {
					wp_delete_post( $item->ID, true );
				}

				$menu_id = $existing->term_id;
			} else {
				$menu_id = wp_create_nav_menu( $name );
			}

			if ( is_wp_error( $menu_id ) ) {
				continue;
			}

			$parents = array();

			foreach ( $menu['items'] as $key => $item ) {
				$args = array(
					'menu-item-title'  => $item['title'],
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => isset( $item['parent'], $parents[ $item['parent'] ] ) ? $parents[ $item['parent'] ] : 0,
				);

				if ( isset( $item['page'] ) && isset( $page_ids[ $item['page'] ] ) ) {
					$args['menu-item-object-id'] = $page_ids[ $item['page'] ];
					$args['menu-item-object']    = 'page';
					$args['menu-item-type']      = 'post_type';
				} else {
					$args['menu-item-url']  = isset( $item['url'] ) ? $item['url'] : home_url( '/' );
					$args['menu-item-type'] = 'custom';
				}

				$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );

				if ( is_wp_error( $item_id ) ) {
					continue;
				}

				$parents[ is_string( $key ) ? $key : $item['title'] ] = $item_id;

				if ( ! empty( $item['icon'] ) ) {
					update_post_meta( $item_id, '_mahan_menu_icon', $item['icon'] );
				}

				if ( ! empty( $item['badge'] ) ) {
					update_post_meta( $item_id, '_mahan_menu_badge', $item['badge'] );
				}

				if ( ! empty( $item['mega'] ) ) {
					update_post_meta( $item_id, '_mahan_mega_menu', 'yes' );
					update_post_meta( $item_id, '_mahan_mega_columns', isset( $item['mega_columns'] ) ? (int) $item['mega_columns'] : 4 );
				}
			}

			$locations[ $location ] = $menu_id;
			$menu_ids[ $location ]  = $menu_id;
			++$created;
		}

		set_theme_mod( 'nav_menu_locations', $locations );
		$this->log( 'menus', $menu_ids );

		return $created;
	}

	/**
	 * Places the pack's widgets into the sidebars.
	 *
	 * @param array $pack Demo pack.
	 * @return int Number of widgets placed.
	 */
	private function import_widgets( array $pack ) {
		if ( empty( $pack['widgets'] ) ) {
			return 0;
		}

		$sidebars = get_option( 'sidebars_widgets', array() );
		$sidebars = is_array( $sidebars ) ? $sidebars : array();
		$created  = 0;

		foreach ( $pack['widgets'] as $sidebar_id => $widgets ) {
			$sidebars[ $sidebar_id ] = array();

			foreach ( $widgets as $widget ) {
				$type     = $widget['type'];
				$instance = isset( $widget['instance'] ) ? $widget['instance'] : array();
				$option   = 'widget_' . $type;
				$stored   = get_option( $option, array() );
				$stored   = is_array( $stored ) ? $stored : array();

				$numbers = array_filter( array_keys( $stored ), 'is_numeric' );
				$index   = $numbers ? max( $numbers ) + 1 : 2;

				$stored[ $index ]     = $instance;
				$stored['_multiwidget'] = 1;

				update_option( $option, $stored );

				$sidebars[ $sidebar_id ][] = $type . '-' . $index;
				++$created;
			}
		}

		update_option( 'sidebars_widgets', $sidebars );
		$this->log( 'widgets', array_keys( $pack['widgets'] ) );

		return $created;
	}

	/**
	 * Final touches: permalinks, reading settings and WooCommerce pages.
	 *
	 * @param array $pack Demo pack.
	 * @return int
	 */
	private function import_finish( array $pack ) {
		update_option( 'posts_per_page', 9 );
		update_option( 'blogdescription', isset( $pack['tagline'] ) ? $pack['tagline'] : get_bloginfo( 'description' ) );

		if ( ! get_option( 'permalink_structure' ) ) {
			update_option( 'permalink_structure', '/%postname%/' );
		}

		flush_rewrite_rules( false );

		if ( mahan_has_elementor() ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		$this->configure_store();

		$this->log( 'completed_at', time() );

		return 1;
	}

	/**
	 * Points a freshly installed store at Persian currency formatting.
	 *
	 * The demo prices are written in Toman, so leaving WooCommerce on its US
	 * dollar default would make them read wrong. A store that has already been
	 * configured is left alone.
	 */
	private function configure_store() {
		if ( ! mahan_has_woocommerce() ) {
			return;
		}

		// 'USD' is WooCommerce's install default; anything else is a deliberate choice.
		if ( 'USD' !== get_option( 'woocommerce_currency' ) ) {
			return;
		}

		update_option( 'woocommerce_currency', 'IRT' );
		update_option( 'woocommerce_currency_pos', 'right_space' );
		update_option( 'woocommerce_price_thousand_sep', ',' );
		update_option( 'woocommerce_price_decimal_sep', '.' );
		update_option( 'woocommerce_price_num_decimals', 0 );
		update_option( 'woocommerce_default_country', 'IR:THR' );
		update_option( 'woocommerce_weight_unit', 'kg' );
		update_option( 'woocommerce_dimension_unit', 'cm' );
	}

	/**
	 * Inserts one post, tagging it as demo content and assigning its terms.
	 *
	 * @param array $postarr Post fields.
	 * @param array $terms   Taxonomy mapped to a list of term names.
	 * @return int|false The new post ID, or false on failure.
	 */
	private function create_post( array $postarr, array $terms = array() ) {
		$postarr = wp_parse_args(
			$postarr,
			array(
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
			)
		);

		// Skip a post with this exact title so re-running does not duplicate it.
		if ( $this->title_exists( $postarr['post_title'], $postarr['post_type'] ) ) {
			return false;
		}

		$post_id = wp_insert_post( $postarr, true );

		if ( is_wp_error( $post_id ) ) {
			return false;
		}

		update_post_meta( $post_id, self::OWNER_META, 1 );
		$this->log_post( $post_id );

		foreach ( $terms as $taxonomy => $names ) {
			if ( taxonomy_exists( $taxonomy ) ) {
				wp_set_object_terms( $post_id, $names, $taxonomy, false );
			}
		}

		return $post_id;
	}

	/**
	 * Whether a post of this type already carries this exact title.
	 *
	 * Replaces get_page_by_title(), which core deprecated in 6.2.
	 *
	 * @param string $title     Title to look for.
	 * @param string $post_type Post type to search.
	 * @return bool
	 */
	private function title_exists( $title, $post_type ) {
		$found = get_posts(
			array(
				'post_type'              => $post_type,
				'title'                  => $title,
				'post_status'            => 'any',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		return ! empty( $found );
	}

	/**
	 * The stored import log.
	 *
	 * @return array
	 */
	private function get_log() {
		$log = get_option( self::LOG_OPTION, array() );

		return is_array( $log ) ? $log : array();
	}

	/**
	 * Writes a value into the import log.
	 *
	 * @param string $key   Log key.
	 * @param mixed  $value Value to store.
	 */
	private function log( $key, $value ) {
		$log         = $this->get_log();
		$log[ $key ] = $value;

		update_option( self::LOG_OPTION, $log, false );
	}

	/**
	 * Records a created post so the rollback can find it.
	 *
	 * @param int $post_id Post ID.
	 */
	private function log_post( $post_id ) {
		$log            = $this->get_log();
		$log['posts']   = isset( $log['posts'] ) ? (array) $log['posts'] : array();
		$log['posts'][] = (int) $post_id;

		update_option( self::LOG_OPTION, $log, false );
	}

	/**
	 * Deletes everything the last import created.
	 *
	 * @return int Number of objects removed.
	 */
	public function rollback() {
		$log     = $this->get_log();
		$removed = 0;

		foreach ( (array) ( isset( $log['posts'] ) ? $log['posts'] : array() ) as $post_id ) {
			if ( get_post_meta( $post_id, self::OWNER_META, true ) && wp_delete_post( $post_id, true ) ) {
				++$removed;
			}
		}

		foreach ( (array) ( isset( $log['pages'] ) ? $log['pages'] : array() ) as $page_id ) {
			if ( get_post_meta( $page_id, self::OWNER_META, true ) && wp_delete_post( $page_id, true ) ) {
				++$removed;
			}
		}

		foreach ( (array) ( isset( $log['menus'] ) ? $log['menus'] : array() ) as $menu_id ) {
			if ( wp_delete_nav_menu( $menu_id ) ) {
				++$removed;
			}
		}

		update_option( 'show_on_front', 'posts' );
		delete_option( 'page_on_front' );
		delete_option( 'page_for_posts' );
		delete_option( self::LOG_OPTION );

		Mahan_Options::reset();

		return $removed;
	}

	/**
	 * The demo that was imported last, if any.
	 *
	 * @return string
	 */
	public static function imported_demo() {
		$log = get_option( self::LOG_OPTION, array() );

		return is_array( $log ) && isset( $log['demo'] ) ? (string) $log['demo'] : '';
	}
}
