<?php
/**
 * Starter site: education / courses.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'یادگیری مهارت‌های واقعی، با مدرسان حرفه‌ای', 'mahan' ),

	'options' => array(
		'header_layout'     => 'classic',
		'footer_layout'     => 'columns',
		'topbar_enabled'    => true,
		'topbar_text'       => __( 'ثبت‌نام ترم جدید آغاز شد — ۲۰٪ تخفیف زودهنگام', 'mahan' ),
		'header_cart'       => true,
		'blog_layout'       => 'grid',
		'blog_sidebar'      => 'right',
		'footer_about_text' => __( 'آموزشگاه ما از سال ۱۳۹۵ بیش از ده هزار هنرجو را برای بازار کار آماده کرده است.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'دورهٔ جامع طراحی وب', 'mahan' ),
			'excerpt' => __( 'از HTML و CSS تا ساخت یک پروژهٔ واقعی؛ ۶۰ ساعت آموزش عملی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'code' ),
		),
		array(
			'title'   => __( 'دورهٔ طراحی رابط کاربری', 'mahan' ),
			'excerpt' => __( 'اصول طراحی، پروتوتایپ و تحویل طرح به تیم فنی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'pen' ),
		),
		array(
			'title'   => __( 'دورهٔ دیجیتال مارکتینگ', 'mahan' ),
			'excerpt' => __( 'سئو، تبلیغات و تحلیل داده برای رشد کسب‌وکار آنلاین.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'chart' ),
		),
		array(
			'title'   => __( 'دورهٔ برنامه‌نویسی پایتون', 'mahan' ),
			'excerpt' => __( 'از مبانی تا تحلیل داده و اتوماسیون کارهای روزمره.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'layers' ),
		),
		array(
			'title'   => __( 'کارگاه عکاسی محصول', 'mahan' ),
			'excerpt' => __( 'نورپردازی، ترکیب‌بندی و ویرایش عکس‌های فروشگاهی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'camera' ),
		),
		array(
			'title'   => __( 'دورهٔ مدیریت محصول', 'mahan' ),
			'excerpt' => __( 'از کشف مسئله تا تعریف نقشهٔ راه و اندازه‌گیری نتیجه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'target' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'دکتر سعید رحیمی', 'mahan' ),
			'excerpt' => __( 'مدرس برنامه‌نویسی با ۱۲ سال سابقهٔ تدریس دانشگاهی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدرس برنامه‌نویسی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'الهام فرهادی', 'mahan' ),
			'excerpt' => __( 'طراح ارشد محصول و مدرس دوره‌های تجربهٔ کاربری.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدرس طراحی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'بهنام یوسفی', 'mahan' ),
			'excerpt' => __( 'متخصص بازاریابی دیجیتال و مشاور رشد استارتاپ‌ها.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدرس بازاریابی', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'    => array(
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
									'layout'          => 'split',
									'eyebrow'         => __( 'ثبت‌نام ترم جدید', 'mahan' ),
									'title'           => __( 'مهارتی یاد بگیرید که بازار کار می‌خواهد', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'دوره‌های پروژه‌محور با پشتیبانی مستقیم مدرس و گواهی پایان دوره.', 'mahan' ),
									'primary_text'    => __( 'مشاهدهٔ دوره‌ها', 'mahan' ),
									'secondary_text'  => __( 'مشاورهٔ رایگان', 'mahan' ),
									'stats'           => array(
										array(
											'number' => '۱۰۰۰۰',
											'suffix' => '+',
											'label'  => __( 'هنرجو', 'mahan' ),
										),
										array(
											'number' => '۴۵',
											'suffix' => '',
											'label'  => __( 'دورهٔ فعال', 'mahan' ),
										),
										array(
											'number' => '۹۴',
											'suffix' => '٪',
											'label'  => __( 'رضایت هنرجویان', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_padding( 0, 48 )
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'دوره‌های محبوب', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'هر دوره با پروژهٔ واقعی، تمرین هفتگی و بازخورد مدرس همراه است.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'icon-box',
								array(
									'title'           => __( 'چرا ما؟', 'mahan' ),
									'title_highlight' => 1,
									'style'           => 'gradient',
									'columns'         => '4',
									'items'           => array(
										array(
											'icon'  => 'graduation',
											'title' => __( 'مدرسان حرفه‌ای', 'mahan' ),
											'text'  => __( 'همهٔ مدرسان، متخصصان شاغل در همان حوزه هستند.', 'mahan' ),
										),
										array(
											'icon'  => 'target',
											'title' => __( 'آموزش پروژه‌محور', 'mahan' ),
											'text'  => __( 'در پایان هر دوره یک نمونه‌کار واقعی خواهید داشت.', 'mahan' ),
										),
										array(
											'icon'  => 'headphones',
											'title' => __( 'پشتیبانی رفع اشکال', 'mahan' ),
											'text'  => __( 'پرسش‌های شما در کمتر از ۲۴ ساعت پاسخ داده می‌شود.', 'mahan' ),
										),
										array(
											'icon'  => 'check-circle',
											'title' => __( 'گواهی معتبر', 'mahan' ),
											'text'  => __( 'گواهی پایان دوره با امکان استعلام آنلاین.', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'مدرسان ما', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'style'           => 'circle',
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
									'title'           => __( 'پلن‌های عضویت', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'testimonial-carousel',
								array(
									'title'           => __( 'هنرجویان چه می‌گویند', 'mahan' ),
									'title_highlight' => 1,
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
		'courses' => array(
			'title'    => __( 'دوره‌ها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'همهٔ دوره‌ها', 'mahan' ),
									'title_highlight' => 1,
									'posts_per_page'  => 12,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'   => array(
			'title'    => __( 'دربارهٔ آموزشگاه', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image' => $media->card( 0 ),
									'title'           => __( 'دربارهٔ ما', 'mahan' ),
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
		'contact' => array(
			'title'    => __( 'تماس و ثبت‌نام', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'ثبت‌نام و مشاوره', 'mahan' ),
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
		'blog'    => array( 'title' => __( 'مقالات آموزشی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی آموزشگاه', 'mahan' ),
			'items' => array(
				'home'    => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'courses' => array(
					'title' => __( 'دوره‌ها', 'mahan' ),
					'page'  => 'courses',
					'icon'  => 'graduation',
					'badge' => __( 'جدید', 'mahan' ),
				),
				'about'   => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'blog'    => array(
					'title' => __( 'مقالات', 'mahan' ),
					'page'  => 'blog',
				),
				'contact' => array(
					'title' => __( 'ثبت‌نام', 'mahan' ),
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
					'title'     => __( 'مقالات تازه', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'date',
					'thumbnail' => true,
				),
			),
		),
	),
);
