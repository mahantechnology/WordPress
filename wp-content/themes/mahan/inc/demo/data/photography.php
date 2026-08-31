<?php
/**
 * Starter site: photography studio.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'لحظه‌ها را نگه می‌داریم', 'mahan' ),

	'options' => array(
		'header_layout'      => 'glass',
		'footer_layout'      => 'minimal',
		'header_transparent' => true,
		'header_cta_text'    => __( 'رزرو جلسه', 'mahan' ),
		'topbar_enabled'     => false,
		'header_cart'        => false,
		'blog_layout'        => 'masonry',
		'blog_sidebar'       => 'none',
		'radius'             => 4,
		'dark_mode'          => 'auto',
		'footer_about_text'  => __( 'استودیوی عکاسی ماهان؛ پرتره، مراسم، محصول و عکاسی صنعتی.', 'mahan' ),
	),

	'portfolio' => array(
		array(
			'title'   => __( 'پرترهٔ استودیویی', 'mahan' ),
			'excerpt' => __( 'نور نرم، پس‌زمینهٔ ساده و تمرکز روی چهره.', 'mahan' ),
		),
		array(
			'title'   => __( 'عکاسی مراسم', 'mahan' ),
			'excerpt' => __( 'روایت یک روز کامل، بدون کارگردانی.', 'mahan' ),
		),
		array(
			'title'   => __( 'عکاسی محصول', 'mahan' ),
			'excerpt' => __( 'برای فروشگاه اینترنتی و کاتالوگ چاپی.', 'mahan' ),
		),
		array(
			'title'   => __( 'معماری و فضای داخلی', 'mahan' ),
			'excerpt' => __( 'با تصحیح پرسپکتیو و نورپردازی ترکیبی.', 'mahan' ),
		),
		array(
			'title'   => __( 'عکاسی صنعتی', 'mahan' ),
			'excerpt' => __( 'خط تولید، تجهیزات و گزارش تصویری کارخانه.', 'mahan' ),
		),
		array(
			'title'   => __( 'عکاسی خیابانی', 'mahan' ),
			'excerpt' => __( 'پروژهٔ شخصی استودیو، در شهرهای مختلف.', 'mahan' ),
		),
	),

	'services' => array(
		array(
			'title'   => __( 'بستهٔ پرتره', 'mahan' ),
			'excerpt' => __( 'یک ساعت عکاسی، ده فایل ویرایش‌شده.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'camera' ),
		),
		array(
			'title'   => __( 'بستهٔ مراسم', 'mahan' ),
			'excerpt' => __( 'حضور تمام‌روز با دو عکاس و آلبوم چاپی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'heart' ),
		),
		array(
			'title'   => __( 'بستهٔ محصول', 'mahan' ),
			'excerpt' => __( 'تا سی قلم کالا، با پس‌زمینهٔ سفید و محیطی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'cart' ),
		),
		array(
			'title'   => __( 'اجارهٔ استودیو', 'mahan' ),
			'excerpt' => __( 'فضای ۸۰ متری با نور دائم و فلاش.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'building' ),
		),
		array(
			'title'   => __( 'ویرایش و رتوش', 'mahan' ),
			'excerpt' => __( 'رتوش حرفه‌ای روی عکس‌های خودتان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'sparkles' ),
		),
		array(
			'title'   => __( 'کارگاه عکاسی', 'mahan' ),
			'excerpt' => __( 'دوره‌های مقدماتی و پیشرفتهٔ آخر هفته.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'graduation' ),
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
								'hero-slider',
								array(
									'slides' => array(
										array(
											'image'       => $media->wide( 0 ),
											'eyebrow'     => __( 'استودیوی ماهان', 'mahan' ),
											'title'       => __( 'لحظه‌ها را نگه می‌داریم', 'mahan' ),
											'text'        => __( 'پرتره، مراسم، محصول و صنعتی؛ با نگاهی ساده و صادق.', 'mahan' ),
											'button_text' => __( 'دیدن نمونه‌کارها', 'mahan' ),
											'align'       => 'center',
										),
										array(
											'image'       => $media->wide( 3 ),
											'eyebrow'     => __( 'رزرو جلسه', 'mahan' ),
											'title'       => __( 'قاب بعدی، مال شما', 'mahan' ),
											'text'        => __( 'وقت استودیو را آنلاین رزرو کنید.', 'mahan' ),
											'button_text' => __( 'رزرو', 'mahan' ),
											'align'       => 'center',
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
								'portfolio-grid',
								array(
									'title'           => __( 'نمونه‌کارها', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'گزیده‌ای از کارهای اخیر استودیو.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
									'style'           => 'overlay',
								)
							),
						),
						mahan_el_padding( 64 )
					)
					->row(
						array(
							mahan_el(
								'image-accordion',
								array(
									'title'           => __( 'دسته‌بندی‌ها', 'mahan' ),
									'title_highlight' => 1,
									'panels'          => array(
										array(
											'image' => $media->portrait( 0 ),
											'title' => __( 'پرتره', 'mahan' ),
										),
										array(
											'image' => $media->portrait( 1 ),
											'title' => __( 'مراسم', 'mahan' ),
										),
										array(
											'image' => $media->portrait( 2 ),
											'title' => __( 'محصول', 'mahan' ),
										),
										array(
											'image' => $media->portrait( 3 ),
											'title' => __( 'معماری', 'mahan' ),
										),
										array(
											'image' => $media->portrait( 4 ),
											'title' => __( 'صنعتی', 'mahan' ),
										),
									),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'price-list',
								array(
									'title'           => __( 'بسته‌های عکاسی', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '2',
									'style'           => 'card',
									'rows'            => array(
										array(
											'title' => __( 'پرترهٔ تک‌نفره', 'mahan' ),
											'text'  => __( 'یک ساعت، ده فایل ویرایش‌شده.', 'mahan' ),
											'price' => __( '۲,۴۰۰,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
										),
										array(
											'title' => __( 'عکاسی مراسم', 'mahan' ),
											'text'  => __( 'تمام‌روز، دو عکاس، آلبوم چاپی.', 'mahan' ),
											'price' => __( '۱۸,۰۰۰,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
											'badge' => __( 'پرطرفدار', 'mahan' ),
										),
										array(
											'title' => __( 'عکاسی محصول', 'mahan' ),
											'text'  => __( 'هر قلم کالا، سه زاویه.', 'mahan' ),
											'price' => __( '۳۵۰,۰۰۰', 'mahan' ),
											'unit'  => __( 'تومان', 'mahan' ),
										),
										array(
											'title' => __( 'اجارهٔ استودیو', 'mahan' ),
											'text'  => __( 'هر ساعت، با تجهیزات نور.', 'mahan' ),
											'price' => __( '۹۰۰,۰۰۰', 'mahan' ),
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
								'before-after',
								array(
									'before_image' => $media->card( 6 ),
									'after_image'  => $media->card( 7 ),
									'before_label' => __( 'خام', 'mahan' ),
									'after_label'  => __( 'پس از رتوش', 'mahan' ),
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'testimonial-carousel',
								array(
									'title'           => __( 'نظر مشتریان', 'mahan' ),
									'title_highlight' => 2,
									'source'          => 'cpt',
									'card_style'      => 'card',
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
									'icon'        => 'camera',
									'title'       => __( 'جلسهٔ عکاسی خود را رزرو کنید', 'mahan' ),
									'text'        => __( 'تقویم استودیو معمولاً تا دو هفته جلوتر پر است.', 'mahan' ),
									'button_text' => __( 'رزرو جلسه', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'portfolio' => array(
			'title'    => __( 'نمونه‌کارها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'portfolio-grid',
								array(
									'title'           => __( 'گالری کامل', 'mahan' ),
									'title_highlight' => 2,
									'posts_per_page'  => 12,
									'columns'         => '3',
									'style'           => 'card',
									'show_filter'     => 'yes',
								)
							),
						)
					)
					->to_array();
			},
		),
		'services'  => array(
			'title'    => __( 'خدمات', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'خدمات استودیو', 'mahan' ),
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
								'opening-hours',
								array(
									'title'           => __( 'ساعات استودیو', 'mahan' ),
									'title_highlight' => 2,
									'days'            => array(
										array(
											'day'   => __( 'شنبه تا چهارشنبه', 'mahan' ),
											'hours' => __( '۱۰:۰۰ تا ۱۹:۰۰', 'mahan' ),
										),
										array(
											'day'   => __( 'پنجشنبه', 'mahan' ),
											'hours' => __( '۱۰:۰۰ تا ۱۴:۰۰', 'mahan' ),
										),
										array(
											'day'    => __( 'جمعه', 'mahan' ),
											'hours'  => __( 'فقط با هماهنگی', 'mahan' ),
											'closed' => 'yes',
										),
									),
									'note'            => __( 'برای عکاسی مراسم، خارج از این ساعت‌ها هم هماهنگ می‌کنیم.', 'mahan' ),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->to_array();
			},
		),
		'about'     => array(
			'title'    => __( 'دربارهٔ استودیو', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 2 ),
									'title'           => __( 'ده سال پشت دوربین', 'mahan' ),
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
									'style'  => 'line',
									'quote'  => __( 'بهترین عکس آن است که کسی یادش نمی‌آید دوربینی آن‌جا بوده.', 'mahan' ),
									'author' => __( 'سرپرست استودیو', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'contact'   => array(
			'title'    => __( 'رزرو و تماس', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'contact-info',
								array(
									'title'           => __( 'استودیو کجاست؟', 'mahan' ),
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
		'blog'      => array( 'title' => __( 'یادداشت‌های عکاسی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی استودیو', 'mahan' ),
			'items' => array(
				'home'      => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'portfolio' => array(
					'title' => __( 'نمونه‌کارها', 'mahan' ),
					'page'  => 'portfolio',
					'icon'  => 'camera',
					'mega'  => true,
				),
				'services'  => array(
					'title' => __( 'خدمات', 'mahan' ),
					'page'  => 'services',
				),
				'about'     => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'contact'   => array(
					'title' => __( 'رزرو جلسه', 'mahan' ),
					'page'  => 'contact',
					'icon'  => 'calendar',
				),
			),
		),
	),

	'widgets' => array(),
);
