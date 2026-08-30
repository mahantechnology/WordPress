<?php
/**
 * Starter site: beauty salon / spa.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'زیبایی، با آرامش', 'mahan' ),

	'options' => array(
		'header_layout'      => 'centered',
		'footer_layout'      => 'centered',
		'header_transparent' => true,
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'نوبت‌دهی آنلاین · همه‌روزه ۱۰ تا ۲۱', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'masonry',
		'blog_sidebar'       => 'none',
		'radius'             => 26,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'سالن زیبایی و اسپا با کادر متخصص، مواد اولیهٔ اورجینال و فضایی آرام برای رسیدگی به خودتان.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'کوتاهی و رنگ مو', 'mahan' ),
			'excerpt' => __( 'مشاورهٔ رنگ متناسب با پوست شما و رنگ‌های بدون آمونیاک.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sparkles' ),
		),
		array(
			'title'   => __( 'میکاپ عروس', 'mahan' ),
			'excerpt' => __( 'گریم ماندگار همراه با تست چهره پیش از روز مراسم.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'star' ),
		),
		array(
			'title'   => __( 'پاکسازی پوست', 'mahan' ),
			'excerpt' => __( 'هیدرافیشیال، میکرودرم و ماسک‌های تخصصی هر نوع پوست.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'heart' ),
		),
		array(
			'title'   => __( 'ناخن و مانیکور', 'mahan' ),
			'excerpt' => __( 'کاشت، ژلیش و طراحی، با ابزار کاملاً استریل.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'gift' ),
		),
		array(
			'title'   => __( 'ماساژ و اسپا', 'mahan' ),
			'excerpt' => __( 'ماساژ ریلکسی، سنگ داغ و اسکراب بدن در اتاق اختصاصی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'moon' ),
		),
		array(
			'title'   => __( 'خدمات ابرو و مژه', 'mahan' ),
			'excerpt' => __( 'میکروبلیدینگ، لیفت مژه و اکستنشن مو به مو.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'eye' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'مهسا افشار', 'mahan' ),
			'excerpt' => __( 'کولوریست با گواهی بین‌المللی رنگ و مش.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'متخصص رنگ مو', 'mahan' ) ),
		),
		array(
			'title'   => __( 'رؤیا بهرامی', 'mahan' ),
			'excerpt' => __( 'میکاپ‌آرتیست عروس با بیش از چهارصد کار موفق.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'میکاپ‌آرتیست', 'mahan' ) ),
		),
		array(
			'title'   => __( 'سمیرا یزدانی', 'mahan' ),
			'excerpt' => __( 'کارشناس پوست و مو، متخصص درمان آکنه و لک.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'متخصص پوست', 'mahan' ) ),
		),
		array(
			'title'   => __( 'پریسا اکبری', 'mahan' ),
			'excerpt' => __( 'طراح ناخن و مدرس دوره‌های کاشت و ژلیش.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'طراح ناخن', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'     => array(
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
									'image'           => $media->wide( 5 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'سالن زیبایی و اسپا ماهان', 'mahan' ),
									'title'           => __( 'زیبایی، با آرامش', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'یک ساعت برای خودتان بگذارید؛ بقیه‌اش با ما. نوبت آنلاین بگیرید و بی‌معطلی بیایید.', 'mahan' ),
									'primary_text'    => __( 'رزرو نوبت', 'mahan' ),
									'secondary_text'  => __( 'خدمات و تعرفه', 'mahan' ),
								)
							),
						),
						mahan_el_full()
					)
					->row(
						array(
							mahan_el(
								'icon-box',
								array(
									'show_heading' => '',
									'style'        => 'plain',
									'columns'      => '3',
									'items'        => array(
										array(
											'icon'  => 'shield',
											'title' => __( 'بهداشت کامل', 'mahan' ),
											'text'  => __( 'همهٔ ابزارها پس از هر مشتری استریل می‌شوند.', 'mahan' ),
										),
										array(
											'icon'  => 'star',
											'title' => __( 'مواد اورجینال', 'mahan' ),
											'text'  => __( 'فقط برندهای معتبر و قابل ردیابی.', 'mahan' ),
										),
										array(
											'icon'  => 'clock',
											'title' => __( 'بدون معطلی', 'mahan' ),
											'text'  => __( 'با نوبت آنلاین، سر ساعت پذیرش می‌شوید.', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_padding( 56 )
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'خدمات سالن', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'از یک اصلاح ساده تا میکاپ عروس، همه زیر یک سقف.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'before-after',
								array(
									'before_image' => $media->portrait( 0 ),
									'after_image'  => $media->portrait( 1 ),
									'before_label' => __( 'قبل', 'mahan' ),
									'after_label'  => __( 'بعد', 'mahan' ),
								)
							),
						),
						mahan_el_bg( '#fdf2f8' )
					)
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'images'          => $media->gallery( 'portrait', 6, 0 ),
									'title'           => __( 'نمونهٔ کارها', 'mahan' ),
									'title_highlight' => 2,
									'layout'          => 'masonry',
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
									'title'           => __( 'بسته‌های ویژه', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'ترکیب چند خدمت، با قیمتی کمتر از مجموعشان.', 'mahan' ),
									'columns'         => '3',
									'plans'           => array(
										array(
											'name'        => __( 'بستهٔ تازگی', 'mahan' ),
											'price'       => '۱,۲۰۰,۰۰۰',
											'period'      => __( 'تومان', 'mahan' ),
											'features'    => __( "پاکسازی پوست\nاصلاح ابرو\nماساژ صورت", 'mahan' ),
											'button_text' => __( 'رزرو بسته', 'mahan' ),
										),
										array(
											'name'        => __( 'بستهٔ عروس', 'mahan' ),
											'price'       => '۶,۵۰۰,۰۰۰',
											'period'      => __( 'تومان', 'mahan' ),
											'features'    => __( "تست چهره\nمیکاپ روز مراسم\nشینیون\nمانیکور و پدیکور\nپاکسازی پیش از مراسم", 'mahan' ),
											'featured'    => 'yes',
											'badge'       => __( 'پرطرفدار', 'mahan' ),
											'button_text' => __( 'رزرو بسته', 'mahan' ),
										),
										array(
											'name'        => __( 'بستهٔ آرامش', 'mahan' ),
											'price'       => '۲,۴۰۰,۰۰۰',
											'period'      => __( 'تومان', 'mahan' ),
											'features'    => __( "ماساژ ریلکسی کامل\nاسکراب بدن\nماسک مو\nپذیرایی با دمنوش", 'mahan' ),
											'button_text' => __( 'رزرو بسته', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_bg( '#fdf2f8' )
					)
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'متخصصان ما', 'mahan' ),
									'title_highlight' => 1,
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
									'title'           => __( 'نظر مشتریان', 'mahan' ),
									'title_highlight' => 2,
									'source'          => 'cpt',
									'card_style'      => 'bubble',
									'slides_to_show'  => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'cta-banner',
								array(
									'icon'        => 'calendar',
									'title'       => __( 'نوبت‌تان را همین حالا رزرو کنید', 'mahan' ),
									'text'        => __( 'ظرفیت آخر هفته‌ها زود پر می‌شود.', 'mahan' ),
									'button_text' => __( 'رزرو آنلاین نوبت', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'services' => array(
			'title'    => __( 'خدمات و تعرفه', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'فهرست کامل خدمات', 'mahan' ),
									'title_highlight' => 2,
									'posts_per_page'  => 12,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های متداول', 'mahan' ),
									'title_highlight' => 1,
									'faqs'           => array(
										array(
											'question' => __( 'برای رزرو باید بیعانه بدهم؟', 'mahan' ),
											'answer'   => __( 'برای خدمات کوتاه خیر. برای بستهٔ عروس و خدمات بالای سه ساعت، ۳۰٪ بیعانه دریافت می‌شود.', 'mahan' ),
										),
										array(
											'question' => __( 'اگر نتوانم بیایم چه کنم؟', 'mahan' ),
											'answer'   => __( 'تا ۲۴ ساعت پیش از نوبت، لغو یا جابه‌جایی بدون هزینه انجام می‌شود.', 'mahan' ),
										),
										array(
											'question' => __( 'محصولات مورد استفاده چه برندهایی هستند؟', 'mahan' ),
											'answer'   => __( 'فهرست برندها روی میز پذیرش موجود است و پیش از شروع کار به شما نشان داده می‌شود.', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_bg( '#fdf2f8' )
					)
					->to_array();
			},
		),
		'gallery'  => array(
			'title'    => __( 'گالری', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'images'          => $media->gallery( 'card', 8, 0 ),
									'title'           => __( 'کارهای ما', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'mosaic',
									'columns'         => '4',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'  => array(
			'title'    => __( 'رزرو نوبت', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'ما اینجاییم', 'mahan' ),
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
		'blog'     => array( 'title' => __( 'مجلهٔ زیبایی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی سالن زیبایی', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'services' => array(
					'title' => __( 'خدمات و تعرفه', 'mahan' ),
					'page'  => 'services',
					'icon'  => 'sparkles',
					'mega'  => true,
				),
				'gallery'  => array(
					'title' => __( 'گالری', 'mahan' ),
					'page'  => 'gallery',
					'icon'  => 'camera',
				),
				'blog'     => array(
					'title' => __( 'مجله', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'  => array(
					'title' => __( 'رزرو نوبت', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'calendar',
					'badge' => __( 'آنلاین', 'mahan' ),
				),
			),
		),
	),

	'widgets' => array(),
);
