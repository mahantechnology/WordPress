<?php
/**
 * Starter site: cloud software / SaaS product.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'کارها را ساده‌تر کنید', 'mahan' ),

	'options' => array(
		'header_layout'      => 'glass',
		'footer_layout'      => 'cta',
		'header_transparent' => true,
		'header_cta_text'    => __( 'شروع رایگان', 'mahan' ),
		'topbar_enabled'     => false,
		'header_cart'        => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'none',
		'radius'             => 18,
		'dark_mode'          => 'auto',
		'footer_about_text'  => __( 'سرویس ابری مدیریت کارها و تیم‌ها، ساخته‌شده برای کسب‌وکارهای ایرانی.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'مدیریت وظایف', 'mahan' ),
			'excerpt' => __( 'تخته، فهرست و تقویم؛ هرطور که تیم شما کار می‌کند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'grid' ),
		),
		array(
			'title'   => __( 'گزارش‌های زنده', 'mahan' ),
			'excerpt' => __( 'پیشرفت پروژه و بار کاری هر نفر، در یک نگاه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'chart' ),
		),
		array(
			'title'   => __( 'اتوماسیون', 'mahan' ),
			'excerpt' => __( 'قانون بسازید تا کارهای تکراری خودشان انجام شوند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lightning' ),
		),
		array(
			'title'   => __( 'یکپارچگی', 'mahan' ),
			'excerpt' => __( 'اتصال به ایمیل، تقویم و ابزارهای روزمرهٔ تیم.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'refresh' ),
		),
		array(
			'title'   => __( 'کنترل دسترسی', 'mahan' ),
			'excerpt' => __( 'نقش‌ها و مجوزها، دقیق و قابل ممیزی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lock' ),
		),
		array(
			'title'   => __( 'پشتیبان‌گیری خودکار', 'mahan' ),
			'excerpt' => __( 'نسخهٔ پشتیبان روزانه روی سرورهای داخل کشور.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'shield' ),
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
									'image'           => $media->wide( 2 ),
									'layout'          => 'split',
									'eyebrow'         => __( 'نرم‌افزار ابری ماهان', 'mahan' ),
									'title'           => __( 'کارهای تیم را یک‌جا جمع کنید', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'وظایف، پروژه‌ها و گزارش‌ها در یک فضای مشترک؛ بدون نصب، بدون دردسر.', 'mahan' ),
									'primary_text'    => __( 'شروع رایگان ۱۴ روزه', 'mahan' ),
									'secondary_text'  => __( 'دیدن دمو', 'mahan' ),
									'stats'           => array(
										array( 'number' => __( '۴,۲۰۰+', 'mahan' ), 'label' => __( 'تیم فعال', 'mahan' ) ),
										array( 'number' => __( '۹۹٫۹٪', 'mahan' ), 'label' => __( 'در دسترس بودن', 'mahan' ) ),
										array( 'number' => __( '۱۴', 'mahan' ), 'label' => __( 'روز رایگان', 'mahan' ) ),
									),
								)
							),
						),
						mahan_el_full()
					)
					->row(
						array(
							mahan_el(
								'logo-carousel',
								array(
									'logos'           => array(
										array( 'logo' => $media->logo( 0 ), 'name' => __( 'مشتری ۱', 'mahan' ) ),
										array( 'logo' => $media->logo( 1 ), 'name' => __( 'مشتری ۲', 'mahan' ) ),
										array( 'logo' => $media->logo( 2 ), 'name' => __( 'مشتری ۳', 'mahan' ) ),
										array( 'logo' => $media->logo( 3 ), 'name' => __( 'مشتری ۴', 'mahan' ) ),
										array( 'logo' => $media->logo( 4 ), 'name' => __( 'مشتری ۵', 'mahan' ) ),
										array( 'logo' => $media->logo( 5 ), 'name' => __( 'مشتری ۶', 'mahan' ) ),
									),
									'title'           => __( 'مورد اعتماد تیم‌ها', 'mahan' ),
									'title_highlight' => 2,
									'grayscale'       => 'yes',
								)
							),
						),
						mahan_el_padding( 48 )
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'امکانات', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'هرچه برای اداره‌کردن کار روزمره لازم دارید.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'image-hotspots',
								array(
									'image'           => $media->wide( 3 ),
									'title'           => __( 'یک نگاه به محیط نرم‌افزار', 'mahan' ),
									'title_highlight' => 3,
									'spots'           => array(
										array(
											'title' => __( 'تختهٔ کارها', 'mahan' ),
											'text'  => __( 'وظایف را با کشیدن جابه‌جا کنید.', 'mahan' ),
											'x'     => 30,
											'y'     => 36,
										),
										array(
											'title' => __( 'نوار پیشرفت', 'mahan' ),
											'text'  => __( 'درصد انجام هر پروژه، زنده.', 'mahan' ),
											'x'     => 62,
											'y'     => 58,
										),
										array(
											'title' => __( 'اعلان‌ها', 'mahan' ),
											'text'  => __( 'فقط چیزی که به شما مربوط است.', 'mahan' ),
											'x'     => 80,
											'y'     => 24,
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
								'counter-circle',
								array(
									'title'           => __( 'در عمل چه اتفاقی می‌افتد؟', 'mahan' ),
									'title_highlight' => 2,
									'columns'         => '3',
									'rings'           => array(
										array(
											'value' => 42,
											'label' => __( 'کاهش زمان جلسه‌ها', 'mahan' ),
										),
										array(
											'value' => 87,
											'label' => __( 'تحویل به‌موقع پروژه', 'mahan' ),
										),
										array(
											'value' => 95,
											'label' => __( 'رضایت کاربران', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'پلن‌های اشتراک', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'چهارده روز رایگان، بدون نیاز به کارت بانکی.', 'mahan' ),
									'columns'         => '3',
									'plans'           => array(
										array(
											'name'        => __( 'شخصی', 'mahan' ),
											'price'       => '۰',
											'period'      => __( 'تومان / ماه', 'mahan' ),
											'features'    => __( "تا سه پروژه\nیک کاربر\nپشتیبانی ایمیلی", 'mahan' ),
											'button_text' => __( 'شروع کنید', 'mahan' ),
										),
										array(
											'name'        => __( 'تیمی', 'mahan' ),
											'price'       => '۴۹۰,۰۰۰',
											'period'      => __( 'تومان / ماه', 'mahan' ),
											'features'    => __( "پروژهٔ نامحدود\nتا ۱۵ کاربر\nگزارش‌های پیشرفته\nاتوماسیون\nپشتیبانی تلفنی", 'mahan' ),
											'featured'    => 'yes',
											'badge'       => __( 'پیشنهاد ما', 'mahan' ),
											'button_text' => __( 'شروع رایگان', 'mahan' ),
										),
										array(
											'name'        => __( 'سازمانی', 'mahan' ),
											'price'       => __( 'تماس بگیرید', 'mahan' ),
											'period'      => '',
											'features'    => __( "کاربر نامحدود\nنصب روی سرور شما\nورود یکپارچه (SSO)\nمدیر حساب اختصاصی", 'mahan' ),
											'button_text' => __( 'درخواست مشاوره', 'mahan' ),
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
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های متداول', 'mahan' ),
									'title_highlight' => 2,
									'faqs'            => array(
										array(
											'question' => __( 'داده‌های ما کجا نگهداری می‌شود؟', 'mahan' ),
											'answer'   => __( 'روی سرورهای داخل کشور، با پشتیبان‌گیری روزانه و رمزنگاری در حالت سکون.', 'mahan' ),
										),
										array(
											'question' => __( 'می‌توانیم پلن را وسط ماه عوض کنیم؟', 'mahan' ),
											'answer'   => __( 'بله. تفاوت مبلغ به‌صورت نسبی محاسبه و به صورتحساب بعدی منتقل می‌شود.', 'mahan' ),
										),
										array(
											'question' => __( 'امکان انتقال داده از ابزار قبلی هست؟', 'mahan' ),
											'answer'   => __( 'بله، فایل CSV و چند سرویس رایج پشتیبانی می‌شوند و تیم ما در انتقال کمک می‌کند.', 'mahan' ),
										),
										array(
											'question' => __( 'اگر منصرف شویم چه می‌شود؟', 'mahan' ),
											'answer'   => __( 'هر زمان می‌توانید اشتراک را لغو کنید و خروجی کامل داده‌ها را بردارید.', 'mahan' ),
										),
									),
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
									'title'       => __( 'همین امروز شروع کنید', 'mahan' ),
									'text'        => __( 'چهارده روز رایگان؛ اگر نپسندیدید، هیچ هزینه‌ای ندارد.', 'mahan' ),
									'button_text' => __( 'ساخت حساب رایگان', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'features' => array(
			'title'    => __( 'امکانات', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'همهٔ امکانات', 'mahan' ),
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
								'compare-table',
								array(
									'title'           => __( 'مقایسهٔ پلن‌ها', 'mahan' ),
									'title_highlight' => 2,
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'pricing'  => array(
			'title'    => __( 'تعرفه', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'پلن مناسب خود را انتخاب کنید', 'mahan' ),
									'title_highlight' => 3,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'trust-badges',
								array(
									'show_heading' => '',
									'style'        => 'plain',
									'columns'      => '4',
									'badges'       => array(
										array(
											'icon'  => 'shield',
											'title' => __( 'داده در ایران', 'mahan' ),
											'text'  => __( 'سرورهای داخلی', 'mahan' ),
										),
										array(
											'icon'  => 'lock',
											'title' => __( 'رمزنگاری', 'mahan' ),
											'text'  => __( 'در انتقال و سکون', 'mahan' ),
										),
										array(
											'icon'  => 'refresh',
											'title' => __( 'پشتیبان روزانه', 'mahan' ),
											'text'  => __( 'با نگهداری ۳۰ روزه', 'mahan' ),
										),
										array(
											'icon'  => 'headphones',
											'title' => __( 'پشتیبانی', 'mahan' ),
											'text'  => __( 'شنبه تا پنجشنبه', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'  => array(
			'title'    => __( 'تماس با ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'با ما حرف بزنید', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
								)
							),
						)
					)
					->to_array();
			},
		),
		'blog'     => array( 'title' => __( 'وبلاگ محصول', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی نرم‌افزار', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'features' => array(
					'title' => __( 'امکانات', 'mahan' ),
					'page'  => 'features',
					'icon'  => 'grid',
					'mega'  => true,
				),
				'pricing'  => array(
					'title' => __( 'تعرفه', 'mahan' ),
					'page'  => 'pricing',
				),
				'blog'     => array(
					'title' => __( 'وبلاگ', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'  => array(
					'title' => __( 'تماس', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'phone',
				),
			),
		),
	),

	'widgets' => array(),
);
