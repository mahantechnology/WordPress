<?php
/**
 * Starter site: personal portfolio.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'طراح و توسعه‌دهندهٔ محصولات دیجیتال', 'mahan' ),

	'options' => array(
		'header_layout'      => 'minimal',
		'footer_layout'      => 'centered',
		'topbar_enabled'     => false,
		'header_cart'        => false,
		'header_wishlist'    => false,
		'header_transparent' => true,
		'blog_layout'        => 'list',
		'blog_sidebar'       => 'none',
		'single_layout'      => 'narrow',
		'single_sidebar'     => 'none',
		'container_width'    => 1120,
		'dark_mode'          => 'toggle',
		'footer_about_text'  => __( 'اگر پروژه‌ای در ذهن دارید، خوشحال می‌شوم دربارهٔ آن بشنوم.', 'mahan' ),
	),

	'portfolio' => array(
		array(
			'title'   => __( 'بازطراحی اپلیکیشن بانکی', 'mahan' ),
			'excerpt' => __( 'بازطراحی کامل تجربهٔ کاربری یک اپلیکیشن بانکی با تمرکز بر سادگی.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'رابط کاربری', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'هویت بصری استودیو هنر', 'mahan' ),
			'excerpt' => __( 'طراحی لوگو، رنگ‌بندی و سیستم تایپوگرافی برای یک استودیوی هنری.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'برندینگ', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'فروشگاه آنلاین پوشاک', 'mahan' ),
			'excerpt' => __( 'طراحی و پیاده‌سازی فروشگاهی با تمرکز بر نرخ تبدیل و سرعت.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'وب', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'داشبورد تحلیل داده', 'mahan' ),
			'excerpt' => __( 'یک داشبورد مدیریتی برای نمایش شاخص‌های کلیدی به‌صورت لحظه‌ای.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'رابط کاربری', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'وب‌سایت کنفرانس فناوری', 'mahan' ),
			'excerpt' => __( 'صفحهٔ فرود و سامانهٔ ثبت‌نام برای یک رویداد دوروزه.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'وب', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'بسته‌بندی محصول ارگانیک', 'mahan' ),
			'excerpt' => __( 'طراحی بسته‌بندی یک خط محصول غذایی با رویکرد پایدار.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'برندینگ', 'mahan' ) ) ),
		),
	),

	'pages' => array(
		'home'      => array(
			'title'    => __( 'صفحهٔ اصلی', 'mahan' ),
			'meta'     => array(
				'_mahan_layout'      => 'full',
				'_mahan_transparent' => '1',
			),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'hero-banner',
								array(
									'layout'          => 'split',
									'eyebrow'         => __( 'سلام، من نگار هستم', 'mahan' ),
									'title'           => __( 'محصولات دیجیتالی می‌سازم که مردم دوست‌شان دارند', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'ده سال است روی طراحی و توسعهٔ محصولات وب و موبایل کار می‌کنم.', 'mahan' ),
									'primary_text'    => __( 'نمونه‌کارها', 'mahan' ),
									'secondary_text'  => __( 'دانلود رزومه', 'mahan' ),
									'stats'           => array(
										array(
											'number' => '۱۲۰',
											'suffix' => '+',
											'label'  => __( 'پروژهٔ تحویل‌شده', 'mahan' ),
										),
										array(
											'number' => '۱۰',
											'suffix' => __( ' سال', 'mahan' ),
											'label'  => __( 'تجربهٔ حرفه‌ای', 'mahan' ),
										),
										array(
											'number' => '۴۵',
											'suffix' => '+',
											'label'  => __( 'مشتری راضی', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_padding( 120, 72 )
					)
					->row(
						array(
							mahan_el(
								'typewriter',
								array(
									'before' => __( 'تخصص من در', 'mahan' ),
									'words'  => __( "طراحی رابط کاربری\nتوسعهٔ فرانت‌اند\nطراحی سیستم\nتحقیق کاربر", 'mahan' ),
								)
							),
						),
						mahan_el_padding( 24, 24 )
					)
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'نمونه‌کارها', 'mahan' ),
									'title_highlight' => 1,
									'style'           => 'overlay',
									'posts_per_page'  => 6,
									'columns'         => '3',
									'show_filter'     => 'yes',
								)
							),
						)
					)
					->section(
						array(
							Mahan_Elementor_Builder::column(
								50,
								array(
									mahan_el(
										'progress-bars',
										array(
											'title'           => __( 'مهارت‌ها', 'mahan' ),
											'title_highlight' => 1,
											'heading_align'   => 'right',
										)
									),
								)
							),
							Mahan_Elementor_Builder::column(
								50,
								array(
									mahan_el(
										'timeline',
										array(
											'title'           => __( 'سوابق کاری', 'mahan' ),
											'title_highlight' => 1,
											'heading_align'   => 'right',
											'style'           => 'single',
										)
									),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'testimonial-carousel',
								array(
									'title'           => __( 'نظر همکاران', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'card_style'      => 'minimal',
									'slides_to_show'  => '2',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'cta-banner',
								array(
									'style'       => 'outline',
									'title'       => __( 'بیایید با هم کار کنیم', 'mahan' ),
									'text'        => __( 'برای شروع یک پروژهٔ تازه یا فقط یک گفتگوی کوتاه، پیام بدهید.', 'mahan' ),
									'button_text' => __( 'ارسال پیام', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'     => array(
			'title'    => __( 'دربارهٔ من', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'title'           => __( 'کمی دربارهٔ من', 'mahan' ),
									'title_highlight' => 1,
									'heading_align'   => 'right',
									'media_position'  => 'right',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'title'           => __( 'فضای کار', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'masonry',
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'portfolio' => array(
			'title'    => __( 'نمونه‌کارها', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'همهٔ پروژه‌ها', 'mahan' ),
									'title_highlight' => 1,
									'posts_per_page'  => 12,
									'columns'         => '3',
									'show_filter'     => 'yes',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'   => array(
			'title'    => __( 'تماس', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'راه‌های ارتباطی', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '3',
									'style'           => 'plain',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'social-icons',
								array(
									'style' => 'circle',
									'align' => 'center',
								)
							),
						),
						mahan_el_padding( 0, 72 )
					)
					->to_array();
			},
		),
		'blog'      => array( 'title' => __( 'یادداشت‌ها', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی شخصی', 'mahan' ),
			'items' => array(
				'home'      => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
				),
				'about'     => array(
					'title' => __( 'دربارهٔ من', 'mahan' ),
					'page'  => 'about',
				),
				'portfolio' => array(
					'title' => __( 'نمونه‌کارها', 'mahan' ),
					'page'  => 'portfolio',
				),
				'blog'      => array(
					'title' => __( 'یادداشت‌ها', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'   => array(
					'title' => __( 'تماس', 'mahan' ),
					'page'  => 'contact',
				),
			),
		),
	),

	'widgets' => array(),
);
