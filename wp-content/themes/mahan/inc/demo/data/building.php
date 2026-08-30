<?php
/**
 * Starter site: construction / architecture firm.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'از نقشه تا کلید', 'mahan' ),

	'options' => array(
		'header_layout'      => 'classic',
		'footer_layout'      => 'columns',
		'header_transparent' => true,
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'بازدید و برآورد رایگان پروژه در تهران و کرج', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'none',
		'radius'             => 2,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'شرکت ساختمانی و معماری با پروانهٔ پایه یک، مجری پروژه‌های مسکونی، اداری و صنعتی.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'طراحی معماری', 'mahan' ),
			'excerpt' => __( 'از ایده تا نقشه‌های اجرایی و رندرهای سه‌بعدی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'pen' ),
		),
		array(
			'title'   => __( 'پیمانکاری عمومی', 'mahan' ),
			'excerpt' => __( 'اجرای کامل پروژه با مدیریت پیمان و کنترل هزینه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'building' ),
		),
		array(
			'title'   => __( 'بازسازی و نوسازی', 'mahan' ),
			'excerpt' => __( 'تبدیل فضای قدیمی به خانه‌ای امروزی، بدون تخریب کامل.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'refresh' ),
		),
		array(
			'title'   => __( 'طراحی داخلی', 'mahan' ),
			'excerpt' => __( 'نورپردازی، چیدمان و انتخاب متریال متناسب با سبک زندگی شما.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'home' ),
		),
		array(
			'title'   => __( 'نظارت و کنترل کیفیت', 'mahan' ),
			'excerpt' => __( 'حضور ناظر مقیم و گزارش هفتگی پیشرفت کار.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'shield' ),
		),
		array(
			'title'   => __( 'مقاوم‌سازی', 'mahan' ),
			'excerpt' => __( 'ارزیابی سازه و اجرای طرح تقویت بر اساس آیین‌نامهٔ ۲۸۰۰.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lock' ),
		),
	),

	'portfolio' => array(
		array(
			'title'   => __( 'برج مسکونی آرام', 'mahan' ),
			'excerpt' => __( 'دوازده طبقه، چهل واحد، نمای سرامیک خشک، تحویل ۱۴۰۲.', 'mahan' ),
		),
		array(
			'title'   => __( 'ساختمان اداری هورا', 'mahan' ),
			'excerpt' => __( 'اسکلت فلزی، نمای کرتین‌وال، شش هزار مترمربع.', 'mahan' ),
		),
		array(
			'title'   => __( 'ویلای جنگلی نور', 'mahan' ),
			'excerpt' => __( 'سازهٔ چوب و بتن، هماهنگ با شیب زمین و درختان موجود.', 'mahan' ),
		),
		array(
			'title'   => __( 'بازسازی خانهٔ شهری', 'mahan' ),
			'excerpt' => __( 'یک آپارتمان چهل‌ساله که به فضایی روشن و باز تبدیل شد.', 'mahan' ),
		),
		array(
			'title'   => __( 'انبار صنعتی پارس', 'mahan' ),
			'excerpt' => __( 'سوله با دهانهٔ سی متر، کف بتن اسلب و جرثقیل سقفی.', 'mahan' ),
		),
		array(
			'title'   => __( 'مجتمع تجاری نگین', 'mahan' ),
			'excerpt' => __( 'سه طبقه تجاری با آتریوم مرکزی و نورگیر سقفی.', 'mahan' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'مهندس فرهاد رستمی', 'mahan' ),
			'excerpt' => __( 'کارشناس ارشد سازه، دارندهٔ پروانهٔ پایه یک نظام مهندسی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر پروژه', 'mahan' ) ),
		),
		array(
			'title'   => __( 'مهندس سارا کیانی', 'mahan' ),
			'excerpt' => __( 'معمار، با تمرکز بر طراحی پایدار و مصرف کم انرژی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'سرپرست معماری', 'mahan' ) ),
		),
		array(
			'title'   => __( 'مهندس یاسر عبدی', 'mahan' ),
			'excerpt' => __( 'متخصص تأسیسات مکانیکی و سیستم‌های گرمایش از کف.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر تأسیسات', 'mahan' ) ),
		),
		array(
			'title'   => __( 'مهندس الهام نیک‌پور', 'mahan' ),
			'excerpt' => __( 'کنترل پروژه و برآورد هزینه، مسلط به متره و برآورد.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'کنترل پروژه', 'mahan' ) ),
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
									'image'           => $media->wide( 4 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'شرکت ساختمانی ماهان', 'mahan' ),
									'title'           => __( 'از نقشه تا کلید، کنار شما', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'طراحی، اجرا و نظارت پروژه‌های مسکونی و اداری با زمان‌بندی روشن و هزینهٔ شفاف.', 'mahan' ),
									'primary_text'    => __( 'مشاهدهٔ پروژه‌ها', 'mahan' ),
									'secondary_text'  => __( 'درخواست برآورد', 'mahan' ),
								)
							),
						),
						mahan_el_full()
					)
					->row(
						array(
							mahan_el(
								'stats-counter',
								array(
									'show_heading' => '',
									'columns'      => '4',
									'counters'     => array(
										array(
											'value'  => 26,
											'label'  => __( 'سال فعالیت', 'mahan' ),
											'icon'   => 'calendar',
										),
										array(
											'value'  => 140,
											'suffix' => '+',
											'label'  => __( 'پروژهٔ تحویل‌شده', 'mahan' ),
											'icon'   => 'building',
										),
										array(
											'value'  => 320,
											'suffix' => __( ' هزار', 'mahan' ),
											'label'  => __( 'مترمربع ساخت', 'mahan' ),
											'icon'   => 'grid',
										),
										array(
											'value'  => 65,
											'label'  => __( 'مهندس و تکنسین', 'mahan' ),
											'icon'   => 'user',
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
								'portfolio-grid',
								array(
									'title'           => __( 'پروژه‌های اجراشده', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'کارنامهٔ ما را در مصالح و جزئیات ببینید، نه در حرف.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
									'style'           => 'overlay',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'خدمات ما', 'mahan' ),
									'title_highlight' => 1,
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
									'title'           => __( 'مراحل کار', 'mahan' ),
									'title_highlight' => 2,
									'steps'           => array(
										array(
											'title' => __( 'بازدید و برآورد', 'mahan' ),
											'text'  => __( 'زمین یا ساختمان را می‌بینیم و برآورد اولیه می‌دهیم.', 'mahan' ),
											'icon'  => 'map-pin',
										),
										array(
											'title' => __( 'طراحی و تأیید', 'mahan' ),
											'text'  => __( 'نقشه و رندر را تا رسیدن به طرح دلخواه شما اصلاح می‌کنیم.', 'mahan' ),
											'icon'  => 'pen',
										),
										array(
											'title' => __( 'اجرا', 'mahan' ),
											'text'  => __( 'با برنامهٔ زمان‌بندی و گزارش هفتگی پیشرفت کار.', 'mahan' ),
											'icon'  => 'truck',
										),
										array(
											'title' => __( 'تحویل و گارانتی', 'mahan' ),
											'text'  => __( 'تحویل کلید، به‌همراه دو سال ضمانت اجرای سازه.', 'mahan' ),
											'icon'  => 'check-circle',
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'before-after',
								array(
									'before_image' => $media->card( 5 ),
									'after_image'  => $media->card( 6 ),
									'before_label' => __( 'پیش از بازسازی', 'mahan' ),
									'after_label'  => __( 'پس از بازسازی', 'mahan' ),
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
									'logos'           => array(
										array( 'logo' => $media->logo( 0 ), 'name' => __( 'کارفرمای ۱', 'mahan' ) ),
										array( 'logo' => $media->logo( 1 ), 'name' => __( 'کارفرمای ۲', 'mahan' ) ),
										array( 'logo' => $media->logo( 2 ), 'name' => __( 'کارفرمای ۳', 'mahan' ) ),
										array( 'logo' => $media->logo( 3 ), 'name' => __( 'کارفرمای ۴', 'mahan' ) ),
										array( 'logo' => $media->logo( 4 ), 'name' => __( 'کارفرمای ۵', 'mahan' ) ),
										array( 'logo' => $media->logo( 5 ), 'name' => __( 'کارفرمای ۶', 'mahan' ) ),
									),
									'title'           => __( 'کارفرمایان ما', 'mahan' ),
									'title_highlight' => 1,
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'cta-banner',
								array(
									'icon'        => 'phone',
									'title'       => __( 'پروژه‌ای در ذهن دارید؟', 'mahan' ),
									'text'        => __( 'بازدید و برآورد اولیه در تهران و کرج رایگان است.', 'mahan' ),
									'button_text' => __( 'درخواست بازدید', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'projects'  => array(
			'title'    => __( 'پروژه‌ها', 'mahan' ),
			'sections' => static function ( $media ) {
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
									'style'           => 'card',
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
									'title'           => __( 'خدمات ساختمانی', 'mahan' ),
									'title_highlight' => 2,
									'posts_per_page'  => 12,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'     => array(
			'title'    => __( 'دربارهٔ شرکت', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 7 ),
									'title'           => __( 'ساختنی که می‌ماند', 'mahan' ),
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
									'title'           => __( 'تیم مهندسی', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'contact'   => array(
			'title'    => __( 'تماس و برآورد', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'دفتر مرکزی', 'mahan' ),
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
		'blog'      => array( 'title' => __( 'یادداشت‌های فنی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی شرکت ساختمانی', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'projects' => array(
					'title' => __( 'پروژه‌ها', 'mahan' ),
					'page'  => 'projects',
					'icon'  => 'building',
					'mega'  => true,
				),
				'services' => array(
					'title' => __( 'خدمات', 'mahan' ),
					'page'  => 'services',
				),
				'about'    => array(
					'title' => __( 'دربارهٔ شرکت', 'mahan' ),
					'page'  => 'about',
				),
				'contact'  => array(
					'title' => __( 'درخواست برآورد', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'phone',
				),
			),
		),
	),

	'widgets' => array(),
);
