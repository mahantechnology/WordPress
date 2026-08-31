<?php
/**
 * One description of every theme option, shared by the customizer and the
 * Mahan admin panel so the two can never drift apart.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Schema {

	/**
	 * Cached schema for the current request.
	 *
	 * @var array|null
	 */
	private static $cache = null;

	/**
	 * The full option schema, grouped into the panels the UI renders as tabs.
	 *
	 * Each field carries a `type` the renderers understand: text, textarea,
	 * url, number, checkbox, select or color.
	 *
	 * @return array
	 */
	public static function all() {
		if ( null !== self::$cache ) {
			return self::$cache;
		}

		$palettes = array();

		foreach ( Mahan_Options::palettes() as $key => $palette ) {
			$palettes[ $key ] = $palette['label'];
		}

		$fonts = array();

		foreach ( Mahan_Options::fonts() as $key => $font ) {
			$fonts[ $key ] = $font['label'];
		}

		$sides = array(
			'right' => __( 'راست', 'mahan' ),
			'left'  => __( 'چپ', 'mahan' ),
			'none'  => __( 'بدون ستون کناری', 'mahan' ),
		);

		$schema = array(

			'colors'      => array(
				'label'       => __( 'رنگ‌ها', 'mahan' ),
				'icon'        => 'sparkles',
				'description' => __( 'یک پالت آماده انتخاب کنید یا تک‌تک رنگ‌ها را دستی تنظیم کنید. همهٔ رنگ‌ها به‌صورت متغیر CSS به مرورگر می‌رسند، پس تغییرشان کل سایت را با هم عوض می‌کند.', 'mahan' ),
				'fields'      => array(
					'palette'               => array(
						'label'       => __( 'پالت آماده', 'mahan' ),
						'type'        => 'select',
						'choices'     => $palettes,
						'description' => __( 'با انتخاب پالت، سه رنگ اصلی زیر به‌روزرسانی می‌شوند.', 'mahan' ),
						'swatches'    => true,
					),
					'color_primary'         => array(
						'label'     => __( 'رنگ اصلی', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_secondary'       => array(
						'label'     => __( 'رنگ دوم', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_accent'          => array(
						'label'     => __( 'رنگ تأکید', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_success'         => array(
						'label' => __( 'رنگ موفقیت', 'mahan' ),
						'type'  => 'color',
					),
					'color_danger'          => array(
						'label' => __( 'رنگ خطا', 'mahan' ),
						'type'  => 'color',
					),
					'color_text'            => array(
						'label'     => __( 'رنگ متن', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_muted'           => array(
						'label'     => __( 'رنگ متن کم‌رنگ', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_surface'         => array(
						'label'     => __( 'رنگ کارت‌ها', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_background'      => array(
						'label'     => __( 'رنگ پس‌زمینه', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'color_border'          => array(
						'label'     => __( 'رنگ خطوط', 'mahan' ),
						'type'      => 'color',
						'transport' => 'postMessage',
					),
					'dark_mode'             => array(
						'label'   => __( 'حالت تاریک', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'off'    => __( 'غیرفعال', 'mahan' ),
							'toggle' => __( 'کلید دستی در هدر', 'mahan' ),
							'auto'   => __( 'خودکار بر اساس سیستم کاربر', 'mahan' ),
							'always' => __( 'همیشه تاریک', 'mahan' ),
						),
					),
					'dark_color_text'       => array(
						'label' => __( 'متن (تاریک)', 'mahan' ),
						'type'  => 'color',
					),
					'dark_color_muted'      => array(
						'label' => __( 'متن کم‌رنگ (تاریک)', 'mahan' ),
						'type'  => 'color',
					),
					'dark_color_surface'    => array(
						'label' => __( 'کارت‌ها (تاریک)', 'mahan' ),
						'type'  => 'color',
					),
					'dark_color_background' => array(
						'label' => __( 'پس‌زمینه (تاریک)', 'mahan' ),
						'type'  => 'color',
					),
					'dark_color_border'     => array(
						'label' => __( 'خطوط (تاریک)', 'mahan' ),
						'type'  => 'color',
					),
				),
			),

			'typography'  => array(
				'label'       => __( 'تایپوگرافی', 'mahan' ),
				'icon'        => 'pen',
				'description' => __( 'فونت، اندازه و ارتفاع خط. متن فارسی با ارتفاع خط ۱.۸ تا ۲ خواناتر است.', 'mahan' ),
				'fields'      => array(
					'font_family'    => array(
						'label'   => __( 'فونت سایت', 'mahan' ),
						'type'    => 'select',
						'choices' => $fonts,
					),
					'font_size_base' => array(
						'label'     => __( 'اندازهٔ پایهٔ متن', 'mahan' ),
						'type'      => 'number',
						'min'       => 13,
						'max'       => 20,
						'step'      => 1,
						'unit'      => __( 'پیکسل', 'mahan' ),
						'transport' => 'postMessage',
					),
					'font_scale'     => array(
						'label'       => __( 'نسبت بزرگی عنوان‌ها', 'mahan' ),
						'type'        => 'number',
						'min'         => 1.05,
						'max'         => 1.45,
						'step'        => 0.01,
						'description' => __( 'عدد بزرگ‌تر یعنی تیترهای درشت‌تر. مقدار پیشنهادی ۱.۲۲ است.', 'mahan' ),
					),
					'line_height'    => array(
						'label'     => __( 'ارتفاع خط', 'mahan' ),
						'type'      => 'number',
						'min'       => 1.4,
						'max'       => 2.4,
						'step'      => 0.05,
						'transport' => 'postMessage',
					),
					'body_weight'    => array(
						'label'   => __( 'ضخامت متن', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							300 => __( 'نازک', 'mahan' ),
							400 => __( 'معمولی', 'mahan' ),
							500 => __( 'نیمه‌ضخیم', 'mahan' ),
						),
					),
					'heading_weight' => array(
						'label'   => __( 'ضخامت عنوان‌ها', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							400 => __( 'معمولی', 'mahan' ),
							500 => __( 'نیمه‌ضخیم', 'mahan' ),
							600 => __( 'ضخیم', 'mahan' ),
							700 => __( 'خیلی ضخیم', 'mahan' ),
							800 => __( 'سیاه', 'mahan' ),
						),
					),
					'force_rtl'      => array(
						'label'       => __( 'راست‌چین اجباری', 'mahan' ),
						'type'        => 'checkbox',
						'description' => __( 'چیدمان را حتی روی زبان‌های چپ‌چین هم راست‌چین نگه می‌دارد. اگر قالب را ترجمه کرده‌اید خاموش کنید.', 'mahan' ),
					),
					'persian_digits' => array(
						'label'       => __( 'تبدیل اعداد به فارسی', 'mahan' ),
						'type'        => 'checkbox',
						'description' => __( 'تاریخ‌ها، قیمت‌ها و شمارنده‌ها با ارقام فارسی نمایش داده می‌شوند.', 'mahan' ),
					),
				),
			),

			'layout'      => array(
				'label'       => __( 'چیدمان', 'mahan' ),
				'icon'        => 'layers',
				'description' => __( 'عرض محتوا، گردی گوشه‌ها، سایه‌ها و فاصلهٔ بخش‌ها.', 'mahan' ),
				'fields'      => array(
					'container_width'  => array(
						'label'     => __( 'عرض محتوا', 'mahan' ),
						'type'      => 'number',
						'min'       => 960,
						'max'       => 1600,
						'step'      => 20,
						'unit'      => __( 'پیکسل', 'mahan' ),
						'transport' => 'postMessage',
					),
					'radius'           => array(
						'label'     => __( 'گردی گوشه‌ها', 'mahan' ),
						'type'      => 'number',
						'min'       => 0,
						'max'       => 32,
						'step'      => 1,
						'unit'      => __( 'پیکسل', 'mahan' ),
						'transport' => 'postMessage',
					),
					'section_spacing'  => array(
						'label'     => __( 'فاصلهٔ بین بخش‌ها', 'mahan' ),
						'type'      => 'number',
						'min'       => 40,
						'max'       => 160,
						'step'      => 4,
						'unit'      => __( 'پیکسل', 'mahan' ),
						'transport' => 'postMessage',
					),
					'shadow_strength'  => array(
						'label'   => __( 'شدت سایه‌ها', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'none'   => __( 'بدون سایه', 'mahan' ),
							'soft'   => __( 'ملایم', 'mahan' ),
							'strong' => __( 'پررنگ', 'mahan' ),
						),
					),
					'sticky_sidebar'   => array(
						'label' => __( 'چسبیدن ستون کناری هنگام اسکرول', 'mahan' ),
						'type'  => 'checkbox',
					),
					'page_transition'  => array(
						'label' => __( 'انیمیشن ورود عناصر', 'mahan' ),
						'type'  => 'checkbox',
					),
					'back_to_top'      => array(
						'label' => __( 'دکمهٔ بازگشت به بالا', 'mahan' ),
						'type'  => 'checkbox',
					),
					'preloader'        => array(
						'label' => __( 'نمایش لودر هنگام باز شدن صفحه', 'mahan' ),
						'type'  => 'checkbox',
					),
				),
			),

			'header'      => array(
				'label'       => __( 'هدر', 'mahan' ),
				'icon'        => 'grid',
				'description' => __( 'پنج چیدمان آماده به‌همراه نوار اعلان، جستجو، سبد خرید و منوی موبایل.', 'mahan' ),
				'fields'      => array(
					'header_layout'        => array(
						'label'   => __( 'چیدمان هدر', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'classic'  => __( 'کلاسیک (لوگو راست، منو وسط)', 'mahan' ),
							'centered' => __( 'وسط‌چین', 'mahan' ),
							'split'    => __( 'دو ردیفه', 'mahan' ),
							'minimal'  => __( 'مینیمال', 'mahan' ),
							'shop'     => __( 'فروشگاهی (با جستجوی بزرگ)', 'mahan' ),
							'glass'    => __( 'شیشه‌ای شناور', 'mahan' ),
							'gradient' => __( 'نوار گرادیانی', 'mahan' ),
							'stack'    => __( 'لوگوی وسط، منوی زیر آن', 'mahan' ),
						),
					),
					'header_cta_text'      => array(
						'label'       => __( 'متن دکمهٔ فراخوان هدر', 'mahan' ),
						'type'        => 'text',
						'description' => __( 'خالی بگذارید تا دکمه‌ای نمایش داده نشود.', 'mahan' ),
					),
					'header_cta_url'       => array(
						'label' => __( 'لینک دکمهٔ فراخوان هدر', 'mahan' ),
						'type'  => 'url',
					),
					'header_sticky'        => array(
						'label' => __( 'هدر چسبان هنگام اسکرول', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_sticky_shrink' => array(
						'label' => __( 'کوچک شدن هدر چسبان', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_transparent'   => array(
						'label' => __( 'هدر شیشه‌ای روی صفحهٔ اصلی', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_dark'          => array(
						'label' => __( 'هدر تیره', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_search'        => array(
						'label' => __( 'دکمهٔ جستجو', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_account'       => array(
						'label' => __( 'دکمهٔ حساب کاربری', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_cart'          => array(
						'label' => __( 'دکمهٔ سبد خرید', 'mahan' ),
						'type'  => 'checkbox',
					),
					'header_wishlist'      => array(
						'label' => __( 'دکمهٔ علاقه‌مندی‌ها', 'mahan' ),
						'type'  => 'checkbox',
					),
					'topbar_enabled'       => array(
						'label' => __( 'نمایش نوار اعلان بالای هدر', 'mahan' ),
						'type'  => 'checkbox',
					),
					'topbar_text'          => array(
						'label'     => __( 'متن نوار اعلان', 'mahan' ),
						'type'      => 'text',
						'transport' => 'postMessage',
					),
					'topbar_phone'         => array(
						'label' => __( 'شمارهٔ تماس نوار اعلان', 'mahan' ),
						'type'  => 'text',
					),
					'mobile_menu_style'    => array(
						'label'   => __( 'سبک منوی موبایل', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'drawer'     => __( 'کشویی از کنار', 'mahan' ),
							'fullscreen' => __( 'تمام‌صفحه', 'mahan' ),
						),
					),
					'mobile_bottom_bar'    => array(
						'label' => __( 'نوار پایین صفحه در موبایل', 'mahan' ),
						'type'  => 'checkbox',
					),
				),
			),

			'footer'      => array(
				'label'       => __( 'فوتر', 'mahan' ),
				'icon'        => 'list',
				'description' => __( 'چیدمان فوتر، خبرنامه، نمادهای اعتماد و متن کپی‌رایت.', 'mahan' ),
				'fields'      => array(
					'footer_layout'      => array(
						'label'   => __( 'چیدمان فوتر', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'columns'  => __( 'ستونی', 'mahan' ),
							'compact'  => __( 'فشرده', 'mahan' ),
							'centered' => __( 'وسط‌چین', 'mahan' ),
							'shop'     => __( 'فروشگاهی (با نمادها)', 'mahan' ),
							'mega'     => __( 'بزرگ (با کارت تماس)', 'mahan' ),
							'cta'      => __( 'با پنل فراخوان', 'mahan' ),
							'minimal'  => __( 'مینیمال تک‌ردیفه', 'mahan' ),
						),
					),
					'footer_columns'     => array(
						'label'       => __( 'تعداد ستون‌ها', 'mahan' ),
						'type'        => 'number',
						'min'         => 1,
						'max'         => 6,
						'step'        => 1,
						'description' => __( 'پس از تغییر، ابزارک‌های ستون‌های تازه را در بخش ابزارک‌ها پر کنید.', 'mahan' ),
					),
					'footer_dark'        => array(
						'label' => __( 'فوتر تیره', 'mahan' ),
						'type'  => 'checkbox',
					),
					'footer_newsletter'  => array(
						'label' => __( 'نوار خبرنامه', 'mahan' ),
						'type'  => 'checkbox',
					),
					'footer_badges'      => array(
						'label' => __( 'نمایش نمادهای اعتماد', 'mahan' ),
						'type'  => 'checkbox',
					),
					'footer_about_title' => array(
						'label' => __( 'عنوان بخش دربارهٔ فوتر', 'mahan' ),
						'type'  => 'text',
					),
					'footer_about_text'  => array(
						'label' => __( 'متن دربارهٔ فوتر', 'mahan' ),
						'type'  => 'textarea',
					),
					'footer_copyright'   => array(
						'label'       => __( 'متن کپی‌رایت', 'mahan' ),
						'type'        => 'textarea',
						'description' => __( 'می‌توانید از {site} و {year} استفاده کنید.', 'mahan' ),
					),
				),
			),

			'blog'        => array(
				'label'       => __( 'بلاگ', 'mahan' ),
				'icon'        => 'book',
				'description' => __( 'آرشیو نوشته‌ها و صفحهٔ تک‌نوشته.', 'mahan' ),
				'fields'      => array(
					'blog_layout'            => array(
						'label'   => __( 'چیدمان آرشیو', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'grid'     => __( 'شبکه‌ای', 'mahan' ),
							'list'     => __( 'فهرستی', 'mahan' ),
							'masonry'  => __( 'آجری', 'mahan' ),
							'magazine' => __( 'مجله‌ای', 'mahan' ),
						),
					),
					'blog_columns'           => array(
						'label' => __( 'تعداد ستون‌ها', 'mahan' ),
						'type'  => 'number',
						'min'   => 1,
						'max'   => 4,
						'step'  => 1,
					),
					'blog_sidebar'           => array(
						'label'   => __( 'ستون کناری آرشیو', 'mahan' ),
						'type'    => 'select',
						'choices' => $sides,
					),
					'blog_excerpt_length'    => array(
						'label' => __( 'تعداد کلمات خلاصه', 'mahan' ),
						'type'  => 'number',
						'min'   => 8,
						'max'   => 80,
						'step'  => 1,
					),
					'blog_pagination'        => array(
						'label'   => __( 'نوع صفحه‌بندی', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'numbers'  => __( 'شماره‌گذاری', 'mahan' ),
							'loadmore' => __( 'دکمهٔ نمایش بیشتر', 'mahan' ),
							'infinite' => __( 'بارگذاری خودکار', 'mahan' ),
						),
					),
					'blog_show_author'       => array(
						'label' => __( 'نمایش نویسنده', 'mahan' ),
						'type'  => 'checkbox',
					),
					'blog_show_date'         => array(
						'label' => __( 'نمایش تاریخ', 'mahan' ),
						'type'  => 'checkbox',
					),
					'blog_show_category'     => array(
						'label' => __( 'نمایش دسته‌بندی', 'mahan' ),
						'type'  => 'checkbox',
					),
					'blog_show_reading_time' => array(
						'label' => __( 'نمایش زمان مطالعه', 'mahan' ),
						'type'  => 'checkbox',
					),
					'blog_show_views'        => array(
						'label' => __( 'نمایش تعداد بازدید', 'mahan' ),
						'type'  => 'checkbox',
					),
					'single_layout'          => array(
						'label'   => __( 'چیدمان تک‌نوشته', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'sidebar' => __( 'با ستون کناری', 'mahan' ),
							'narrow'  => __( 'باریک و متمرکز', 'mahan' ),
							'wide'    => __( 'تمام‌عرض', 'mahan' ),
						),
					),
					'single_sidebar'         => array(
						'label'   => __( 'سمت ستون کناری تک‌نوشته', 'mahan' ),
						'type'    => 'select',
						'choices' => $sides,
					),
					'single_share'           => array(
						'label' => __( 'دکمه‌های اشتراک‌گذاری', 'mahan' ),
						'type'  => 'checkbox',
					),
					'single_toc'             => array(
						'label' => __( 'فهرست مطالب خودکار', 'mahan' ),
						'type'  => 'checkbox',
					),
					'single_author_box'      => array(
						'label' => __( 'باکس نویسنده', 'mahan' ),
						'type'  => 'checkbox',
					),
					'single_related'         => array(
						'label' => __( 'نوشته‌های مرتبط', 'mahan' ),
						'type'  => 'checkbox',
					),
					'single_related_count'   => array(
						'label' => __( 'تعداد نوشته‌های مرتبط', 'mahan' ),
						'type'  => 'number',
						'min'   => 2,
						'max'   => 8,
						'step'  => 1,
					),
					'single_progress_bar'    => array(
						'label' => __( 'نوار پیشرفت مطالعه', 'mahan' ),
						'type'  => 'checkbox',
					),
					'single_prev_next'       => array(
						'label' => __( 'پیمایش نوشتهٔ قبلی و بعدی', 'mahan' ),
						'type'  => 'checkbox',
					),
				),
			),

			'shop'        => array(
				'label'       => __( 'فروشگاه', 'mahan' ),
				'icon'        => 'cart',
				'description' => __( 'تنظیمات ووکامرس. این بخش فقط وقتی اثر دارد که ووکامرس فعال باشد.', 'mahan' ),
				'requires'    => 'woocommerce',
				'fields'      => array(
					'shop_columns'            => array(
						'label' => __( 'تعداد ستون‌های محصولات', 'mahan' ),
						'type'  => 'number',
						'min'   => 2,
						'max'   => 6,
						'step'  => 1,
					),
					'shop_per_page'           => array(
						'label' => __( 'تعداد محصول در هر صفحه', 'mahan' ),
						'type'  => 'number',
						'min'   => 4,
						'max'   => 48,
						'step'  => 1,
					),
					'shop_sidebar'            => array(
						'label'   => __( 'ستون کناری فروشگاه', 'mahan' ),
						'type'    => 'select',
						'choices' => $sides,
					),
					'shop_card_style'         => array(
						'label'   => __( 'سبک کارت محصول', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'modern'  => __( 'مدرن', 'mahan' ),
							'classic' => __( 'کلاسیک', 'mahan' ),
							'minimal' => __( 'مینیمال', 'mahan' ),
							'overlay' => __( 'روی تصویر', 'mahan' ),
						),
					),
					'single_product_layout'   => array(
						'label'   => __( 'چیدمان صفحهٔ محصول', 'mahan' ),
						'type'    => 'select',
						'choices' => array(
							'modern'  => __( 'مدرن', 'mahan' ),
							'classic' => __( 'کلاسیک', 'mahan' ),
							'wide'    => __( 'تمام‌عرض', 'mahan' ),
						),
					),
					'shop_quick_view'         => array(
						'label' => __( 'مشاهدهٔ سریع محصول', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_ajax_add_to_cart'   => array(
						'label' => __( 'افزودن به سبد بدون بارگذاری صفحه', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_wishlist'           => array(
						'label' => __( 'فهرست علاقه‌مندی‌ها', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_badge_discount'     => array(
						'label' => __( 'نمایش درصد تخفیف', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_hover_gallery'      => array(
						'label' => __( 'تعویض تصویر با هاور', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_stock_bar'          => array(
						'label' => __( 'نوار موجودی کم', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_sticky_add_to_cart' => array(
						'label' => __( 'نوار خرید چسبان در صفحهٔ محصول', 'mahan' ),
						'type'  => 'checkbox',
					),
					'shop_trust_badges'       => array(
						'label' => __( 'نمادهای اعتماد در صفحهٔ محصول', 'mahan' ),
						'type'  => 'checkbox',
					),
				),
			),

			'social'      => array(
				'label'       => __( 'تماس و شبکه‌ها', 'mahan' ),
				'icon'        => 'phone',
				'description' => __( 'این مقادیر در هدر، فوتر، ابزارک تماس و المان‌های المنتور استفاده می‌شوند.', 'mahan' ),
				'fields'      => array(
					'contact_phone'    => array(
						'label' => __( 'شمارهٔ تماس', 'mahan' ),
						'type'  => 'text',
					),
					'contact_email'    => array(
						'label' => __( 'ایمیل', 'mahan' ),
						'type'  => 'text',
					),
					'contact_address'  => array(
						'label' => __( 'نشانی', 'mahan' ),
						'type'  => 'textarea',
					),
					'social_instagram' => array(
						'label' => __( 'اینستاگرام', 'mahan' ),
						'type'  => 'url',
					),
					'social_telegram'  => array(
						'label' => __( 'تلگرام', 'mahan' ),
						'type'  => 'url',
					),
					'social_whatsapp'  => array(
						'label' => __( 'واتساپ', 'mahan' ),
						'type'  => 'url',
					),
					'social_linkedin'  => array(
						'label' => __( 'لینکدین', 'mahan' ),
						'type'  => 'url',
					),
					'social_twitter'   => array(
						'label' => __( 'ایکس (توییتر)', 'mahan' ),
						'type'  => 'url',
					),
					'social_youtube'   => array(
						'label' => __( 'یوتیوب', 'mahan' ),
						'type'  => 'url',
					),
					'social_aparat'    => array(
						'label' => __( 'آپارات', 'mahan' ),
						'type'  => 'url',
					),
				),
			),

			'performance' => array(
				'label'       => __( 'کارایی', 'mahan' ),
				'icon'        => 'lightning',
				'description' => __( 'گزینه‌هایی برای سبک‌تر شدن صفحه‌ها. اگر افزونهٔ بهینه‌سازی دارید، ممکن است بعضی از این‌ها را آن افزونه هم انجام دهد.', 'mahan' ),
				'fields'      => array(
					'lazy_load'      => array(
						'label' => __( 'بارگذاری تنبل تصاویر', 'mahan' ),
						'type'  => 'checkbox',
					),
					'disable_emoji'  => array(
						'label' => __( 'غیرفعال کردن اسکریپت ایموجی وردپرس', 'mahan' ),
						'type'  => 'checkbox',
					),
					'disable_embeds' => array(
						'label' => __( 'غیرفعال کردن اسکریپت embed وردپرس', 'mahan' ),
						'type'  => 'checkbox',
					),
					'preload_fonts'  => array(
						'label' => __( 'پیش‌بارگذاری فونت‌ها', 'mahan' ),
						'type'  => 'checkbox',
					),
				),
			),
		);

		/**
		 * Filters the shared option schema.
		 *
		 * @param array $schema Groups of fields.
		 */
		self::$cache = apply_filters( 'mahan_option_schema', $schema );

		return self::$cache;
	}

	/**
	 * One group, or null when the key is unknown.
	 *
	 * @param string $group Group key.
	 * @return array|null
	 */
	public static function group( $group ) {
		$all = self::all();

		return isset( $all[ $group ] ) ? $all[ $group ] : null;
	}

	/**
	 * The definition for one field, searched across every group.
	 *
	 * @param string $key Option key.
	 * @return array|null
	 */
	public static function field( $key ) {
		foreach ( self::all() as $group ) {
			if ( isset( $group['fields'][ $key ] ) ) {
				return $group['fields'][ $key ];
			}
		}

		return null;
	}

	/**
	 * Every option key the schema covers.
	 *
	 * @return string[]
	 */
	public static function keys() {
		$keys = array();

		foreach ( self::all() as $group ) {
			$keys = array_merge( $keys, array_keys( $group['fields'] ) );
		}

		return $keys;
	}
}
