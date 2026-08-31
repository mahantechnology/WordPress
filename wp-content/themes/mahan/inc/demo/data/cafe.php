<?php
/**
 * Starter site: coffee shop.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'قهوه‌ای که روزت را می‌سازد', 'mahan' ),

	'options' => array(
		'header_layout'      => 'glass',
		'footer_layout'      => 'minimal',
		'header_transparent' => true,
		'header_cta_text'    => __( 'رزرو میز', 'mahan' ),
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'هر روز از ۸ صبح تا ۱۱ شب · صبحانه تا ۱۱:۳۰', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'none',
		'radius'             => 14,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'دانه‌های تک‌خاستگاه، رست‌شده در کارگاه خودمان و دم‌آوری با دقت.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'اسپرسو و شیرقهوه', 'mahan' ),
			'excerpt' => __( 'از ریسترتو تا لاته، با شیر تازهٔ روزانه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sparkles' ),
		),
		array(
			'title'   => __( 'دم‌آوری دستی', 'mahan' ),
			'excerpt' => __( 'V60، کمکس و ایروپرس؛ با معرفی خاستگاه دانه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'refresh' ),
		),
		array(
			'title'   => __( 'صبحانهٔ کافه', 'mahan' ),
			'excerpt' => __( 'املت، نان تازه و مربای خانگی تا ۱۱:۳۰ صبح.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sun' ),
		),
		array(
			'title'   => __( 'کیک و دسر', 'mahan' ),
			'excerpt' => __( 'هر روز چند دسر تازه از شیرینی‌پز خودمان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'gift' ),
		),
		array(
			'title'   => __( 'فروش دانهٔ قهوه', 'mahan' ),
			'excerpt' => __( 'بسته‌های ۲۵۰ گرمی، آسیاب‌شده یا دانه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'cart' ),
		),
		array(
			'title'   => __( 'کارگاه باریستا', 'mahan' ),
			'excerpt' => __( 'دوره‌های کوتاه آشنایی با قهوه و دم‌آوری.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'graduation' ),
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
									'image'           => $media->wide( 1 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'کافهٔ ماهان', 'mahan' ),
									'title'           => __( 'قهوه‌ای که روزت را می‌سازد', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'دانه‌های تک‌خاستگاه، رست تازه و باریستاهایی که قهوه را جدی می‌گیرند.', 'mahan' ),
									'primary_text'    => __( 'دیدن منو', 'mahan' ),
									'secondary_text'  => __( 'رزرو میز', 'mahan' ),
									'stats'           => array(),
								)
							),
						),
						mahan_el_full()
					)
					->row(
						array(
							mahan_el(
								'feature-list',
								array(
									'title'           => __( 'چرا این‌جا؟', 'mahan' ),
									'title_highlight' => 1,
									'style'           => 'circle',
									'columns'         => '3',
									'items'           => array(
										array(
											'icon' => 'sparkles',
											'text' => __( 'رست تازه، هر هفته', 'mahan' ),
											'note' => __( 'دانه‌ها بیش از ده روز از رست نمی‌گذرند.', 'mahan' ),
										),
										array(
											'icon' => 'heart',
											'text' => __( 'فضای دنج برای کار', 'mahan' ),
											'note' => __( 'پریز کنار هر میز و اینترنت پرسرعت.', 'mahan' ),
										),
										array(
											'icon' => 'truck',
											'text' => __( 'ارسال دانه به سراسر کشور', 'mahan' ),
											'note' => __( 'سفارش تا ساعت ۱۴، همان روز ارسال می‌شود.', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_padding( 60 )
					)
					->row(
						array(
							mahan_el(
								'price-list',
								array(
									'title'           => __( 'منوی قهوه', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'قیمت‌ها بر حسب تومان و شامل مالیات است.', 'mahan' ),
									'columns'         => '2',
									'style'           => 'dotted',
									'rows'            => array(
										array(
											'title' => __( 'اسپرسو', 'mahan' ),
											'text'  => __( 'تک‌شات، از دانهٔ روز.', 'mahan' ),
											'price' => __( '۶۵,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
										),
										array(
											'title' => __( 'کاپوچینو', 'mahan' ),
											'text'  => __( 'با شیر تازه و فوم مخملی.', 'mahan' ),
											'price' => __( '۹۵,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
											'badge' => __( 'پرفروش', 'mahan' ),
										),
										array(
											'title' => __( 'لاته', 'mahan' ),
											'text'  => __( 'با طرح لاته‌آرت.', 'mahan' ),
											'price' => __( '۱۰۵,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
										),
										array(
											'title' => __( 'V60', 'mahan' ),
											'text'  => __( 'دم‌آوری دستی، با معرفی خاستگاه.', 'mahan' ),
											'price' => __( '۱۲۰,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
										),
										array(
											'title' => __( 'موکا', 'mahan' ),
											'text'  => __( 'با شکلات تلخ خانگی.', 'mahan' ),
											'price' => __( '۱۱۵,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
										),
										array(
											'title' => __( 'دمنوش فصل', 'mahan' ),
											'text'  => __( 'ترکیب گیاهی، بدون کافئین.', 'mahan' ),
											'price' => __( '۸۰,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
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
								'image-accordion',
								array(
									'title'           => __( 'فضای کافه', 'mahan' ),
									'title_highlight' => 1,
									'panels'          => array(
										array(
											'image' => $media->card( 0 ),
											'title' => __( 'بار قهوه', 'mahan' ),
											'text'  => __( 'جایی که همه‌چیز شروع می‌شود.', 'mahan' ),
										),
										array(
											'image' => $media->card( 1 ),
											'title' => __( 'میز کار', 'mahan' ),
											'text'  => __( 'دنج، ساکت و با پریز.', 'mahan' ),
										),
										array(
											'image' => $media->card( 2 ),
											'title' => __( 'حیاط', 'mahan' ),
											'text'  => __( 'برای روزهای آفتابی.', 'mahan' ),
										),
										array(
											'image' => $media->card( 3 ),
											'title' => __( 'کارگاه رست', 'mahan' ),
											'text'  => __( 'هر هفته، تازه.', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'opening-hours',
								array(
									'title'           => __( 'ساعات کاری', 'mahan' ),
									'title_highlight' => 1,
									'days'            => array(
										array(
											'day'   => __( 'شنبه تا چهارشنبه', 'mahan' ),
											'hours' => __( '۸:۰۰ تا ۲۳:۰۰', 'mahan' ),
										),
										array(
											'day'       => __( 'پنجشنبه و جمعه', 'mahan' ),
											'hours'     => __( '۹:۰۰ تا ۲۴:۰۰', 'mahan' ),
											'highlight' => 'yes',
										),
										array(
											'day'   => __( 'صبحانه', 'mahan' ),
											'hours' => __( 'تا ۱۱:۳۰', 'mahan' ),
										),
									),
									'note'            => __( 'در تعطیلات رسمی هم باز هستیم.', 'mahan' ),
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
									'title'           => __( 'نظر مهمانان', 'mahan' ),
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
									'title'       => __( 'میزتان را رزرو کنید', 'mahan' ),
									'text'        => __( 'آخر هفته‌ها شلوغ است؛ زودتر خبر بدهید.', 'mahan' ),
									'button_text' => __( 'رزرو میز', 'mahan' ),
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
									'title'           => __( 'همهٔ منو', 'mahan' ),
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
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 4 ),
									'title'           => __( 'از دانه تا فنجان', 'mahan' ),
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
								'blockquote',
								array(
									'style'  => 'gradient',
									'quote'  => __( 'قهوهٔ خوب عجله ندارد؛ وقت می‌خواهد و کمی وسواس.', 'mahan' ),
									'author' => __( 'سرباریستای کافه', 'mahan' ),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'contact' => array(
			'title'    => __( 'تماس و رزرو', 'mahan' ),
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
		'blog'    => array( 'title' => __( 'یادداشت‌های قهوه', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی کافه', 'mahan' ),
			'items' => array(
				'home'    => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
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
