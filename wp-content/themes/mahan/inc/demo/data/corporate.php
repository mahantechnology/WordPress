<?php
/**
 * Starter site: corporate / business.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'راهکارهای فناوری برای کسب‌وکارهای در حال رشد', 'mahan' ),

	'options' => array(
		'header_layout'      => 'classic',
		'footer_layout'      => 'columns',
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'مشاورهٔ رایگان برای پروژه‌های سازمانی', 'mahan' ),
		'header_cart'        => false,
		'header_wishlist'    => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'right',
		'footer_dark'        => true,
		'footer_about_text'  => __( 'ما با ترکیب تجربه و فناوری روز، راهکارهایی می‌سازیم که کسب‌وکار شما را یک گام جلوتر می‌برد.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'مشاورهٔ تحول دیجیتال', 'mahan' ),
			'excerpt' => __( 'نقشهٔ راه دیجیتالی‌سازی سازمان شما را بر پایهٔ داده طراحی می‌کنیم.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'target' ),
		),
		array(
			'title'   => __( 'توسعهٔ نرم‌افزار سفارشی', 'mahan' ),
			'excerpt' => __( 'سامانه‌های تحت وب و موبایل، متناسب با فرایندهای واقعی سازمان شما.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'code' ),
		),
		array(
			'title'   => __( 'زیرساخت و امنیت', 'mahan' ),
			'excerpt' => __( 'طراحی، استقرار و پایش زیرساخت با تمرکز بر پایداری و امنیت.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'shield' ),
		),
		array(
			'title'   => __( 'تحلیل داده و گزارش‌سازی', 'mahan' ),
			'excerpt' => __( 'داشبوردهای مدیریتی که تصمیم‌گیری را ساده و سریع می‌کنند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'chart' ),
		),
		array(
			'title'   => __( 'پشتیبانی و نگهداری', 'mahan' ),
			'excerpt' => __( 'قرارداد پشتیبانی با سطح خدمات مشخص و پاسخ‌گویی تضمین‌شده.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'headphones' ),
		),
		array(
			'title'   => __( 'آموزش تیم‌های فنی', 'mahan' ),
			'excerpt' => __( 'دوره‌های تخصصی درون‌سازمانی برای توانمندسازی تیم شما.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'graduation' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'رضا احمدی', 'mahan' ),
			'excerpt' => __( 'پانزده سال تجربه در معماری نرم‌افزارهای سازمانی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیرعامل', 'mahan' ) ),
		),
		array(
			'title'   => __( 'مریم سلطانی', 'mahan' ),
			'excerpt' => __( 'طراح تجربهٔ کاربری با تمرکز بر محصولات داده‌محور.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر محصول', 'mahan' ) ),
		),
		array(
			'title'   => __( 'کامران نوری', 'mahan' ),
			'excerpt' => __( 'متخصص زیرساخت ابری و امنیت اطلاعات.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر فنی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'هستی مرادی', 'mahan' ),
			'excerpt' => __( 'مسئول ارتباط با مشتریان و توسعهٔ بازار.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر بازاریابی', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'     => array(
			'title'    => __( 'صفحهٔ اصلی', 'mahan' ),
			'meta'     => array( '_mahan_layout' => 'full' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'hero-banner',
								array(
									'image' => $media->wide( 0 ),
									'layout'         => 'split',
									'eyebrow'        => __( 'شریک فناوری شما', 'mahan' ),
									'title'          => __( 'کسب‌وکار خود را با فناوری جلو ببرید', 'mahan' ),
									'title_highlight'=> 2,
									'description'    => __( 'از مشاوره تا اجرا و پشتیبانی، تمام مسیر تحول دیجیتال را کنار شما هستیم.', 'mahan' ),
									'primary_text'   => __( 'درخواست مشاوره', 'mahan' ),
									'secondary_text' => __( 'خدمات ما', 'mahan' ),
								)
							),
						),
						mahan_el_padding( 0, 40 )
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'خدمات ما', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'هر آنچه یک سازمان برای رشد دیجیتال نیاز دارد، زیر یک سقف.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'process-steps',
								array(
									'title'           => __( 'چطور کار می‌کنیم؟', 'mahan' ),
									'title_highlight' => 1,
									'style'           => 'connected',
									'columns'         => '4',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
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
									'title'           => __( 'تیم ما', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'columns'         => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'testimonial-carousel',
								array(
									'title'           => __( 'مشتریان ما چه می‌گویند', 'mahan' ),
									'title_highlight' => 2,
									'source'          => 'cpt',
									'slides_to_show'  => '3',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'logo-carousel',
								array(
									'logos' => array(
										array( 'logo' => $media->logo( 0 ) ),
										array( 'logo' => $media->logo( 1 ) ),
										array( 'logo' => $media->logo( 2 ) ),
										array( 'logo' => $media->logo( 3 ) ),
										array( 'logo' => $media->logo( 4 ) ),
										array( 'logo' => $media->logo( 5 ) ),
									),
									'title'           => __( 'همکاران ما', 'mahan' ),
									'title_highlight' => 1,
									'slides_to_show'  => '5',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'post-grid',
								array(
									'title'           => __( 'آخرین مطالب', 'mahan' ),
									'title_highlight' => 1,
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
									'title'       => __( 'آمادهٔ شروع پروژهٔ بعدی هستید؟', 'mahan' ),
									'text'        => __( 'فرم تماس را پر کنید تا کارشناسان ما در کمتر از یک روز کاری با شما تماس بگیرند.', 'mahan' ),
									'button_text' => __( 'تماس با ما', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'    => array(
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image' => $media->card( 0 ),
									'title'           => __( 'ما چه کسانی هستیم؟', 'mahan' ),
									'title_highlight' => 1,
									'heading_align'   => 'right',
									'subtitle'        => __( 'یک تیم چندتخصصی که از سال ۱۳۹۸ در حوزهٔ فناوری اطلاعات فعالیت می‌کند.', 'mahan' ),
									'media_position'  => 'left',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'card-flip',
								array(
									'title'           => __( 'ارزش‌های ما', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '3',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
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
					->to_array();
			},
		),
		'services' => array(
			'title'    => __( 'خدمات', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'خدمات ما', 'mahan' ),
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
									'title'           => __( 'پلن‌های همکاری', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '3',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های متداول', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'two',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'  => array(
			'title'    => __( 'تماس با ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'با ما در ارتباط باشید', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
								)
							),
						)
					)
					->row( array( mahan_el( 'map-embed' ) ) )
					->to_array();
			},
		),
		'blog'     => array( 'title' => __( 'بلاگ', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی اصلی', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'about'    => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'services' => array(
					'title' => __( 'خدمات', 'mahan' ),
					'page'  => 'services',
					'icon'  => 'layers',
				),
				'blog'     => array(
					'title' => __( 'بلاگ', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'  => array(
					'title' => __( 'تماس با ما', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'phone',
				),
			),
		),
	),

	'widgets' => array(
		'sidebar-blog' => array(
			array(
				'type'     => 'search',
				'instance' => array( 'title' => __( 'جستجو', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'آخرین مطالب', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'date',
					'thumbnail' => true,
				),
			),
			array(
				'type'     => 'mahan_contact',
				'instance' => array( 'title' => __( 'تماس سریع', 'mahan' ) ),
			),
		),
	),
);
