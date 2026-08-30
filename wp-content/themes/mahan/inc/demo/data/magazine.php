<?php
/**
 * Starter site: magazine / news.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'تازه‌ترین خبرها و تحلیل‌های دنیای فناوری', 'mahan' ),

	'options' => array(
		'header_layout'          => 'split',
		'footer_layout'          => 'columns',
		'topbar_enabled'         => true,
		'topbar_text'            => __( 'هر روز صبح، خلاصهٔ مهم‌ترین خبرها', 'mahan' ),
		'header_cart'            => false,
		'header_wishlist'        => false,
		'blog_layout'            => 'magazine',
		'blog_columns'           => 3,
		'blog_sidebar'           => 'right',
		'blog_show_views'        => true,
		'single_toc'             => true,
		'single_progress_bar'    => true,
		'single_related_count'   => 4,
		'container_width'        => 1320,
		'footer_about_text'      => __( 'مجلهٔ آنلاین فناوری؛ خبر، تحلیل و آموزش، هر روز به‌روز.', 'mahan' ),
	),

	'pages' => array(
		'home'    => array(
			'title'    => __( 'صفحهٔ اصلی', 'mahan' ),
			'meta'     => array( '_mahan_layout' => 'full' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'marquee',
								array(
									'items' => __( "آخرین اخبار فناوری\nگزارش‌های ویژه\nمصاحبه با بنیان‌گذاران\nنقد و بررسی محصولات", 'mahan' ),
									'speed' => 26,
								)
							),
						),
						mahan_el_padding( 0 )
					)
					->section(
						array(
							Mahan_Elementor_Builder::column(
								66,
								array(
									mahan_el(
										'post-grid',
										array(
											'title'           => __( 'تیتر یک', 'mahan' ),
											'title_highlight' => 1,
											'heading_align'   => 'right',
											'card_style'      => 'overlay',
											'posts_per_page'  => 4,
											'columns'         => '2',
										)
									),
								)
							),
							Mahan_Elementor_Builder::column(
								34,
								array(
									mahan_el(
										'post-list',
										array(
											'title'           => __( 'پربازدیدترین‌ها', 'mahan' ),
											'title_highlight' => 1,
											'heading_align'   => 'right',
											'orderby'         => 'views',
											'posts_per_page'  => 6,
											'numbered'        => 'yes',
										)
									),
								)
							),
						),
						mahan_el_padding( 48, 48 )
					)
					->row(
						array(
							mahan_el(
								'post-tabs',
								array(
									'title'           => __( 'موضوع‌های داغ', 'mahan' ),
									'title_highlight' => 1,
									'posts_per_tab'   => 4,
									'columns'         => '4',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'category-boxes',
								array(
									'title'           => __( 'دسته‌بندی مطالب', 'mahan' ),
									'title_highlight' => 1,
									'taxonomy'        => 'category',
									'style'           => 'icon',
									'columns'         => '4',
									'count'           => 8,
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'post-carousel',
								array(
									'title'           => __( 'ویدیوها و گزارش‌های ویژه', 'mahan' ),
									'title_highlight' => 2,
									'posts_per_page'  => 9,
									'slides_to_show'  => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'newsletter-form',
								array(
									'style' => 'boxed',
									'title' => __( 'خبرنامهٔ روزانه', 'mahan' ),
									'text'  => __( 'هر روز صبح، مهم‌ترین خبرها را در ایمیل‌تان بخوانید.', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'   => array(
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'title'           => __( 'دربارهٔ مجله', 'mahan' ),
									'title_highlight' => 1,
									'heading_align'   => 'right',
									'media_position'  => 'left',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'تحریریه', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact' => array(
			'title'    => __( 'تماس با ما', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'ارتباط با تحریریه', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'blog'    => array( 'title' => __( 'آرشیو مطالب', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی مجله', 'mahan' ),
			'items' => array(
				'home'    => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'news'    => array(
					'title' => __( 'اخبار', 'mahan' ),
					'page'  => 'blog',
					'icon'  => 'lightning',
					'badge' => __( 'داغ', 'mahan' ),
					'mega'  => true,
				),
				'reviews' => array(
					'title' => __( 'نقد و بررسی', 'mahan' ),
					'url'   => home_url( '/category/reviews/' ),
				),
				'about'   => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'contact' => array(
					'title' => __( 'تماس', 'mahan' ),
					'page'  => 'contact',
				),
			),
		),
	),

	'widgets' => array(
		'sidebar-blog' => array(
			array(
				'type'     => 'search',
				'instance' => array( 'title' => __( 'جستجو در مجله', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'پربحث‌ترین‌ها', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'comment_count',
					'thumbnail' => true,
				),
			),
			array(
				'type'     => 'categories',
				'instance' => array(
					'title'        => __( 'دسته‌بندی‌ها', 'mahan' ),
					'count'        => 1,
					'hierarchical' => 0,
				),
			),
			array(
				'type'     => 'mahan_tags',
				'instance' => array(
					'title' => __( 'برچسب‌ها', 'mahan' ),
					'count' => 20,
				),
			),
		),
	),
);
