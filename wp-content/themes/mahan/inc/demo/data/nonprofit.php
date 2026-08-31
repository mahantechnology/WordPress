<?php
/**
 * Starter site: charity / non-governmental organisation.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'با هم، کاری از پیش می‌رود', 'mahan' ),

	'options' => array(
		'header_layout'      => 'gradient',
		'footer_layout'      => 'mega',
		'header_transparent' => false,
		'header_cta_text'    => __( 'کمک به کمپین', 'mahan' ),
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'گزارش مالی سال گذشته منتشر شد', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'grid',
		'blog_sidebar'       => 'right',
		'radius'             => 16,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'مؤسسهٔ مردم‌نهاد ماهان، فعال در آموزش و سلامت کودکان مناطق کم‌برخوردار.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'آموزش کودکان', 'mahan' ),
			'excerpt' => __( 'تأمین لوازم‌التحریر و کلاس‌های جبرانی در سی مدرسه.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'graduation' ),
		),
		array(
			'title'   => __( 'سلامت و درمان', 'mahan' ),
			'excerpt' => __( 'ویزیت رایگان و غربالگری در روستاهای دورافتاده.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'stethoscope' ),
		),
		array(
			'title'   => __( 'بستهٔ معیشتی', 'mahan' ),
			'excerpt' => __( 'کمک ماهانه به خانواده‌های تحت پوشش.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'gift' ),
		),
		array(
			'title'   => __( 'ساخت و بازسازی', 'mahan' ),
			'excerpt' => __( 'مقاوم‌سازی مدرسه‌ها و تأمین آب آشامیدنی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'building' ),
		),
		array(
			'title'   => __( 'حمایت تحصیلی', 'mahan' ),
			'excerpt' => __( 'بورسیهٔ دانش‌آموزان مستعد تا پایان دبیرستان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'book' ),
		),
		array(
			'title'   => __( 'توانمندسازی', 'mahan' ),
			'excerpt' => __( 'آموزش مهارت و کمک به راه‌اندازی کسب‌وکار خانگی.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lightning' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'زهرا مهدوی', 'mahan' ),
			'excerpt' => __( 'بنیان‌گذار مؤسسه، با پانزده سال کار میدانی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیرعامل', 'mahan' ) ),
		),
		array(
			'title'   => __( 'حسین رجبی', 'mahan' ),
			'excerpt' => __( 'مسئول هماهنگی پروژه‌های آموزشی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مدیر پروژه‌ها', 'mahan' ) ),
		),
		array(
			'title'   => __( 'مینا فرهادی', 'mahan' ),
			'excerpt' => __( 'حسابرس داخلی و مسئول شفافیت مالی.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'امور مالی', 'mahan' ) ),
		),
		array(
			'title'   => __( 'کاوه سعیدی', 'mahan' ),
			'excerpt' => __( 'هماهنگ‌کنندهٔ شبکهٔ داوطلبان در هشت استان.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مسئول داوطلبان', 'mahan' ) ),
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
									'image'           => $media->wide( 4 ),
									'layout'          => 'center',
									'eyebrow'         => __( 'مؤسسهٔ مردم‌نهاد ماهان', 'mahan' ),
									'title'           => __( 'با هم، کاری از پیش می‌رود', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'هر کمک، هرقدر کوچک، به آموزش و سلامت کودکی می‌رسد که به آن نیاز دارد.', 'mahan' ),
									'primary_text'    => __( 'مشارکت در کمپین', 'mahan' ),
									'secondary_text'  => __( 'گزارش عملکرد', 'mahan' ),
									'stats'           => array(),
								)
							),
						),
						mahan_el_full()
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
											'value'  => 12400,
											'suffix' => '+',
											'label'  => __( 'کودک تحت پوشش', 'mahan' ),
											'icon'   => 'heart',
										),
										array(
											'value' => 32,
											'label' => __( 'مدرسهٔ بازسازی‌شده', 'mahan' ),
											'icon'  => 'building',
										),
										array(
											'value'  => 860,
											'suffix' => '+',
											'label'  => __( 'داوطلب فعال', 'mahan' ),
											'icon'   => 'user',
										),
										array(
											'value'  => 96,
											'suffix' => '٪',
											'label'  => __( 'رسیدن کمک به هدف', 'mahan' ),
											'icon'   => 'check-circle',
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
									'title'           => __( 'کارهایی که می‌کنیم', 'mahan' ),
									'title_highlight' => 2,
									'subtitle'        => __( 'شش برنامهٔ جاری، همه با گزارش عمومی.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'progress-bars',
								array(
									'title'           => __( 'کمپین‌های در جریان', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'درصد تأمین بودجهٔ هر کمپین تا امروز.', 'mahan' ),
								)
							),
						),
						mahan_el_bg( '#f6f7fb' )
					)
					->row(
						array(
							mahan_el(
								'blockquote',
								array(
									'style'  => 'card',
									'quote'  => __( 'وقتی یک کلاس درس سقف داشته باشد، بقیهٔ کارها آسان‌تر می‌شود.', 'mahan' ),
									'author' => __( 'مدیر یکی از مدرسه‌های تحت پوشش', 'mahan' ),
									'align'  => 'center',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'team-grid',
								array(
									'title'           => __( 'تیم مؤسسه', 'mahan' ),
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
								'download-list',
								array(
									'title'           => __( 'گزارش‌های شفاف', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'صورت مالی و گزارش عملکرد، هر سال منتشر می‌شود.', 'mahan' ),
									'columns'         => '2',
									'files'           => array(
										array(
											'title' => __( 'گزارش عملکرد سالانه', 'mahan' ),
											'text'  => __( 'شرح پروژه‌ها و نتایج میدانی.', 'mahan' ),
											'kind'  => 'PDF',
											'size'  => __( '۳٫۱ مگابایت', 'mahan' ),
										),
										array(
											'title' => __( 'صورت‌های مالی حسابرسی‌شده', 'mahan' ),
											'text'  => __( 'درآمد و هزینهٔ هر برنامه.', 'mahan' ),
											'kind'  => 'PDF',
											'size'  => __( '۱٫۶ مگابایت', 'mahan' ),
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
									'icon'        => 'heart',
									'title'       => __( 'همراه ما شوید', 'mahan' ),
									'text'        => __( 'با کمک مالی یا وقت‌گذاشتن به‌عنوان داوطلب.', 'mahan' ),
									'button_text' => __( 'مشارکت', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'campaigns'  => array(
			'title'    => __( 'کمپین‌ها', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'همهٔ برنامه‌ها', 'mahan' ),
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
		'volunteer'  => array(
			'title'    => __( 'داوطلب شوید', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'job-list',
								array(
									'title'           => __( 'فراخوان داوطلبان', 'mahan' ),
									'title_highlight' => 2,
									'cta_label'       => __( 'ثبت‌نام', 'mahan' ),
									'jobs'            => array(
										array(
											'title'      => __( 'مدرس داوطلب ریاضی', 'mahan' ),
											'department' => __( 'آموزش', 'mahan' ),
											'location'   => __( 'تهران', 'mahan' ),
											'type'       => __( 'هفته‌ای ۴ ساعت', 'mahan' ),
										),
										array(
											'title'      => __( 'پزشک داوطلب', 'mahan' ),
											'department' => __( 'سلامت', 'mahan' ),
											'location'   => __( 'اردوهای استانی', 'mahan' ),
											'type'       => __( 'دوره‌ای', 'mahan' ),
										),
										array(
											'title'      => __( 'کمک در امور اداری', 'mahan' ),
											'department' => __( 'پشتیبانی', 'mahan' ),
											'location'   => __( 'دورکاری', 'mahan' ),
											'type'       => __( 'انعطاف‌پذیر', 'mahan' ),
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
									'title'           => __( 'پرسش‌های داوطلبان', 'mahan' ),
									'title_highlight' => 2,
									'faqs'            => array(
										array(
											'question' => __( 'چقدر وقت باید بگذارم؟', 'mahan' ),
											'answer'   => __( 'بسته به نقش، از هفته‌ای دو ساعت تا حضور در اردوهای چندروزه.', 'mahan' ),
										),
										array(
											'question' => __( 'آموزش می‌بینیم؟', 'mahan' ),
											'answer'   => __( 'بله، پیش از شروع یک جلسهٔ توجیهی و راهنمای مکتوب دریافت می‌کنید.', 'mahan' ),
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
		'about'      => array(
			'title'    => __( 'دربارهٔ مؤسسه', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 5 ),
									'title'           => __( 'از یک کلاس درس شروع شد', 'mahan' ),
									'title_highlight' => 3,
									'heading_align'   => 'right',
									'media_position'  => 'left',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'timeline',
								array(
									'title'           => __( 'مسیر ما', 'mahan' ),
									'title_highlight' => 1,
									'items'           => array(
										array(
											'date'  => __( '۱۳۹۰', 'mahan' ),
											'title' => __( 'نخستین کلاس', 'mahan' ),
											'text'  => __( 'با پنج داوطلب و یک اتاق اجاره‌ای.', 'mahan' ),
										),
										array(
											'date'  => __( '۱۳۹۵', 'mahan' ),
											'title' => __( 'ثبت رسمی مؤسسه', 'mahan' ),
											'text'  => __( 'و آغاز انتشار گزارش مالی سالانه.', 'mahan' ),
										),
										array(
											'date'  => __( '۱۴۰۰', 'mahan' ),
											'title' => __( 'گسترش به هشت استان', 'mahan' ),
											'text'  => __( 'با شبکه‌ای از داوطلبان محلی.', 'mahan' ),
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
		'contact'    => array(
			'title'    => __( 'تماس با ما', 'mahan' ),
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
		'blog'       => array( 'title' => __( 'اخبار و گزارش‌ها', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی مؤسسه', 'mahan' ),
			'items' => array(
				'home'      => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'campaigns' => array(
					'title' => __( 'کمپین‌ها', 'mahan' ),
					'page'  => 'campaigns',
					'icon'  => 'heart',
				),
				'volunteer' => array(
					'title' => __( 'داوطلب شوید', 'mahan' ),
					'page'  => 'volunteer',
					'badge' => __( 'فراخوان', 'mahan' ),
				),
				'about'     => array(
					'title' => __( 'دربارهٔ ما', 'mahan' ),
					'page'  => 'about',
				),
				'blog'      => array(
					'title' => __( 'اخبار', 'mahan' ),
					'page'  => 'blog',
				),
				'contact'   => array(
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
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'تازه‌ترین گزارش‌ها', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'date',
					'thumbnail' => true,
				),
			),
		),
	),
);
