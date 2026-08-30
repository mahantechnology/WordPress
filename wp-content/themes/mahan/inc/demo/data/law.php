<?php
/**
 * Starter site: law firm / legal practice.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'tagline' => __( 'حق شما، کار ما', 'mahan' ),

	'options' => array(
		'header_layout'      => 'classic',
		'footer_layout'      => 'columns',
		'header_transparent' => false,
		'topbar_enabled'     => true,
		'topbar_text'        => __( 'وقت مشاوره: شنبه تا چهارشنبه، ۹ تا ۱۸', 'mahan' ),
		'header_cart'        => false,
		'blog_layout'        => 'list',
		'blog_sidebar'       => 'right',
		'radius'             => 4,
		'dark_mode'          => 'off',
		'footer_about_text'  => __( 'مؤسسهٔ حقوقی با پروانهٔ رسمی وکالت، متمرکز بر دعاوی حقوقی، کیفری، خانواده و شرکت‌ها.', 'mahan' ),
	),

	'services' => array(
		array(
			'title'   => __( 'دعاوی حقوقی', 'mahan' ),
			'excerpt' => __( 'مطالبهٔ وجه، الزام به تنظیم سند، خلع ید و اثبات مالکیت.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'shield' ),
		),
		array(
			'title'   => __( 'دعاوی کیفری', 'mahan' ),
			'excerpt' => __( 'دفاع تخصصی در پرونده‌های کلاهبرداری، خیانت در امانت و چک.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'lock' ),
		),
		array(
			'title'   => __( 'خانواده', 'mahan' ),
			'excerpt' => __( 'طلاق توافقی، مهریه، حضانت و نفقه با کمترین تنش.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'heart' ),
		),
		array(
			'title'   => __( 'حقوق شرکت‌ها', 'mahan' ),
			'excerpt' => __( 'ثبت و تغییرات شرکت، تنظیم قرارداد و حل اختلاف شرکا.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'building' ),
		),
		array(
			'title'   => __( 'ملکی و ثبتی', 'mahan' ),
			'excerpt' => __( 'افراز و تفکیک، تنظیم مبایعه‌نامه و دعاوی پیش‌فروش ساختمان.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'home' ),
		),
		array(
			'title'   => __( 'قراردادها', 'mahan' ),
			'excerpt' => __( 'تنظیم و بازبینی قرارداد پیش از امضا، برای جلوگیری از دعوا.', 'mahan' ),
			'meta'    => array( '_mahan_service_icon' => 'book' ),
		),
	),

	'team' => array(
		array(
			'title'   => __( 'دکتر بهرام قاسمی', 'mahan' ),
			'excerpt' => __( 'دکترای حقوق خصوصی، بیست سال سابقهٔ وکالت پایه یک دادگستری.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مؤسس و وکیل پایه یک', 'mahan' ) ),
		),
		array(
			'title'   => __( 'شیرین داوری', 'mahan' ),
			'excerpt' => __( 'متخصص دعاوی خانواده و میانجی‌گری، با رویکرد سازش‌محور.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'وکیل خانواده', 'mahan' ) ),
		),
		array(
			'title'   => __( 'امیر طاهری', 'mahan' ),
			'excerpt' => __( 'کارشناس ارشد حقوق جزا و جرم‌شناسی، مسئول پرونده‌های کیفری.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'وکیل کیفری', 'mahan' ) ),
		),
		array(
			'title'   => __( 'نازنین ابراهیمی', 'mahan' ),
			'excerpt' => __( 'مشاور حقوقی شرکت‌ها و متخصص تنظیم قراردادهای تجاری.', 'mahan' ),
			'meta'    => array( '_mahan_team_role' => __( 'مشاور حقوقی شرکت‌ها', 'mahan' ) ),
		),
	),

	'pages' => array(
		'home'     => array(
			'title'    => __( 'صفحهٔ اصلی', 'mahan' ),
			'meta'     => array( '_mahan_layout' => 'full' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'hero-banner',
								array(
									'image'           => $media->wide( 3 ),
									'layout'          => 'split',
									'eyebrow'         => __( 'مؤسسهٔ حقوقی ماهان', 'mahan' ),
									'title'           => __( 'حق شما را با دقت پیگیری می‌کنیم', 'mahan' ),
									'title_highlight' => 1,
									'description'     => __( 'از نخستین مشاوره تا رأی نهایی، پرونده‌تان را وکیلی می‌بندد که خودش آن را باز کرده است.', 'mahan' ),
									'primary_text'    => __( 'درخواست مشاوره', 'mahan' ),
									'secondary_text'  => __( 'زمینه‌های تخصص', 'mahan' ),
								)
							),
						),
						mahan_el_padding( 0 )
					)
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'زمینه‌های تخصص', 'mahan' ),
									'title_highlight' => 1,
									'subtitle'        => __( 'هر پرونده به وکیلی سپرده می‌شود که همان حوزه کار او است.', 'mahan' ),
									'posts_per_page'  => 6,
									'columns'         => '3',
								)
							),
						),
						mahan_el_padding( 72 )
					)
					->row(
						array(
							mahan_el(
								'process-steps',
								array(
									'title'           => __( 'مسیر پرونده، گام به گام', 'mahan' ),
									'title_highlight' => 2,
									'steps'           => array(
										array(
											'title' => __( 'مشاورهٔ اولیه', 'mahan' ),
											'text'  => __( 'شرح ماجرا را می‌شنویم و امکان‌سنجی حقوقی می‌کنیم.', 'mahan' ),
											'icon'  => 'headphones',
										),
										array(
											'title' => __( 'بررسی مدارک', 'mahan' ),
											'text'  => __( 'اسناد را می‌خوانیم و نقاط قوت و ضعف را صادقانه می‌گوییم.', 'mahan' ),
											'icon'  => 'folder',
										),
										array(
											'title' => __( 'تنظیم دادخواست', 'mahan' ),
											'text'  => __( 'لایحه و دادخواست را با دقت تنظیم و ثبت می‌کنیم.', 'mahan' ),
											'icon'  => 'pen',
										),
										array(
											'title' => __( 'پیگیری تا رأی', 'mahan' ),
											'text'  => __( 'در همهٔ جلسات حاضریم و شما را در جریان می‌گذاریم.', 'mahan' ),
											'icon'  => 'check-circle',
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
								'stats-counter',
								array(
									'show_heading' => '',
									'columns'      => '4',
									'counters'     => array(
										array(
											'value'  => 20,
											'suffix' => '+',
											'label'  => __( 'سال سابقه', 'mahan' ),
											'icon'   => 'clock',
										),
										array(
											'value'  => 1400,
											'suffix' => '+',
											'label'  => __( 'پروندهٔ مختومه', 'mahan' ),
											'icon'   => 'folder',
										),
										array(
											'value'  => 92,
											'suffix' => '٪',
											'label'  => __( 'آرای موفق', 'mahan' ),
											'icon'   => 'check-circle',
										),
										array(
											'value'  => 8,
											'label'  => __( 'وکیل و کارشناس', 'mahan' ),
											'icon'   => 'user',
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
									'title'           => __( 'وکلای مؤسسه', 'mahan' ),
									'title_highlight' => 1,
									'columns'         => '4',
								)
							),
						)
					)
					->row(
						array(
							mahan_el(
								'faq-accordion',
								array(
									'title'           => __( 'پرسش‌های حقوقی پرتکرار', 'mahan' ),
									'title_highlight' => 2,
									'faqs'           => array(
										array(
											'question' => __( 'هزینهٔ مشاوره چقدر است؟', 'mahan' ),
											'answer'   => __( 'نخستین جلسهٔ مشاورهٔ حضوری نیم‌ساعته رایگان است. حق‌الوکاله پس از بررسی پرونده و به‌صورت مکتوب اعلام می‌شود.', 'mahan' ),
										),
										array(
											'question' => __( 'پرونده چقدر طول می‌کشد؟', 'mahan' ),
											'answer'   => __( 'بسته به نوع دعوا و شعبهٔ رسیدگی متفاوت است. در جلسهٔ اول برآورد واقع‌بینانه‌ای از زمان به شما می‌دهیم.', 'mahan' ),
										),
										array(
											'question' => __( 'می‌توانم بدون حضور در دادگاه پرونده را پیش ببرم؟', 'mahan' ),
											'answer'   => __( 'بله. با اعطای وکالت رسمی، حضور در تمام جلسات بر عهدهٔ وکیل خواهد بود.', 'mahan' ),
										),
										array(
											'question' => __( 'اطلاعات پرونده محرمانه می‌ماند؟', 'mahan' ),
											'answer'   => __( 'رازداری تکلیف قانونی وکیل است؛ هیچ اطلاعاتی از پروندهٔ شما نزد کسی بازگو نمی‌شود.', 'mahan' ),
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
								'cta-banner',
								array(
									'icon'        => 'phone',
									'title'       => __( 'پرونده‌ای در دست دارید؟', 'mahan' ),
									'text'        => __( 'پیش از هر اقدامی، نیم ساعت با یک وکیل پایه یک صحبت کنید.', 'mahan' ),
									'button_text' => __( 'رزرو وقت مشاوره', 'mahan' ),
								)
							),
						)
					)
					->to_array();
			},
		),
		'services' => array(
			'title'    => __( 'خدمات حقوقی', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'service-grid',
								array(
									'title'           => __( 'همهٔ خدمات', 'mahan' ),
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
		'about'    => array(
			'title'    => __( 'دربارهٔ مؤسسه', 'mahan' ),
			'sections' => static function ( $media ) {
				return Mahan_Elementor_Builder::make()
					->row(
						array(
							mahan_el(
								'feature-grid',
								array(
									'image'           => $media->card( 4 ),
									'title'           => __( 'دو دهه در کنار موکلان', 'mahan' ),
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
								'timeline',
								array(
									'title'           => __( 'مسیر ما', 'mahan' ),
									'title_highlight' => 1,
									'items'           => array(
										array(
											'date'  => __( '۱۳۸۳', 'mahan' ),
											'title' => __( 'تأسیس دفتر', 'mahan' ),
											'text'  => __( 'کار را با یک وکیل و یک اتاق کوچک شروع کردیم.', 'mahan' ),
										),
										array(
											'date'  => __( '۱۳۹۰', 'mahan' ),
											'title' => __( 'تشکیل تیم تخصصی', 'mahan' ),
											'text'  => __( 'برای هر حوزهٔ حقوقی، وکیل متخصص خودش را آوردیم.', 'mahan' ),
										),
										array(
											'date'  => __( '۱۳۹۸', 'mahan' ),
											'title' => __( 'مشاورهٔ آنلاین', 'mahan' ),
											'text'  => __( 'برای موکلان شهرستانی، مشاورهٔ تصویری راه افتاد.', 'mahan' ),
										),
										array(
											'date'  => __( '۱۴۰۳', 'mahan' ),
											'title' => __( 'مؤسسهٔ حقوقی ثبت‌شده', 'mahan' ),
											'text'  => __( 'با هشت وکیل و کارشناس، در قالب یک مؤسسهٔ رسمی.', 'mahan' ),
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
		'contact'  => array(
			'title'    => __( 'تماس و وقت مشاوره', 'mahan' ),
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
		'blog'     => array( 'title' => __( 'مقالات حقوقی', 'mahan' ) ),
	),

	'menus' => array(
		'primary' => array(
			'name'  => __( 'منوی مؤسسهٔ حقوقی', 'mahan' ),
			'items' => array(
				'home'     => array(
					'title' => __( 'خانه', 'mahan' ),
					'page'  => 'home',
					'icon'  => 'home',
				),
				'services' => array(
					'title' => __( 'خدمات حقوقی', 'mahan' ),
					'page'  => 'services',
					'icon'  => 'shield',
					'mega'  => true,
				),
				'about'    => array(
					'title' => __( 'دربارهٔ مؤسسه', 'mahan' ),
					'page'  => 'about',
				),
				'blog'     => array(
					'title' => __( 'مقالات', 'mahan' ),
					'page'  => 'blog',
					'icon'  => 'book',
				),
				'contact'  => array(
					'title' => __( 'وقت مشاوره', 'mahan' ),
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
				'instance' => array( 'title' => __( 'جستجو در مقالات', 'mahan' ) ),
			),
			array(
				'type'     => 'mahan_posts',
				'instance' => array(
					'title'     => __( 'پرخواننده‌ترین‌ها', 'mahan' ),
					'count'     => 5,
					'orderby'   => 'comment_count',
					'thumbnail' => true,
				),
			),
			array(
				'type'     => 'mahan_contact',
				'instance' => array( 'title' => __( 'تماس فوری', 'mahan' ) ),
			),
		),
	),
);
