<?php
/**
 * Starter site: digital agency.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'برندها را دیدنی می‌کنیم', 'mahan' ),

	'options' => array(
		'header_layout'      => 'split',
		'footer_layout'      => 'columns',
		'header_transparent' => true,
		'topbar_enabled'     => false,
		'header_cart'        => false,
		'header_wishlist'    => false,
		'blog_layout'        => 'masonry',
		'blog_sidebar'       => 'none',
		'radius'             => 24,
		'section_spacing'    => 100,
		'footer_dark'        => true,
		'footer_about_text'  => __( 'یک آژانس خلاق که استراتژی، طراحی و فناوری را کنار هم می‌گذارد.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'استراتژی برند', 'mahan' ),
			'excerpt' => __( 'جایگاه‌یابی، لحن برند و پیام‌های کلیدی، بر پایهٔ تحقیق بازار.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'target' ),
		),
		array(
			'title'   => __( 'طراحی هویت بصری', 'mahan' ),
			'excerpt' => __( 'لوگو، سیستم رنگ و تایپوگرافی که برند شما را متمایز می‌کند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'pen' ),
		),
		array(
			'title'   => __( 'طراحی و توسعهٔ وب', 'mahan' ),
			'excerpt' => __( 'سایت‌هایی سریع، زیبا و بهینه برای موتورهای جستجو.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'code' ),
		),
		array(
			'title'   => __( 'تولید محتوای ویدیویی', 'mahan' ),
			'excerpt' => __( 'از فیلم‌نامه تا تدوین نهایی، با تیم داخلی خودمان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'camera' ),
		),
		array(
			'title'   => __( 'کمپین‌های تبلیغاتی', 'mahan' ),
			'excerpt' => __( 'برنامه‌ریزی، اجرا و بهینه‌سازی کمپین در همهٔ کانال‌ها.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lightning' ),
		),
		array(
			'title'   => __( 'مدیریت شبکه‌های اجتماعی', 'mahan' ),
			'excerpt' => __( 'تقویم محتوایی، تولید و گزارش‌گیری ماهانه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'instagram' ),
		),
	),

	'portfolio' => array(
		array(
			'title'   => __( 'کمپین معرفی محصول نوشیدنی', 'mahan' ),
			'excerpt' => __( 'کمپین یکپارچهٔ دیجیتال با بیش از ۳ میلیون بازدید.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'کمپین', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'ری‌برندینگ یک بانک خصوصی', 'mahan' ),
			'excerpt' => __( 'بازطراحی کامل هویت بصری و راهنمای برند.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'برندینگ', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'وب‌سایت فروشگاهی پوشاک', 'mahan' ),
			'excerpt' => __( 'افزایش ۶۵ درصدی نرخ تبدیل پس از بازطراحی.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'وب', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'ویدیوی معرفی استارتاپ', 'mahan' ),
			'excerpt' => __( 'یک فیلم دو دقیقه‌ای که داستان محصول را روایت می‌کند.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'ویدیو', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'بستهٔ محتوایی شبکه‌های اجتماعی', 'mahan' ),
			'excerpt' => __( 'سه ماه تولید محتوا برای یک برند آرایشی.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'محتوا', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'اپلیکیشن سفارش غذا', 'mahan' ),
			'excerpt' => __( 'طراحی تجربهٔ کاربری و رابط برای اپلیکیشن موبایل.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'وب', 'mahan' ) ) ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'شیوا رستگار', 'mahan' ),
			'excerpt' => __( 'مدیر خلاقیت با سابقهٔ همکاری با برندهای ملی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر خلاقیت', 'mahan' ) ),
		),
		array(
			'title'   => __( 'آرین محمدی', 'mahan' ),
			'excerpt' => __( 'استراتژیست دیجیتال و تحلیلگر داده.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'استراتژیست', 'mahan' ) ),
		),
		array(
			'title'   => __( 'ندا آذری', 'mahan' ),
			'excerpt' => __( 'طراح گرافیک و متخصص هویت بصری.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'طراح ارشد', 'mahan' ) ),
		),
		array(
			'title'   => __( 'سامان دهقان', 'mahan' ),
			'excerpt' => __( 'کارگردان و تدوین‌گر تیم ویدیو.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'کارگردان', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'      => array(
			'title'    => __( 'صفحهٔ اصلی', 'mahan' ),
			'meta'     => array(
				'_mahan_layout'      => 'full',
				'_mahan_transparent' => '1',
			),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'hero-banner',
								array(
									'image' => $media->wide( 0 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'آژانس خلاق ماهان', 'mahan' ),
									'title'           => __( 'برندها را دیدنی می‌کنیم', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'استراتژی، طراحی و فناوری را کنار هم می‌گذاریم تا برند شما دیده شود.', 'mahan' ),
									'primary_text'    => __( 'نمونه‌کارها', 'mahan' ),
									'secondary_text'  => __( 'شروع پروژه', 'mahan' ),
									'stats'           => array(),
								)
							),
						),
						mahan_el_full()
					)
					->row(
						array(
							mahan_el(
								'marquee',
								array(
									'items' => __( "برندینگ\nطراحی وب\nکمپین دیجیتال\nتولید ویدیو\nشبکه‌های اجتماعی", 'mahan' ),
									'speed' => 20,
								)
							),
						),
						mahan_el_padding( 0 )
					)
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'کارهای اخیر', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'گزیده‌ای از پروژه‌هایی که به آن‌ها افتخار می‌کنیم.', 'mahan' ),
									'style'           => 'overlay',
									'posts_per_page'  => 6,
									'columns'         => '3',
									'show_filter'     => 'yes',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'چه کاری برایتان انجام می‌دهیم', 'mahan' ),
									'title_highlight' => 2,
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'process-steps',
								array(
									'title'           => __( 'فرایند کار ما', 'mahan' ),
									'title_highlight' => 2,
									'style'           => 'numbers',
									'columns'         => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'stats-counter',
								array(
									'show_heading' => '',
									'columns'      => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'آدم‌های پشت کار', 'mahan' ),
									'title_highlight' => 2,
									'source'          => 'cpt',
									'style'           => 'overlay',
									'columns'         => '4',
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
									'title'           => __( 'حرف مشتریان', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'slides_to_show'  => '2',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'post-grid',
								array(
									'title'           => __( 'از دفترچهٔ ما', 'mahan' ),
									'title_highlight' => 2,
									'posts_per_page'  => 3,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'cta-banner',
								array(
									'icon'        => 'sparkles',
									'title'       => __( 'ایده‌ای در سر دارید؟', 'mahan' ),
									'text'        => __( 'بیایید دربارهٔ آن حرف بزنیم؛ اولین جلسه مهمان ما هستید.', 'mahan' ),
									'button_text' => __( 'شروع گفتگو', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'work'      => array(
			'title'    => __( 'نمونه‌کارها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'همهٔ پروژه‌ها', 'mahan' ),
									'title_highlight' => 1,
									'style'           => 'masonry',
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
		'services'  => array(
			'title'    => __( 'خدمات', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'خدمات آژانس', 'mahan' ),
									'title_highlight' => 1,
									'posts_per_page'  => 9,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'بسته‌های همکاری', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'     => array(
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image' => $media->card( 0 ),
									'title'           => __( 'ما یک تیم کوچک و پرانرژی هستیم', 'mahan' ),
									'title_highlight' => 2,
									'heading_align'   => 'right',
									'media_position'  => 'left',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'timeline',
								array(
									'title'           => __( 'مسیر ما', 'mahan' ),
									'title_highlight' => 1,
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'images' => $media->gallery( 'card', 6, 1 ),
									'title'           => __( 'دفتر ما', 'mahan' ),
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
		'contact'   => array(
			'title'    => __( 'تماس', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'بیایید حرف بزنیم', 'mahan' ),
									'title_highlight' => 2,
									'columns'         => '4',
								)
							),
						)
					)
					->row( array( mahan_el( 'map-embed' ) ) )
					->to_array();
			},
		),
		'blog'      => array( 'title' => __( 'دفترچه', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی آژانس', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
				),
				'work'     => array(
					'title' => __( 'نمونه‌کارها', 'mahan' ),
					'page'  => 'work',
				),
				'services' => array(
					'title' => __( 'خدمات', 'mahan' ),
					'page'  => 'services',
				),
				'about'    => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'blog'     => array(
					'title' => __( 'دفترچه', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'  => array(
					'title' => __( 'تماس', 'mahan' ),
					'page'  => 'contact',
				),
			),
		),
	),

	'widgets' => array(),
);
