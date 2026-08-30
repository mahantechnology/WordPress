<?php
/**
 * Starter site: gym / fitness club.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'قوی‌تر از دیروز', 'mahan' ),

	'options' => array(
		'header_layout'      => 'classic',
		'footer_layout'      => 'columns',
		'header_transparent' => true,
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'هر روز از ۶ صبح تا ۱۲ شب · جلسهٔ اول رایگان', 'mahan' ),
		'header_cart'        => false,
		'header_search'      => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'right',
		'radius'             => 6,
		'dark_mode'          => 'auto',
		'footer_about_text'  => __( 'باشگاهی با تجهیزات روز، مربیان حرفه‌ای و برنامهٔ تمرینی اختصاصی برای هر بدن.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'بدن‌سازی و فیتنس', 'mahan' ),
			'excerpt' => __( 'سالن مجهز با دستگاه‌های حرفه‌ای و برنامهٔ تمرینی اختصاصی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lightning' ),
		),
		array(
			'title'   => __( 'کراس‌فیت', 'mahan' ),
			'excerpt' => __( 'تمرین‌های پرشدت گروهی برای افزایش استقامت و قدرت.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'target' ),
		),
		array(
			'title'   => __( 'یوگا و پیلاتس', 'mahan' ),
			'excerpt' => __( 'کلاس‌های آرامش‌بخش برای انعطاف، تعادل و تمرکز.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'heart' ),
		),
		array(
			'title'   => __( 'تی‌آر‌ایکس', 'mahan' ),
			'excerpt' => __( 'تمرین با وزن بدن، مناسب همهٔ سطح‌ها از مبتدی تا پیشرفته.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'refresh' ),
		),
		array(
			'title'   => __( 'مشاورهٔ تغذیه', 'mahan' ),
			'excerpt' => __( 'برنامهٔ غذایی متناسب با هدف شما: کاهش وزن یا افزایش حجم.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'stethoscope' ),
		),
		array(
			'title'   => __( 'آمادگی جسمانی بانوان', 'mahan' ),
			'excerpt' => __( 'سانس اختصاصی بانوان با مربی خانم و فضای کاملاً مجزا.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'star' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'آرش کریمی', 'mahan' ),
			'excerpt' => __( 'مربی بین‌المللی بدن‌سازی با پانزده سال سابقهٔ تمرین‌دهی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'سرمربی بدن‌سازی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'نگار رحیمی', 'mahan' ),
			'excerpt' => __( 'مدرس یوگا و پیلاتس، دارای مدرک بین‌المللی آموزش.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مربی یوگا', 'mahan' ) ),
		),
		array(
			'title'   => __( 'سعید مقدم', 'mahan' ),
			'excerpt' => __( 'قهرمان کراس‌فیت کشور و طراح برنامه‌های گروهی باشگاه.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مربی کراس‌فیت', 'mahan' ) ),
		),
		array(
			'title'   => __( 'دکتر لیلا صادقی', 'mahan' ),
			'excerpt' => __( 'متخصص تغذیه و رژیم‌درمانی ورزشی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'کارشناس تغذیه', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'    => array(
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
								'hero-slider',
								array(
									'slides' => array(
										array(
											'image'           => $media->wide( 0 ),
											'eyebrow'         => __( 'باشگاه ورزشی ماهان', 'mahan' ),
											'title'           => __( 'قوی‌تر از دیروز شو', 'mahan' ),
											'text'            => __( 'با برنامهٔ تمرینی اختصاصی و مربی همراه، مسیر تناسب اندام را از همین هفته شروع کنید.', 'mahan' ),
											'button_text'     => __( 'شروع رایگان', 'mahan' ),
											'align'           => 'right',
										),
										array(
											'image'           => $media->wide( 1 ),
											'eyebrow'         => __( 'کلاس‌های گروهی', 'mahan' ),
											'title'           => __( 'تمرین در کنار هم، نتیجهٔ دوچندان', 'mahan' ),
											'text'            => __( 'کراس‌فیت، تی‌آر‌ایکس، یوگا و پیلاتس؛ هر روز هفته با مربیان حرفه‌ای.', 'mahan' ),
											'button_text'     => __( 'برنامهٔ کلاس‌ها', 'mahan' ),
											'align'           => 'center',
										),
									),
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
											'value'  => 1200,
											'suffix' => '+',
											'label'  => __( 'ورزشکار فعال', 'mahan' ),
											'icon'   => 'user',
										),
										array(
											'value'  => 24,
											'label'  => __( 'مربی حرفه‌ای', 'mahan' ),
											'icon'   => 'star',
										),
										array(
											'value'  => 40,
											'suffix' => '+',
											'label'  => __( 'کلاس در هفته', 'mahan' ),
											'icon'   => 'calendar',
										),
										array(
											'value'  => 18,
											'label'  => __( 'سال تجربه', 'mahan' ),
											'icon'   => 'shield',
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
									'title'           => __( 'کلاس‌ها و خدمات', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'برای هر هدفی، برنامه‌ای هست؛ کافی است شروع کنید.', 'mahan' ),
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
									'before_image' => $media->card( 0 ),
									'after_image'  => $media->card( 1 ),
									'before_label' => __( 'قبل از تمرین', 'mahan' ),
									'after_label'  => __( 'بعد از سه ماه', 'mahan' ),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'پلن‌های عضویت', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'بدون قرارداد بلندمدت؛ هر زمان خواستید پلن را عوض کنید.', 'mahan' ),
									'columns'         => '3',
									'plans'           => array(
										array(
											'name'        => __( 'ماهانه', 'mahan' ),
											'price'       => '۹۸۰,۰۰۰',
											'period'      => __( 'تومان / ماه', 'mahan' ),
											'features'    => __( "دسترسی آزاد به سالن\nدو کلاس گروهی در هفته\nمشاورهٔ اولیهٔ تغذیه", 'mahan' ),
											'button_text' => __( 'انتخاب پلن', 'mahan' ),
										),
										array(
											'name'        => __( 'سه‌ماهه', 'mahan' ),
											'price'       => '۲,۶۰۰,۰۰۰',
											'period'      => __( 'تومان / سه ماه', 'mahan' ),
											'features'    => __( "همهٔ امکانات پلن ماهانه\nکلاس‌های گروهی نامحدود\nبرنامهٔ تمرینی اختصاصی\nیک جلسه مربی خصوصی", 'mahan' ),
											'featured'    => 'yes',
											'badge'       => __( 'محبوب‌ترین', 'mahan' ),
											'button_text' => __( 'انتخاب پلن', 'mahan' ),
										),
										array(
											'name'        => __( 'سالانه', 'mahan' ),
											'price'       => '۸,۹۰۰,۰۰۰',
											'period'      => __( 'تومان / سال', 'mahan' ),
											'features'    => __( "همهٔ امکانات پلن سه‌ماهه\nکمد اختصاصی\nپنج جلسه مربی خصوصی\nتخفیف فروشگاه مکمل", 'mahan' ),
											'button_text' => __( 'انتخاب پلن', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'مربیان ما', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'کسانی که قدم‌به‌قدم همراه شما هستند.', 'mahan' ),
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
									'title'           => __( 'از زبان ورزشکاران', 'mahan' ),
									'title_highlight' => 2,
									'source'          => 'cpt',
									'card_style'      => 'card',
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
									'icon'        => 'lightning',
									'title'       => __( 'جلسهٔ اول مهمان ما باشید', 'mahan' ),
									'text'        => __( 'یک جلسهٔ کامل تمرین با مربی، بدون هیچ هزینه‌ای.', 'mahan' ),
									'button_text' => __( 'رزرو جلسهٔ رایگان', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'classes' => array(
			'title'    => __( 'کلاس‌ها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'برنامهٔ کامل کلاس‌ها', 'mahan' ),
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
									'title'           => __( 'پرسش‌های پرتکرار', 'mahan' ),
									'title_highlight' => 1,
									'faqs'           => array(
										array(
											'question' => __( 'برای شروع به تجربهٔ قبلی نیاز دارم؟', 'mahan' ),
											'answer'   => __( 'اصلاً. برنامهٔ مبتدی‌ها با حرکات پایه شروع می‌شود و مربی تمام جلسه کنار شماست.', 'mahan' ),
										),
										array(
											'question' => __( 'چه چیزهایی باید همراه بیاورم؟', 'mahan' ),
											'answer'   => __( 'لباس و کفش ورزشی، حوله و قمقمهٔ آب. کمد و حمام در باشگاه در اختیار شماست.', 'mahan' ),
										),
										array(
											'question' => __( 'امکان تعلیق عضویت وجود دارد؟', 'mahan' ),
											'answer'   => __( 'بله، در پلن‌های سه‌ماهه و سالانه تا دو هفته در سال می‌توانید عضویت را متوقف کنید.', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'about'   => array(
			'title'    => __( 'دربارهٔ باشگاه', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 2 ),
									'title'           => __( 'باشگاهی که برای ماندن ساخته شده', 'mahan' ),
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
								'gallery-grid',
								array(
									'images'          => $media->gallery( 'card', 6, 2 ),
									'title'           => __( 'فضای باشگاه', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'masonry',
									'columns'         => '3',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'contact' => array(
			'title'    => __( 'ثبت‌نام و تماس', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'به ما سر بزنید', 'mahan' ),
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
		'blog'    => array( 'title' => __( 'مجلهٔ تندرستی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی باشگاه', 'mahan' ),
			'items' => array(
				'home'    => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'classes' => array(
					'title' => __( 'کلاس‌ها', 'mahan' ),
					'page'  => 'classes',
					'icon'  => 'lightning',
				),
				'about'   => array(
					'title' => __( 'دربارهٔ باشگاه', 'mahan' ),
					'page'  => 'about',
				),
				'blog'    => array(
					'title' => __( 'مجله', 'mahan' ),
					'page'  => 'blog',
				),
				'contact' => array(
					'title' => __( 'ثبت‌نام', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'calendar',
					'badge' => __( 'رایگان', 'mahan' ),
				),
			),
		),
	),

	'widgets' => array(
		'sidebar-blog' => array(
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'تازه‌ترین مطالب', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'date',
					'thumbnail' => true,
				),
			),
			array(
				'type'     => 'mahan_contact',
				'instance' => array( 'title' => __( 'تماس با باشگاه', 'mahan' ) ),
			),
		),
	),
);
