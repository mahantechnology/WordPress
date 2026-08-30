<?php
/**
 * Starter site: startup / SaaS app landing.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'اپلیکیشنی که کارهای روزانه‌تان را ساده می‌کند', 'mahan' ),

	'options' => array(
		'header_layout'      => 'minimal',
		'footer_layout'      => 'compact',
		'header_transparent' => true,
		'topbar_enabled'     => false,
		'header_cart'        => false,
		'header_wishlist'    => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'none',
		'radius'             => 22,
		'dark_mode'          => 'toggle',
		'section_spacing'    => 96,
		'footer_about_text'  => __( 'ما ابزارهایی می‌سازیم که وقت شما را آزاد می‌کنند.', 'mahan' ),
	),

	'pages' => array(
		'home'     => array(
			'title'    => __( 'صفحهٔ اصلی', 'mahan' ),
			'meta'     => array(
				'_mahan_layout'      => 'full',
				'_mahan_transparent' => '1',
			),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'hero-banner',
								array(
									'layout'          => 'split',
									'eyebrow'         => __( 'نسخهٔ ۳ منتشر شد', 'mahan' ),
									'title'           => __( 'کارهای تیم‌تان را در یک‌جا جمع کنید', 'mahan' ),
									'title_highlight' => 2,
									'description'     => __( 'وظیفه‌ها، یادداشت‌ها و گفتگوها؛ همه در یک اپلیکیشن سبک و سریع.', 'mahan' ),
									'primary_text'    => __( 'شروع رایگان', 'mahan' ),
									'secondary_text'  => __( 'مشاهدهٔ دمو', 'mahan' ),
									'stats'           => array(
										array(
											'number' => '۵۰۰۰۰',
											'suffix' => '+',
											'label'  => __( 'کاربر فعال', 'mahan' ),
										),
										array(
											'number' => '۴.۹',
											'suffix' => '/۵',
											'label'  => __( 'امتیاز کاربران', 'mahan' ),
										),
										array(
											'number' => '۹۹.۹',
											'suffix' => '٪',
											'label'  => __( 'پایداری سرویس', 'mahan' ),
										),
									),
								)
							),
						),
						mahan_el_padding( 120, 64 )
					)
					->row(
						array(
							mahan_el(
								'logo-carousel',
								array(
									'show_heading'   => '',
									'slides_to_show' => '6',
									'grayscale'      => 'yes',
								)
							),
						),
						mahan_el_padding( 24, 48 )
					)
					->row(
						array(
							mahan_el(
								'icon-box',
								array(
									'title'           => __( 'همه چیز در یک ابزار', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'دیگر لازم نیست میان پنج اپلیکیشن مختلف جابه‌جا شوید.', 'mahan' ),
									'style'           => 'card',
									'columns'         => '3',
									'items'           => array(
										array(
											'icon'  => 'lightning',
											'title' => __( 'سرعت بالا', 'mahan' ),
											'text'  => __( 'رابط کاربری سبک که روی هر دستگاهی روان کار می‌کند.', 'mahan' ),
										),
										array(
											'icon'  => 'layers',
											'title' => __( 'یکپارچگی', 'mahan' ),
											'text'  => __( 'اتصال به بیش از ۴۰ سرویس محبوب، تنها با چند کلیک.', 'mahan' ),
										),
										array(
											'icon'  => 'shield',
											'title' => __( 'امنیت داده', 'mahan' ),
											'text'  => __( 'رمزنگاری سرتاسری و پشتیبان‌گیری روزانه.', 'mahan' ),
										),
										array(
											'icon'  => 'chart',
											'title' => __( 'گزارش‌های زنده', 'mahan' ),
											'text'  => __( 'ببینید تیم شما دقیقاً کجای مسیر است.', 'mahan' ),
										),
										array(
											'icon'  => 'globe',
											'title' => __( 'کار از هر جا', 'mahan' ),
											'text'  => __( 'نسخهٔ وب، دسکتاپ و موبایل، همیشه هماهنگ.', 'mahan' ),
										),
										array(
											'icon'  => 'headphones',
											'title' => __( 'پشتیبانی فارسی', 'mahan' ),
											'text'  => __( 'تیم پشتیبانی همه‌روزه در دسترس شماست.', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'title'           => __( 'ساخته‌شده برای تیم‌های واقعی', 'mahan' ),
									'title_highlight' => 2,
									'heading_align'   => 'right',
									'media_position'  => 'left',
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'before-after',
								array(
									'before_label' => __( 'قبل از ماهان', 'mahan' ),
									'after_label'  => __( 'بعد از ماهان', 'mahan' ),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'پلن مناسب خود را انتخاب کنید', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'چهارده روز رایگان، بدون نیاز به کارت بانکی.', 'mahan' ),
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
									'title_highlight' => 1,
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
									'title'           => __( 'کاربران چه می‌گویند', 'mahan' ),
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
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های متداول', 'mahan' ),
									'title_highlight' => 1,
									'layout'          => 'two',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'cta-banner',
								array(
									'icon'           => 'lightning',
									'title'          => __( 'همین امروز رایگان شروع کنید', 'mahan' ),
									'text'           => __( 'در کمتر از دو دقیقه حساب بسازید و تیم‌تان را دعوت کنید.', 'mahan' ),
									'button_text'    => __( 'ساخت حساب رایگان', 'mahan' ),
									'secondary_text' => __( 'گفتگو با فروش', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'pricing'  => array(
			'title'    => __( 'تعرفه‌ها', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'pricing-table',
								array(
									'title'           => __( 'تعرفه‌های ماهان', 'mahan' ),
									'title_highlight' => 1,
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
									'title'           => __( 'جزئیات پلن‌ها', 'mahan' ),
									'title_highlight' => 1,
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های مالی', 'mahan' ),
									'title_highlight' => 1,
								)
							),
						)
					)
					->to_array();
			},
		),
		'features' => array(
			'title'    => __( 'ویژگی‌ها', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'icon-box',
								array(
									'title'           => __( 'همهٔ ویژگی‌ها', 'mahan' ),
									'title_highlight' => 1,
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
									'title'           => __( 'شروع در چهار گام', 'mahan' ),
									'title_highlight' => 2,
									'columns'         => '4',
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'  => array(
			'title'    => __( 'تماس', 'mahan' ),
			'sections' => static function () {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'با ما حرف بزنید', 'mahan' ),
									'title_highlight' => 2,
									'columns'         => '3',
								)
							),
						)
					)
					->to_array();
			},
		),
		'blog'     => array( 'title' => __( 'بلاگ', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی استارتاپ', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
				),
				'features' => array(
					'title' => __( 'ویژگی‌ها', 'mahan' ),
					'page'  => 'features',
				),
				'pricing'  => array(
					'title' => __( 'تعرفه‌ها', 'mahan' ),
					'page'  => 'pricing',
				),
				'blog'     => array(
					'title' => __( 'بلاگ', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'  => array(
					'title' => __( 'تماس', 'mahan' ),
					'page'  => 'contact',
				),
			),
		),
	),

	'widgets' => array(),
);
