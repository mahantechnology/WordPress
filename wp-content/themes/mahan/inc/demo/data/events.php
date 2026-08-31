<?php
/**
 * Starter site: conference / event.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'سه روز، سی سخنران، یک شهر', 'mahan' ),

	'options' => array(
		'header_layout'      => 'stack',
		'footer_layout'      => 'cta',
		'header_transparent' => true,
		'header_cta_text'    => __( 'خرید بلیت', 'mahan' ),
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'مهلت ثبت‌نام زودهنگام تا پایان همین هفته', 'mahan' ),
		'header_cart'        => false,
		'header_search'      => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'none',
		'radius'             => 10,
		'dark_mode'          => 'auto',
		'footer_about_text'  => __( 'همایش سالانهٔ ماهان؛ گردهمایی طراحان، توسعه‌دهندگان و مدیران محصول.', 'mahan' ),
	),

	'team' => array(
		array(
			'title'   => __( 'دکتر نیما شریفی', 'mahan' ),
			'excerpt' => __( 'پژوهشگر تعامل انسان و رایانه، دربارهٔ آیندهٔ رابط‌ها.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'سخنران کلیدی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'سحر امینی', 'mahan' ),
			'excerpt' => __( 'مدیر محصول، دربارهٔ ساختن تیم‌هایی که تحویل می‌دهند.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'سخنران', 'mahan' ) ),
		),
		array(
			'title'   => __( 'بابک تهرانی', 'mahan' ),
			'excerpt' => __( 'مهندس نرم‌افزار، دربارهٔ معماری سرویس‌های پرترافیک.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'سخنران', 'mahan' ) ),
		),
		array(
			'title'   => __( 'الهه کاظمی', 'mahan' ),
			'excerpt' => __( 'طراح تجربهٔ کاربری، دربارهٔ دسترس‌پذیری در وب فارسی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'سخنران', 'mahan' ) ),
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
									'image'           => $media->wide( 5 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'همایش سالانهٔ ماهان', 'mahan' ),
									'title'           => __( 'سه روز، سی سخنران، یک شهر', 'mahan' ),
									'title_highlight' => 3,
									'description'     => __( 'گردهمایی طراحان، توسعه‌دهندگان و مدیران محصول؛ با کارگاه‌های عملی و شبکه‌سازی.', 'mahan' ),
									'primary_text'    => __( 'خرید بلیت', 'mahan' ),
									'secondary_text'  => __( 'برنامهٔ همایش', 'mahan' ),
									'stats'           => array(
										array( 'number' => __( '۳', 'mahan' ), 'label' => __( 'روز', 'mahan' ) ),
										array( 'number' => __( '۳۰', 'mahan' ), 'label' => __( 'سخنران', 'mahan' ) ),
										array( 'number' => __( '۱۲', 'mahan' ), 'label' => __( 'کارگاه', 'mahan' ) ),
									),
								)
							),
						),
						mahan_el_full()
					)
					->row(
						array(
							mahan_el(
								'countdown',
								array(
									'title'        => __( 'تا شروع همایش', 'mahan' ),
									'expired_text' => __( 'همایش برگزار شد؛ ویدیوها به‌زودی منتشر می‌شوند.', 'mahan' ),
								)
							),
						),
						mahan_el_padding( 56 )
					)
					->row(
						array(
							mahan_el(
								'event-list',
								array(
									'title'           => __( 'برنامهٔ روز نخست', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'سالن اصلی، با پخش زنده برای بلیت‌های آنلاین.', 'mahan' ),
									'events'          => array(
										array(
											'day'   => __( 'روز اول', 'mahan' ),
											'date'  => __( '۹:۰۰', 'mahan' ),
											'title' => __( 'افتتاحیه و سخنرانی کلیدی', 'mahan' ),
											'text'  => __( 'آیندهٔ رابط‌های کاربری در دههٔ پیش رو.', 'mahan' ),
											'meta'  => __( 'سالن اصلی', 'mahan' ),
										),
										array(
											'day'   => __( 'روز اول', 'mahan' ),
											'date'  => __( '۱۱:۰۰', 'mahan' ),
											'title' => __( 'کارگاه دسترس‌پذیری', 'mahan' ),
											'text'  => __( 'تمرین عملی روی یک سایت واقعی.', 'mahan' ),
											'meta'  => __( 'کارگاه ۲', 'mahan' ),
											'badge' => __( 'ظرفیت محدود', 'mahan' ),
										),
										array(
											'day'   => __( 'روز اول', 'mahan' ),
											'date'  => __( '۱۴:۰۰', 'mahan' ),
											'title' => __( 'میزگرد مدیران محصول', 'mahan' ),
											'text'  => __( 'از ایده تا انتشار، در تیم‌های کوچک.', 'mahan' ),
											'meta'  => __( 'سالن اصلی', 'mahan' ),
										),
										array(
											'day'   => __( 'روز اول', 'mahan' ),
											'date'  => __( '۱۶:۳۰', 'mahan' ),
											'title' => __( 'شبکه‌سازی و پذیرایی', 'mahan' ),
											'meta'  => __( 'محوطهٔ باز', 'mahan' ),
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
									'title'           => __( 'سخنرانان', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
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
									'title'           => __( 'بلیت‌ها', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'قیمت زودهنگام تا پایان همین هفته معتبر است.', 'mahan' ),
									'columns'         => '3',
									'plans'           => array(
										array(
											'name'        => __( 'آنلاین', 'mahan' ),
											'price'       => '۴۹۰,۰۰۰',
											'period'      => __( 'تومان', 'mahan' ),
											'features'    => __( "پخش زندهٔ سالن اصلی\nدسترسی به ویدیوها\nگواهی شرکت", 'mahan' ),
											'button_text' => __( 'خرید بلیت', 'mahan' ),
										),
										array(
											'name'        => __( 'حضوری', 'mahan' ),
											'price'       => '۱,۹۰۰,۰۰۰',
											'period'      => __( 'تومان', 'mahan' ),
											'features'    => __( "سه روز حضور کامل\nناهار و پذیرایی\nکارگاه‌ها\nبستهٔ همایش\nگواهی شرکت", 'mahan' ),
											'featured'    => 'yes',
											'badge'       => __( 'پرفروش', 'mahan' ),
											'button_text' => __( 'خرید بلیت', 'mahan' ),
										),
										array(
											'name'        => __( 'سازمانی', 'mahan' ),
											'price'       => '۸,۵۰۰,۰۰۰',
											'period'      => __( 'تومان', 'mahan' ),
											'features'    => __( "پنج بلیت حضوری\nمیز اختصاصی\nمعرفی در سایت همایش\nجلسهٔ خصوصی با سخنرانان", 'mahan' ),
											'button_text' => __( 'رزرو', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'images'          => $media->gallery( 'card', 6, 4 ),
									'title'           => __( 'دورهٔ گذشته', 'mahan' ),
									'title_highlight' => 2,
									'layout'          => 'mosaic',
									'columns'         => '3',
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
									'icon'        => 'calendar',
									'title'       => __( 'جای خود را رزرو کنید', 'mahan' ),
									'text'        => __( 'ظرفیت سالن محدود است و بلیت‌های زودهنگام زود تمام می‌شوند.', 'mahan' ),
									'button_text' => __( 'خرید بلیت', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'schedule'  => array(
			'title'    => __( 'برنامهٔ همایش', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'event-list',
								array(
									'title'           => __( 'روز دوم', 'mahan' ),
									'title_highlight' => 1,
									'events'          => array(
										array(
											'day'   => __( 'روز دوم', 'mahan' ),
											'date'  => __( '۹:۳۰', 'mahan' ),
											'title' => __( 'معماری سرویس‌های پرترافیک', 'mahan' ),
											'meta'  => __( 'سالن اصلی', 'mahan' ),
										),
										array(
											'day'   => __( 'روز دوم', 'mahan' ),
											'date'  => __( '۱۱:۳۰', 'mahan' ),
											'title' => __( 'کارگاه طراحی رابط فارسی', 'mahan' ),
											'meta'  => __( 'کارگاه ۱', 'mahan' ),
										),
										array(
											'day'   => __( 'روز دوم', 'mahan' ),
											'date'  => __( '۱۵:۰۰', 'mahan' ),
											'title' => __( 'ارائه‌های کوتاه استارتاپی', 'mahan' ),
											'meta'  => __( 'سالن دوم', 'mahan' ),
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
									'title'           => __( 'ساعات پذیرش', 'mahan' ),
									'title_highlight' => 2,
									'days'            => array(
										array(
											'day'   => __( 'روز اول', 'mahan' ),
											'hours' => __( '۸:۰۰ تا ۱۸:۰۰', 'mahan' ),
										),
										array(
											'day'   => __( 'روز دوم', 'mahan' ),
											'hours' => __( '۸:۳۰ تا ۱۸:۰۰', 'mahan' ),
										),
										array(
											'day'   => __( 'روز سوم', 'mahan' ),
											'hours' => __( '۸:۳۰ تا ۱۵:۰۰', 'mahan' ),
										),
									),
									'note'            => __( 'کارت ورود را همراه داشته باشید.', 'mahan' ),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'speakers'  => array(
			'title'    => __( 'سخنرانان', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'همهٔ سخنرانان', 'mahan' ),
									'title_highlight' => 2,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'tickets'   => array(
			'title'    => __( 'بلیت‌ها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'انتخاب بلیت', 'mahan' ),
									'title_highlight' => 2,
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
									'title'           => __( 'پرسش‌های بلیت', 'mahan' ),
									'title_highlight' => 2,
									'faqs'            => array(
										array(
											'question' => __( 'بلیت قابل انتقال است؟', 'mahan' ),
											'answer'   => __( 'بله، تا ۴۸ ساعت پیش از شروع می‌توانید نام شرکت‌کننده را عوض کنید.', 'mahan' ),
										),
										array(
											'question' => __( 'ویدیوها بعداً منتشر می‌شود؟', 'mahan' ),
											'answer'   => __( 'برای دارندگان بلیت آنلاین و حضوری، تا دو هفته پس از همایش.', 'mahan' ),
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
		'contact'   => array(
			'title'    => __( 'تماس و محل برگزاری', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'محل برگزاری', 'mahan' ),
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
		'blog'      => array( 'title' => __( 'اخبار همایش', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی همایش', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'schedule' => array(
					'title' => __( 'برنامه', 'mahan' ),
					'page'  => 'schedule',
					'icon'  => 'calendar',
				),
				'speakers' => array(
					'title' => __( 'سخنرانان', 'mahan' ),
					'page'  => 'speakers',
				),
				'tickets'  => array(
					'title' => __( 'بلیت', 'mahan' ),
					'page'  => 'tickets',
					'badge' => __( 'زودهنگام', 'mahan' ),
				),
				'contact'  => array(
					'title' => __( 'محل برگزاری', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'map-pin',
				),
			),
		),
	),

	'widgets' => array(),
);
