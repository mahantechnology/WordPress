<?php
/**
 * Starter site: online shop.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'فروشگاه اینترنتی کالای اصل با ارسال سریع', 'mahan' ),

	'options' => array(
		'header_layout'         => 'shop',
		'footer_layout'         => 'shop',
		'topbar_enabled'        => true,
		'topbar_text'           => __( 'ارسال رایگان برای سفارش‌های بالای ۵۰۰ هزار تومان', 'mahan' ),
		'header_cart'           => true,
		'header_wishlist'       => true,
		'shop_columns'          => 4,
		'shop_card_style'       => 'modern',
		'shop_sidebar'          => 'right',
		'blog_layout'           => 'grid',
		'blog_columns'          => 3,
		'mobile_bottom_bar'     => true,
		'footer_badges'         => true,
		'footer_about_text'     => __( 'ما کالای اصل را با قیمت منصفانه و ارسال سریع به دست شما می‌رسانیم.', 'mahan' ),
	),

	'product_cats' => array(
		__( 'کالای دیجیتال', 'mahan' ),
		__( 'پوشاک', 'mahan' ),
		__( 'خانه و آشپزخانه', 'mahan' ),
		__( 'زیبایی و سلامت', 'mahan' ),
	),

	'products' => array(
		array(
			'title'      => __( 'گوشی هوشمند مدل آلفا پرو', 'mahan' ),
			'excerpt'    => __( 'نمایشگر ۶.۷ اینچی، دوربین سه‌گانه و باتری ۵۰۰۰ میلی‌آمپری.', 'mahan' ),
			'content'    => __( 'آلفا پرو با پردازندهٔ نسل جدید و شارژ سریع ۶۵ وات، انتخابی مطمئن برای استفادهٔ روزمره و حرفه‌ای است. بدنهٔ مقاوم و صفحهٔ ۱۲۰ هرتز، تجربهٔ روانی می‌سازد.', 'mahan' ),
			'price'      => 24900000,
			'sale_price' => 21900000,
			'stock'      => 7,
		),
		array(
			'title'   => __( 'لپ‌تاپ حرفه‌ای ۱۵ اینچ', 'mahan' ),
			'excerpt' => __( 'مناسب طراحی، برنامه‌نویسی و کارهای سنگین گرافیکی.', 'mahan' ),
			'content' => __( 'با ۱۶ گیگابایت حافظه و درایو NVMe یک ترابایتی، این لپ‌تاپ چند پروژهٔ سنگین را هم‌زمان اجرا می‌کند. صفحه‌کلید با نورپردازی و پنل رنگی دقیق.', 'mahan' ),
			'price'   => 68500000,
		),
		array(
			'title'      => __( 'هدفون بی‌سیم نویزکنسل', 'mahan' ),
			'excerpt'    => __( 'حذف فعال نویز و ۴۰ ساعت پخش مداوم.', 'mahan' ),
			'content'    => __( 'بالشتک‌های نرم و وزن سبک، استفادهٔ چندساعته را راحت می‌کند. با قاب شارژ همراه و اتصال هم‌زمان به دو دستگاه.', 'mahan' ),
			'price'      => 5200000,
			'sale_price' => 4390000,
		),
		array(
			'title'   => __( 'ساعت هوشمند ورزشی', 'mahan' ),
			'excerpt' => __( 'پایش ضربان، خواب و بیش از ۸۰ حالت ورزشی.', 'mahan' ),
			'content' => __( 'ضدآب تا ۵۰ متر، با باتری هفت‌روزه و نمایشگر آمولد همیشه‌روشن.', 'mahan' ),
			'price'   => 3850000,
			'stock'   => 4,
		),
		array(
			'title'   => __( 'کیف چرم دست‌دوز', 'mahan' ),
			'excerpt' => __( 'چرم طبیعی با دوخت دستی و ضمانت دو ساله.', 'mahan' ),
			'content' => __( 'هر کیف در کارگاه ما و به‌صورت تکی دوخته می‌شود؛ به همین دلیل رگه‌های چرم هر نمونه منحصربه‌فرد است.', 'mahan' ),
			'price'   => 2450000,
		),
		array(
			'title'      => __( 'پیراهن نخی مردانه', 'mahan' ),
			'excerpt'    => __( 'نخ ۱۰۰٪ پنبه، مناسب چهار فصل.', 'mahan' ),
			'content'    => __( 'برش راحت و یقهٔ کلاسیک، هم برای محیط کار و هم برای استفادهٔ روزمره مناسب است.', 'mahan' ),
			'price'      => 1290000,
			'sale_price' => 890000,
		),
		array(
			'title'   => __( 'کفش راحتی روزمره', 'mahan' ),
			'excerpt' => __( 'زیرهٔ فوم سبک و رویهٔ تنفس‌پذیر.', 'mahan' ),
			'content' => __( 'برای پیاده‌روی طولانی طراحی شده؛ وزن هر لنگه کمتر از ۳۰۰ گرم است.', 'mahan' ),
			'price'   => 1850000,
			'stock'   => 9,
		),
		array(
			'title'   => __( 'قهوه‌ساز خانگی', 'mahan' ),
			'excerpt' => __( 'دم‌آوری اسپرسو و قهوهٔ فیلتر در یک دستگاه.', 'mahan' ),
			'content' => __( 'مخزن یک‌ونیم لیتری، فشار ۱۵ بار و بخارساز شیر برای کاپوچینو.', 'mahan' ),
			'price'   => 8900000,
		),
		array(
			'title'      => __( 'چراغ مطالعهٔ LED', 'mahan' ),
			'excerpt'    => __( 'سه حالت نور و بازوی متحرک.', 'mahan' ),
			'content'    => __( 'نور بدون سوسو با دمای رنگ قابل تنظیم، مناسب مطالعهٔ طولانی.', 'mahan' ),
			'price'      => 780000,
			'sale_price' => 620000,
		),
		array(
			'title'   => __( 'کوله‌پشتی ضدآب', 'mahan' ),
			'excerpt' => __( 'جای لپ‌تاپ ۱۶ اینچ و پارچهٔ ضدآب.', 'mahan' ),
			'content' => __( 'بندهای ارگونومیک و پنل پشتی مش‌دار، حمل بار سنگین را راحت‌تر می‌کند.', 'mahan' ),
			'price'   => 1650000,
		),
		array(
			'title'   => __( 'اسپیکر قابل حمل', 'mahan' ),
			'excerpt' => __( 'صدای ۳۶۰ درجه با ۱۲ ساعت پخش.', 'mahan' ),
			'content' => __( 'ضدآب IPX7، مناسب سفر و فضای باز. امکان اتصال دو اسپیکر برای پخش استریو.', 'mahan' ),
			'price'   => 2980000,
			'stock'   => 5,
		),
		array(
			'title'   => __( 'ماگ سرامیکی دست‌ساز', 'mahan' ),
			'excerpt' => __( 'لعاب بدون سرب، مناسب ماشین ظرفشویی.', 'mahan' ),
			'content' => __( 'هر ماگ روی چرخ سفالگری شکل می‌گیرد، پس اندازه و رنگ هر نمونه کمی متفاوت است.', 'mahan' ),
			'price'   => 480000,
		),
	),

	'services' => array(
		array(
			'title'   => __( 'ارسال سریع سراسر کشور', 'mahan' ),
			'excerpt' => __( 'سفارش‌های تهران در همان روز و شهرستان‌ها ۲۴ تا ۷۲ ساعته تحویل داده می‌شوند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'truck' ),
		),
		array(
			'title'   => __( 'ضمانت اصالت کالا', 'mahan' ),
			'excerpt' => __( 'همهٔ کالاها دارای گارانتی معتبر و ضمانت بازگشت هفت‌روزه هستند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'shield' ),
		),
		array(
			'title'   => __( 'پرداخت امن', 'mahan' ),
			'excerpt' => __( 'پرداخت از طریق درگاه‌های معتبر بانکی و امکان پرداخت در محل.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'check-circle' ),
		),
		array(
			'title'   => __( 'پشتیبانی همه‌روزه', 'mahan' ),
			'excerpt' => __( 'کارشناسان ما هر روز هفته پاسخگوی پرسش‌های شما هستند.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'headphones' ),
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
								'hero-slider',
								array(
									'slides' => array(
										array(
											'image' => $media->wide( 0 ),
											'eyebrow'     => __( 'فروش ویژهٔ هفته', 'mahan' ),
											'title'       => __( 'تا ۴۰٪ تخفیف روی کالای دیجیتال', 'mahan' ),
											'text'        => __( 'گوشی، لپ‌تاپ و لوازم جانبی با بهترین قیمت بازار.', 'mahan' ),
											'button_text' => __( 'مشاهدهٔ محصولات', 'mahan' ),
											'align'       => 'right',
										),
										array(
											'image' => $media->wide( 1 ),
											'eyebrow'     => __( 'تازه‌رسیده‌ها', 'mahan' ),
											'title'       => __( 'کالکشن جدید پاییز', 'mahan' ),
											'text'        => __( 'محصولات تازه هر هفته به فروشگاه اضافه می‌شوند.', 'mahan' ),
											'button_text' => __( 'همین حالا ببینید', 'mahan' ),
											'align'       => 'right',
										),
									),
									'autoplay' => 'yes',
								)
							),
						),
						array(
							'padding' => array(
								'unit'     => 'px',
								'top'      => '0',
								'right'    => '0',
								'bottom'   => '0',
								'left'     => '0',
								'isLinked' => true,
							),
						)
					)
					->row(
						array(
							mahan_el(
								'icon-box',
								array(
									'show_heading' => '',
									'style'        => 'inline',
									'columns'      => '4',
									'items'        => array(
										array(
											'icon'  => 'truck',
											'title' => __( 'ارسال سریع', 'mahan' ),
											'text'  => __( 'تحویل ۲۴ تا ۷۲ ساعته', 'mahan' ),
										),
										array(
											'icon'  => 'shield',
											'title' => __( 'ضمانت اصالت', 'mahan' ),
											'text'  => __( 'کالای کاملاً اورجینال', 'mahan' ),
										),
										array(
											'icon'  => 'refresh',
											'title' => __( 'بازگشت کالا', 'mahan' ),
											'text'  => __( 'تا هفت روز پس از خرید', 'mahan' ),
										),
										array(
											'icon'  => 'headphones',
											'title' => __( 'پشتیبانی', 'mahan' ),
											'text'  => __( 'همه‌روزه، ۹ تا ۲۱', 'mahan' ),
										),
									),
								)
							),
						),
						array(
							'padding' => array(
								'unit'     => 'px',
								'top'      => '32',
								'right'    => '0',
								'bottom'   => '32',
								'left'     => '0',
								'isLinked' => false,
							),
						)
					)
					->row(
						array(
							mahan_el(
								'product-categories',
								array(
									'title'           => __( 'خرید بر اساس دسته‌بندی', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'دسته‌بندی مورد نظرتان را انتخاب کنید و سریع‌تر به کالای دلخواه برسید.', 'mahan' ),
									'count'           => 6,
									'style'           => 'circle',
									'columns'         => '6',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'product-deal',
								array(
									'title' => __( 'پیشنهاد شگفت‌انگیز', 'mahan' ),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'product-tabs',
								array(
									'title'           => __( 'پرفروش‌ترین محصولات', 'mahan' ),
									'title_highlight' => 1,
									'per_tab'         => 8,
									'columns'         => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'product-banner',
								array(
									'columns' => '3',
									'banners' => array(
										array(
											'eyebrow'     => __( 'تا ۳۰٪ تخفیف', 'mahan' ),
											'title'       => __( 'لوازم جانبی', 'mahan' ),
											'button_text' => __( 'خرید کنید', 'mahan' ),
										),
										array(
											'eyebrow'     => __( 'تازه رسید', 'mahan' ),
											'title'       => __( 'پوشاک پاییزی', 'mahan' ),
											'button_text' => __( 'مشاهده', 'mahan' ),
										),
										array(
											'eyebrow'     => __( 'ارسال رایگان', 'mahan' ),
											'title'       => __( 'خانه و آشپزخانه', 'mahan' ),
											'button_text' => __( 'خرید کنید', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'product-carousel',
								array(
									'title'           => __( 'محصولات حراج', 'mahan' ),
									'title_highlight' => 1,
									'filter'          => 'on_sale',
									'slides_to_show'  => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'testimonial-carousel',
								array(
									'title'           => __( 'مشتریان دربارهٔ ما', 'mahan' ),
									'title_highlight' => 1,
									'source'          => 'cpt',
									'slides_to_show'  => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'post-grid',
								array(
									'title'           => __( 'از وبلاگ ما', 'mahan' ),
									'title_highlight' => 1,
									'posts_per_page'  => 3,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'newsletter-form',
								array(
									'style' => 'boxed',
									'title' => __( 'از تخفیف‌ها باخبر شوید', 'mahan' ),
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
									'image' => $media->card( 0 ),
									'title'           => __( 'دربارهٔ فروشگاه ما', 'mahan' ),
									'title_highlight' => 1,
									'heading_align'   => 'right',
									'subtitle'        => __( 'از سال ۱۳۹۸ در کنار شما هستیم تا خرید اینترنتی را ساده و مطمئن کنیم.', 'mahan' ),
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
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact' => array(
			'title'    => __( 'تماس با ما', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'راه‌های ارتباط با ما', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el( 'map-embed' ),
						)
					)
					->to_array();
			},
		),
		'blog'    => array( 'title' => __( 'مجله', 'mahan' ) ),
		'faq'     => array(
			'title'    => __( 'پرسش‌های متداول', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های پرتکرار مشتریان', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'two',
								)
							),
						)
					)
					->to_array();
			},
		),
	),

	'menus' => array(
		'primary'   => array(
			'name'  => __( 'منوی اصلی فروشگاه', 'mahan' ),
			'items' => array(
				'home'  => array(
					'title' => __( 'صفحهٔ اصلی', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'shop'  => array(
					'title'   => __( 'فروشگاه', 'mahan' ),
					'wc_page' => 'shop',
					'url'     => home_url( '/shop/' ),
					'icon'    => 'cart',
					'mega'    => true,
				),
				'blog'  => array(
					'title' => __( 'مجله', 'mahan' ),
					'page'  => 'blog',
					'icon'  => 'book',
				),
				'about' => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'faq'   => array(
					'title' => __( 'پرسش‌های متداول', 'mahan' ),
					'page'  => 'faq',
				),
				'contact' => array(
					'title' => __( 'تماس با ما', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'phone',
				),
			),
		),
		'secondary' => array(
			'name'  => __( 'منوی بالای فروشگاه', 'mahan' ),
			'items' => array(
				'track'  => array(
					'title'       => __( 'پیگیری سفارش', 'mahan' ),
					'wc_endpoint' => 'orders',
					'url'         => home_url( '/my-account/orders/' ),
				),
				'help'   => array(
					'title' => __( 'راهنمای خرید', 'mahan' ),
					'page'  => 'faq',
				),
			),
		),
	),

	'widgets' => array(
		'sidebar-blog' => array(
			array(
				'type'     => 'search',
				'instance' => array( 'title' => __( 'جستجو', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'پربازدیدترین‌ها', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'views',
					'thumbnail' => true,
				),
			),
			array(
				'type'     => 'mahan_tags',
				'instance' => array(
					'title' => __( 'برچسب‌های داغ', 'mahan' ),
					'count' => 15,
				),
			),
		),
		'footer-1'     => array(
			array(
				'type'     => 'mahan_contact',
				'instance' => array( 'title' => __( 'تماس با ما', 'mahan' ) ),
			),
		),
	),
);
