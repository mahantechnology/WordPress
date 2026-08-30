<?php
/**
 * Starter site: medical clinic.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'سلامت شما، اولویت ماست', 'mahan' ),

	'options' => array(
		'header_layout'     => 'classic',
		'footer_layout'     => 'columns',
		'topbar_enabled'    => true,
		'topbar_text'       => __( 'نوبت‌دهی آنلاین، همه‌روزه از ۸ تا ۲۰', 'mahan' ),
		'header_cart'       => false,
		'header_wishlist'   => false,
		'blog_layout'       => 'grid',
		'blog_sidebar'      => 'right',
		'radius'            => 20,
		'footer_about_text' => __( 'کلینیک ما با کادری مجرب و تجهیزات روز، در کنار سلامت شماست.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'پزشکی عمومی', 'mahan' ),
			'excerpt' => __( 'ویزیت، معاینهٔ دوره‌ای و پیگیری درمان توسط پزشکان عمومی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'stethoscope' ),
		),
		array(
			'title'   => __( 'دندان‌پزشکی', 'mahan' ),
			'excerpt' => __( 'خدمات ترمیمی، زیبایی و جراحی با تجهیزات پیشرفته.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sparkles' ),
		),
		array(
			'title'   => __( 'تصویربرداری', 'mahan' ),
			'excerpt' => __( 'رادیولوژی، سونوگرافی و تصویربرداری تخصصی در محل کلینیک.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'camera' ),
		),
		array(
			'title'   => __( 'آزمایشگاه', 'mahan' ),
			'excerpt' => __( 'نمونه‌گیری در محل و دریافت آنلاین نتیجهٔ آزمایش.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'chart' ),
		),
		array(
			'title'   => __( 'فیزیوتراپی', 'mahan' ),
			'excerpt' => __( 'برنامهٔ توان‌بخشی اختصاصی زیر نظر متخصص.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'heart' ),
		),
		array(
			'title'   => __( 'مشاورهٔ تغذیه', 'mahan' ),
			'excerpt' => __( 'رژیم درمانی و پیگیری هفتگی برای رسیدن به وزن هدف.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'gift' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'دکتر مینا شریفی', 'mahan' ),
			'excerpt' => __( 'متخصص داخلی با ۱۵ سال سابقهٔ بالینی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'متخصص داخلی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'دکتر آرش کاویانی', 'mahan' ),
			'excerpt' => __( 'جراح و متخصص دندان‌پزشکی زیبایی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'دندان‌پزشک', 'mahan' ) ),
		),
		array(
			'title'   => __( 'دکتر لیلا موسوی', 'mahan' ),
			'excerpt' => __( 'متخصص تغذیه و رژیم درمانی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'متخصص تغذیه', 'mahan' ) ),
		),
		array(
			'title'   => __( 'دکتر پویا صادقی', 'mahan' ),
			'excerpt' => __( 'متخصص فیزیوتراپی و توان‌بخشی ورزشی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'فیزیوتراپیست', 'mahan' ) ),
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
									'eyebrow'         => __( 'کلینیک تخصصی ماهان', 'mahan' ),
									'title'           => __( 'سلامت شما در دستان مطمئن', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'نوبت‌دهی آنلاین، پروندهٔ الکترونیک و کادر درمان مجرب، همه در یک‌جا.', 'mahan' ),
									'primary_text'    => __( 'رزرو نوبت', 'mahan' ),
									'secondary_text'  => __( 'تماس با کلینیک', 'mahan' ),
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
									'title'           => __( 'بخش‌های درمانی', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'خدمات تشخیصی و درمانی، همه زیر یک سقف.', 'mahan' ),
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
									'title'           => __( 'مراحل دریافت نوبت', 'mahan' ),
									'title_highlight' => 2,
									'style'           => 'connected',
									'columns'         => '4',
									'steps'           => array(
										array(
											'icon'  => 'search',
											'title' => __( 'انتخاب پزشک', 'mahan' ),
											'text'  => __( 'پزشک و بخش مورد نظرتان را انتخاب کنید.', 'mahan' ),
										),
										array(
											'icon'  => 'calendar',
											'title' => __( 'انتخاب زمان', 'mahan' ),
											'text'  => __( 'از میان زمان‌های آزاد، مناسب‌ترین را انتخاب کنید.', 'mahan' ),
										),
										array(
											'icon'  => 'check-circle',
											'title' => __( 'تأیید نوبت', 'mahan' ),
											'text'  => __( 'کد رهگیری نوبت برای شما پیامک می‌شود.', 'mahan' ),
										),
										array(
											'icon'  => 'stethoscope',
											'title' => __( 'مراجعه', 'mahan' ),
											'text'  => __( 'در زمان تعیین‌شده به کلینیک مراجعه کنید.', 'mahan' ),
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
									'title'           => __( 'کادر درمان', 'mahan' ),
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
								'stats-counter',
								array(
									'show_heading' => '',
									'columns'      => '4',
									'counters'     => array(
										array(
											'icon'  => 'user',
											'value' => 25000,
											'label' => __( 'بیمار درمان‌شده', 'mahan' ),
										),
										array(
											'icon'  => 'stethoscope',
											'value' => 32,
											'label' => __( 'پزشک متخصص', 'mahan' ),
										),
										array(
											'icon'  => 'clock',
											'value' => 15,
											'label' => __( 'سال تجربه', 'mahan' ),
										),
										array(
											'icon'  => 'star',
											'value' => 97,
											'suffix'=> '٪',
											'label' => __( 'رضایت مراجعان', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های پزشکی متداول', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'two',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'cta-banner',
								array(
									'icon'        => 'phone',
									'title'       => __( 'نیاز به مشاورهٔ فوری دارید؟', 'mahan' ),
									'text'        => __( 'کارشناسان ما همه‌روزه از ۸ تا ۲۰ پاسخگوی شما هستند.', 'mahan' ),
									'button_text' => __( 'تماس بگیرید', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'services'=> array(
			'title'    => __( 'خدمات درمانی', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'همهٔ خدمات', 'mahan' ),
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
		'doctors' => array(
			'title'    => __( 'پزشکان', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'کادر درمان کلینیک', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'columns'         => '4',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact' => array(
			'title'    => __( 'نوبت‌دهی و تماس', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'راه‌های ارتباط با کلینیک', 'mahan' ),
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
		'blog'    => array( 'title' => __( 'مطالب سلامت', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی کلینیک', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'services' => array(
					'title' => __( 'خدمات', 'mahan' ),
					'page'  => 'services',
					'icon'  => 'stethoscope',
				),
				'doctors'  => array(
					'title' => __( 'پزشکان', 'mahan' ),
					'page'  => 'doctors',
				),
				'blog'     => array(
					'title' => __( 'مطالب سلامت', 'mahan' ),
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

	'widgets' => array(
		'sidebar-blog' => array(
			array(
				'type'     => 'mahan_contact',
				'instance' => array( 'title' => __( 'تماس با کلینیک', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'مطالب سلامت', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'date',
					'thumbnail' => true,
				),
			),
		),
	),
);
