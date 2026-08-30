<?php
/**
 * Starter site: travel agency / tour operator.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'سفر، از همین‌جا شروع می‌شود', 'mahan' ),

	'options' => array(
		'header_layout'      => 'classic',
		'footer_layout'      => 'columns',
		'header_transparent' => true,
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'مشاورهٔ رایگان تور: ۰۲۱-۱۲۳۴۵۶۷۸', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'right',
		'radius'             => 20,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'برگزارکنندهٔ تورهای داخلی و خارجی با بیش از یک دهه تجربه و مجوز رسمی گردشگری.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'تور کیش', 'mahan' ),
			'excerpt' => __( 'سه شب و چهار روز، پرواز مستقیم، اقامت در هتل چهارستاره.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sun' ),
		),
		array(
			'title'   => __( 'تور استانبول', 'mahan' ),
			'excerpt' => __( 'گشت شهری، بازار بزرگ و بسفر؛ با راهنمای فارسی‌زبان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'globe' ),
		),
		array(
			'title'   => __( 'تور شمال', 'mahan' ),
			'excerpt' => __( 'جنگل، دریا و جادهٔ چالوس در یک آخر هفتهٔ کامل.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'map-pin' ),
		),
		array(
			'title'   => __( 'تور کویر', 'mahan' ),
			'excerpt' => __( 'شب‌مانی زیر آسمان پرستاره با کاروان شتر و موسیقی محلی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'moon' ),
		),
		array(
			'title'   => __( 'تور دبی', 'mahan' ),
			'excerpt' => __( 'خرید، سافاری صحرا و برج خلیفه؛ ویزا و بیمه همراه تور.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'building' ),
		),
		array(
			'title'   => __( 'تور اروپا', 'mahan' ),
			'excerpt' => __( 'ترکیبی از سه کشور در دوازده روز، با پرواز و ترانسفر.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'truck' ),
		),
	),

	'portfolio' => array(
		array(
			'title'   => __( 'اصفهان، نصف جهان', 'mahan' ),
			'excerpt' => __( 'میدان نقش جهان، سی‌وسه‌پل و بازار قیصریه در دو روز.', 'mahan' ),
		),
		array(
			'title'   => __( 'شیراز، شهر شعر', 'mahan' ),
			'excerpt' => __( 'حافظیه، تخت جمشید و باغ ارم در فصل بهار نارنج.', 'mahan' ),
		),
		array(
			'title'   => __( 'قشم و هرمز', 'mahan' ),
			'excerpt' => __( 'جزیرهٔ رنگین‌کمانی، درهٔ ستارگان و جنگل حرا.', 'mahan' ),
		),
		array(
			'title'   => __( 'ماسوله و فومن', 'mahan' ),
			'excerpt' => __( 'پله‌های مه‌گرفته و بام‌هایی که حیاط خانهٔ بالایی‌اند.', 'mahan' ),
		),
		array(
			'title'   => __( 'ارمنستان', 'mahan' ),
			'excerpt' => __( 'ایروان، دریاچهٔ سوان و صومعه‌های تاریخی.', 'mahan' ),
		),
		array(
			'title'   => __( 'گرجستان', 'mahan' ),
			'excerpt' => __( 'تفلیس، باتومی و جاده‌های کوهستانی قفقاز.', 'mahan' ),
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
									'image'           => $media->wide( 2 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'آژانس گردشگری ماهان', 'mahan' ),
									'title'           => __( 'سفر بعدی‌تان را ما می‌چینیم', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'تورهای داخلی و خارجی با پرواز، اقامت، بیمه و راهنمای همراه؛ همه در یک بسته.', 'mahan' ),
									'primary_text'    => __( 'مشاهدهٔ تورها', 'mahan' ),
									'secondary_text'  => __( 'مشاورهٔ رایگان', 'mahan' ),
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
									'style'        => 'card',
									'columns'      => '4',
									'items'        => array(
										array(
											'icon'  => 'shield',
											'title' => __( 'مجوز رسمی', 'mahan' ),
											'text'  => __( 'دارای بند «ب» از سازمان میراث فرهنگی.', 'mahan' ),
										),
										array(
											'icon'  => 'headphones',
											'title' => __( 'همراهی در سفر', 'mahan' ),
											'text'  => __( 'پشتیبانی شبانه‌روزی در تمام مدت تور.', 'mahan' ),
										),
										array(
											'icon'  => 'gift',
											'title' => __( 'قیمت شفاف', 'mahan' ),
											'text'  => __( 'بدون هزینهٔ پنهان؛ هرچه می‌بینید همان است.', 'mahan' ),
										),
										array(
											'icon'  => 'refresh',
											'title' => __( 'کنسلی منعطف', 'mahan' ),
											'text'  => __( 'تا ۷۲ ساعت پیش از حرکت، بدون جریمه.', 'mahan' ),
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
									'title'           => __( 'تورهای پیش رو', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'ظرفیت محدود؛ برای رزرو زودتر اقدام کنید.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'مقصدهای محبوب', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'جاهایی که مسافران ما بیشتر از همه دوستشان داشته‌اند.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
									'style'           => 'overlay',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'countdown',
								array(
									'title'        => __( 'تخفیف ویژهٔ تور نوروزی', 'mahan' ),
									'expired_text' => __( 'مهلت ثبت‌نام این تور به پایان رسید.', 'mahan' ),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'gallery-grid',
								array(
									'images'          => $media->gallery( 'card', 8, 4 ),
									'title'           => __( 'قاب‌هایی از سفرها', 'mahan' ),
									'title_highlight' => 2,
									'layout'          => 'mosaic',
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
									'title'           => __( 'مسافران چه می‌گویند', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'card_style'      => 'bubble',
									'slides_to_show'  => '3',
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
									'title'       => __( 'هنوز مقصدتان را انتخاب نکرده‌اید؟', 'mahan' ),
									'text'        => __( 'شماره‌تان را بگذارید تا کارشناسان ما با شما تماس بگیرند.', 'mahan' ),
									'button_text' => __( 'درخواست مشاوره', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'tours'   => array(
			'title'    => __( 'تورها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'همهٔ تورها', 'mahan' ),
									'title_highlight' => 1,
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
									'title'           => __( 'پیش از رزرو بخوانید', 'mahan' ),
									'title_highlight' => 2,
									'faqs'           => array(
										array(
											'question' => __( 'هزینهٔ تور شامل چه چیزهایی است؟', 'mahan' ),
											'answer'   => __( 'بلیت رفت و برگشت، اقامت، صبحانه، بیمهٔ مسافرتی، ترانسفر فرودگاهی و راهنمای همراه.', 'mahan' ),
										),
										array(
											'question' => __( 'برای تورهای خارجی ویزا لازم است؟', 'mahan' ),
											'answer'   => __( 'برای مقصدهای بدون ویزا خیر. در بقیهٔ موارد، مدارک را می‌گیریم و کار سفارت را خودمان انجام می‌دهیم.', 'mahan' ),
										),
										array(
											'question' => __( 'امکان پرداخت اقساطی هست؟', 'mahan' ),
											'answer'   => __( 'بله، با پرداخت ۵۰٪ مبلغ هنگام رزرو و تسویه تا یک هفته پیش از حرکت.', 'mahan' ),
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
			'title'    => __( 'دربارهٔ ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 3 ),
									'title'           => __( 'ده سال همسفری', 'mahan' ),
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
								'stats-counter',
								array(
									'show_heading' => '',
									'columns'      => '4',
									'counters'     => array(
										array(
											'value'  => 12,
											'label'  => __( 'سال فعالیت', 'mahan' ),
											'icon'   => 'calendar',
										),
										array(
											'value'  => 480,
											'suffix' => '+',
											'label'  => __( 'تور برگزارشده', 'mahan' ),
											'icon'   => 'globe',
										),
										array(
											'value'  => 9600,
											'suffix' => '+',
											'label'  => __( 'مسافر همراه', 'mahan' ),
											'icon'   => 'user',
										),
										array(
											'value'  => 32,
											'label'  => __( 'مقصد داخلی و خارجی', 'mahan' ),
											'icon'   => 'map-pin',
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
		'contact' => array(
			'title'    => __( 'رزرو و تماس', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'راه‌های ارتباطی', 'mahan' ),
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
		'blog'    => array( 'title' => __( 'سفرنامه‌ها', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی گردشگری', 'mahan' ),
			'items' => array(
				'home'    => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'tours'   => array(
					'title' => __( 'تورها', 'mahan' ),
					'page'  => 'tours',
					'icon'  => 'globe',
					'mega'  => true,
				),
				'about'   => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'blog'    => array(
					'title' => __( 'سفرنامه‌ها', 'mahan' ),
					'page'  => 'blog',
				),
				'contact' => array(
					'title' => __( 'رزرو تور', 'mahan' ),
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
				'instance' => array( 'title' => __( 'جستجوی سفرنامه', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'سفرنامه‌های تازه', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'date',
					'thumbnail' => true,
				),
			),
		),
	),
);
