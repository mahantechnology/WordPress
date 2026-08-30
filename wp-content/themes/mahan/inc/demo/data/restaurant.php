<?php
/**
 * Starter site: restaurant / cafe.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'طعمی که به یاد می‌ماند', 'mahan' ),

	'options' => array(
		'header_layout'      => 'centered',
		'footer_layout'      => 'centered',
		'header_transparent' => true,
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'هر روز از ۱۲ ظهر تا نیمه‌شب پذیرای شما هستیم', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'none',
		'radius'             => 8,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'آشپزخانهٔ ما هر روز با مواد اولیهٔ تازه و محلی کار می‌کند.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'خوراک مخصوص سرآشپز', 'mahan' ),
			'excerpt' => __( 'گوشت گوساله با سس مخصوص، سبزیجات فصل و نان تازه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'utensils' ),
		),
		array(
			'title'   => __( 'پاستای خانگی', 'mahan' ),
			'excerpt' => __( 'خمیر تازهٔ روزانه با سس گوجهٔ کندسوز و ریحان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'utensils' ),
		),
		array(
			'title'   => __( 'صبحانهٔ کامل', 'mahan' ),
			'excerpt' => __( 'از ۸ تا ۱۱ صبح، با نان محلی و مربای خانگی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sun' ),
		),
		array(
			'title'   => __( 'دسر روز', 'mahan' ),
			'excerpt' => __( 'هر روز یک دسر تازه از دستان شیرینی‌پز ما.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'gift' ),
		),
		array(
			'title'   => __( 'قهوهٔ تخصصی', 'mahan' ),
			'excerpt' => __( 'دانه‌های تک‌خاستگاه، رست‌شده در کارگاه خودمان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sparkles' ),
		),
		array(
			'title'   => __( 'منوی گیاهی', 'mahan' ),
			'excerpt' => __( 'انتخاب‌های کامل و مغذی برای گیاه‌خواران.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'heart' ),
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
								'hero-banner',
								array(
									'image' => $media->wide( 0 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'رستوران و کافه', 'mahan' ),
									'title'           => __( 'طعمی که به یاد می‌ماند', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'آشپزخانه‌ای باز، مواد اولیهٔ محلی و منویی که هر فصل تازه می‌شود.', 'mahan' ),
									'primary_text'    => __( 'رزرو میز', 'mahan' ),
									'secondary_text'  => __( 'مشاهدهٔ منو', 'mahan' ),
									'stats'           => array(),
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
											'icon'  => 'utensils',
											'title' => __( 'مواد اولیهٔ تازه', 'mahan' ),
											'text'  => __( 'هر روز صبح از بازار محلی خرید می‌کنیم.', 'mahan' ),
										),
										array(
											'icon'  => 'clock',
											'title' => __( 'سرو سریع', 'mahan' ),
											'text'  => __( 'میانگین زمان آماده‌سازی، کمتر از ۱۵ دقیقه.', 'mahan' ),
										),
										array(
											'icon'  => 'heart',
											'title' => __( 'فضای دنج', 'mahan' ),
											'text'  => __( 'مناسب دورهمی‌های خانوادگی و جلسه‌های کاری.', 'mahan' ),
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
									'title'           => __( 'پیشنهاد سرآشپز', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'منتخبی از محبوب‌ترین خوراک‌های منو.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'images' => $media->gallery( 'card', 6, 0 ),
									'title'           => __( 'گالری تصاویر', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'mosaic',
									'columns'         => '4',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'video-popup',
								array(
									'poster' => $media->wide( 1 ),
									'title' => __( 'یک روز در آشپزخانهٔ ما', 'mahan' ),
									'text'  => __( 'ببینید غذای شما چطور آماده می‌شود.', 'mahan' ),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'testimonial-carousel',
								array(
									'title'           => __( 'نظر مهمانان', 'mahan' ),
									'title_highlight' => 1,
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
									'title'       => __( 'میزتان را رزرو کنید', 'mahan' ),
									'text'        => __( 'برای شب‌های آخر هفته، رزرو پیشاپیش را توصیه می‌کنیم.', 'mahan' ),
									'button_text' => __( 'رزرو آنلاین', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'menu'    => array(
			'title'    => __( 'منو', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'منوی کامل', 'mahan' ),
									'title_highlight' => 1,
									'posts_per_page'  => 18,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'about'   => array(
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image' => $media->card( 1 ),
									'title'           => __( 'داستان ما', 'mahan' ),
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
									'title'           => __( 'تیم آشپزخانه', 'mahan' ),
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
			'title'    => __( 'رزرو و تماس', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'ما را پیدا کنید', 'mahan' ),
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
		'blog'    => array( 'title' => __( 'یادداشت‌های آشپزی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی رستوران', 'mahan' ),
			'items' => array(
				'home'    => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
				),
				'menu'    => array(
					'title' => __( 'منو', 'mahan' ),
					'page'  => 'menu',
					'icon'  => 'utensils',
				),
				'about'   => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'blog'    => array(
					'title' => __( 'یادداشت‌ها', 'mahan' ),
					'page'  => 'blog',
				),
				'contact' => array(
					'title' => __( 'رزرو میز', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'calendar',
				),
			),
		),
	),

	'widgets' => array(),
);
