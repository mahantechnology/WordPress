<?php
/**
 * Starter site: real estate.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'خانهٔ تازه‌تان را این‌جا پیدا کنید', 'mahan' ),

	'options' => array(
		'header_layout'     => 'classic',
		'footer_layout'     => 'columns',
		'topbar_enabled'    => true,
		'topbar_text'       => __( 'مشاورهٔ رایگان خرید و فروش ملک', 'mahan' ),
		'header_cart'       => false,
		'header_wishlist'   => true,
		'blog_layout'       => 'grid',
		'blog_sidebar'      => 'right',
		'footer_about_text' => __( 'با بیش از ۱۵ سال تجربه در بازار مسکن، همراه مطمئن شما برای خرید، فروش و اجاره.', 'mahan' ),
	),

	'portfolio' => array(
		array(
			'title'   => __( 'آپارتمان ۱۲۰ متری، سعادت‌آباد', 'mahan' ),
			'excerpt' => __( 'سه خوابه، طبقهٔ چهارم، با پارکینگ و انباری، نوساز.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'آپارتمان', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'ویلای دوبلکس، لواسان', 'mahan' ),
			'excerpt' => __( 'زمین ۵۰۰ متر، بنای ۳۲۰ متر، استخر و باغ اختصاصی.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'ویلا', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'دفتر کار ۸۰ متری، ونک', 'mahan' ),
			'excerpt' => __( 'موقعیت اداری، آسانسور، مناسب استارتاپ‌ها.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'اداری', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'آپارتمان ۷۵ متری، جنت‌آباد', 'mahan' ),
			'excerpt' => __( 'دو خوابه، آفتاب‌گیر، مناسب زوج جوان.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'آپارتمان', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'مغازه ۴۵ متری، بازار', 'mahan' ),
			'excerpt' => __( 'بر اصلی، سند تک‌برگ، موقعیت پرتردد.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'تجاری', 'mahan' ) ) ),
		),
		array(
			'title'   => __( 'پنت‌هاوس ۲۲۰ متری، فرمانیه', 'mahan' ),
			'excerpt' => __( 'تراس اختصاصی، لابی مجلل، دید کامل به شهر.', 'mahan' ),
			'terms'   => array( 'mahan_portfolio_cat' => array( __( 'لوکس', 'mahan' ) ) ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'علی صابری', 'mahan' ),
			'excerpt' => __( 'کارشناس ارشد مناطق شمال تهران.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مشاور ارشد', 'mahan' ) ),
		),
		array(
			'title'   => __( 'زهرا نیک‌پور', 'mahan' ),
			'excerpt' => __( 'متخصص املاک تجاری و اداری.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مشاور تجاری', 'mahan' ) ),
		),
		array(
			'title'   => __( 'محمد بیات', 'mahan' ),
			'excerpt' => __( 'کارشناس ارزیابی و قیمت‌گذاری ملک.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'کارشناس ارزیابی', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'       => array(
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
									'layout'          => 'overlay',
									'eyebrow'         => __( 'مشاور املاک ماهان', 'mahan' ),
									'title'           => __( 'خانهٔ تازه‌تان را این‌جا پیدا کنید', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'بیش از دو هزار ملک فعال در سراسر شهر، با اطلاعات دقیق و بازدید رایگان.', 'mahan' ),
									'primary_text'    => __( 'جستجوی ملک', 'mahan' ),
									'secondary_text'  => __( 'ثبت ملک', 'mahan' ),
								)
							),
						),
						mahan_el_padding( 0 )
					)
					->row(
						array(
							mahan_el(
								'search-box',
								array(
									'placeholder' => __( 'منطقه، متراژ یا نوع ملک را بنویسید…', 'mahan' ),
									'button_text' => __( 'جستجو', 'mahan' ),
									'size'        => 'lg',
								)
							),
						),
						mahan_el_padding( 40 )
					)
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'ملک‌های منتخب', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'تازه‌ترین فایل‌های ثبت‌شده در دفتر ما.', 'mahan' ),
									'style'           => 'card',
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
								'category-boxes',
								array(
									'title'           => __( 'جستجو بر اساس نوع ملک', 'mahan' ),
									'title_highlight' => 2,
									'taxonomy'        => 'mahan_portfolio_cat',
									'style'           => 'icon',
									'columns'         => '5',
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
									'counters'     => array(
										array(
											'icon'  => 'home',
											'value' => 2400,
											'label' => __( 'ملک فعال', 'mahan' ),
										),
										array(
											'icon'  => 'check-circle',
											'value' => 1800,
											'label' => __( 'معاملهٔ موفق', 'mahan' ),
										),
										array(
											'icon'  => 'user',
											'value' => 24,
											'label' => __( 'مشاور حرفه‌ای', 'mahan' ),
										),
										array(
											'icon'  => 'clock',
											'value' => 15,
											'label' => __( 'سال تجربه', 'mahan' ),
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
									'title'           => __( 'مشاوران ما', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
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
									'title'           => __( 'مسیر خرید با ما', 'mahan' ),
									'title_highlight' => 2,
									'columns'         => '4',
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
									'icon'        => 'home',
									'title'       => __( 'ملک‌تان را رایگان ثبت کنید', 'mahan' ),
									'text'        => __( 'کارشناسان ما ظرف ۲۴ ساعت برای بازدید و قیمت‌گذاری با شما تماس می‌گیرند.', 'mahan' ),
									'button_text' => __( 'ثبت ملک', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'properties' => array(
			'title'    => __( 'ملک‌ها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'همهٔ ملک‌ها', 'mahan' ),
									'title_highlight' => 1,
									'style'           => 'card',
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
		'about'      => array(
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image' => $media->card( 0 ),
									'title'           => __( 'چرا ماهان؟', 'mahan' ),
									'title_highlight' => 1,
									'heading_align'   => 'right',
									'media_position'  => 'left',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'    => array(
			'title'    => __( 'تماس با ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'دفتر ما', 'mahan' ),
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
		'blog'       => array( 'title' => __( 'مقالات بازار مسکن', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی املاک', 'mahan' ),
			'items' => array(
				'home'       => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'properties' => array(
					'title' => __( 'ملک‌ها', 'mahan' ),
					'page'  => 'properties',
					'icon'  => 'building',
					'mega'  => true,
				),
				'about'      => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'blog'       => array(
					'title' => __( 'مقالات', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'    => array(
					'title' => __( 'تماس', 'mahan' ),
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
				'instance' => array( 'title' => __( 'جستجوی ملک', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_contact',
				'instance' => array( 'title' => __( 'مشاورهٔ رایگان', 'mahan' ) ),
			),
		),
	),
);
