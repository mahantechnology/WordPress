<?php
/**
 * Customizer panels for every theme option.
 *
 * Each control writes into the single `mahan_settings` theme mod through the
 * option-array syntax, which keeps the settings exportable in one piece.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Customizer {

	/**
	 * Hooks the customizer registration.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'preview_script' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_style' ) );
	}

	/**
	 * Registers every panel, section and control.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	public function register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->add_panel(
			'mahan',
			array(
				'title'       => __( 'تنظیمات قالب ماهان', 'mahan' ),
				'description' => __( 'رنگ‌ها، فونت، هدر، فوتر، بلاگ و فروشگاه را از این‌جا تنظیم کنید.', 'mahan' ),
				'priority'    => 10,
			)
		);

		$this->section_colors( $wp_customize );
		$this->section_typography( $wp_customize );
		$this->section_layout( $wp_customize );
		$this->section_header( $wp_customize );
		$this->section_footer( $wp_customize );
		$this->section_blog( $wp_customize );
		$this->section_single( $wp_customize );

		if ( mahan_has_woocommerce() ) {
			$this->section_shop( $wp_customize );
		}

		$this->section_social( $wp_customize );
		$this->section_performance( $wp_customize );
	}

	/**
	 * Adds one control plus its setting.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 * @param string               $key          Option key.
	 * @param array                $args         Control arguments.
	 */
	private function add( $wp_customize, $key, array $args ) {
		$defaults = Mahan_Options::defaults();
		$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
		$type     = isset( $args['type'] ) ? $args['type'] : 'text';

		$wp_customize->add_setting(
			Mahan_Options::KEY . '[' . $key . ']',
			array(
				'type'              => 'theme_mod',
				'default'           => $default,
				'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => static function ( $value ) use ( $key ) {
					return Mahan_Options::sanitize( $key, $value );
				},
			)
		);

		$control_args = array(
			'label'       => $args['label'],
			'section'     => $args['section'],
			'settings'    => Mahan_Options::KEY . '[' . $key . ']',
			'description' => isset( $args['description'] ) ? $args['description'] : '',
			'priority'    => isset( $args['priority'] ) ? $args['priority'] : 10,
		);

		if ( isset( $args['active_callback'] ) ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		if ( 'color' === $type ) {
			$wp_customize->add_control(
				new WP_Customize_Color_Control( $wp_customize, 'mahan_' . $key, $control_args )
			);

			return;
		}

		$control_args['type'] = $type;

		if ( isset( $args['choices'] ) ) {
			$control_args['choices'] = $args['choices'];
		}

		if ( isset( $args['input_attrs'] ) ) {
			$control_args['input_attrs'] = $args['input_attrs'];
		}

		$wp_customize->add_control( 'mahan_' . $key, $control_args );
	}

	/**
	 * Colours and dark mode.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_colors( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_colors',
			array(
				'title'       => __( 'رنگ‌ها و پالت', 'mahan' ),
				'panel'       => 'mahan',
				'description' => __( 'یک پالت آماده انتخاب کنید یا رنگ‌ها را دستی تنظیم کنید.', 'mahan' ),
			)
		);

		$palette_choices = array();

		foreach ( Mahan_Options::palettes() as $key => $palette ) {
			$palette_choices[ $key ] = $palette['label'];
		}

		$this->add(
			$wp_customize,
			'palette',
			array(
				'label'       => __( 'پالت آماده', 'mahan' ),
				'section'     => 'mahan_colors',
				'type'        => 'select',
				'choices'     => $palette_choices,
				'description' => __( 'با انتخاب پالت، سه رنگ زیر به‌روزرسانی می‌شوند. پس از ذخیره صفحه را تازه کنید.', 'mahan' ),
			)
		);

		$colors = array(
			'color_primary'    => __( 'رنگ اصلی', 'mahan' ),
			'color_secondary'  => __( 'رنگ دوم', 'mahan' ),
			'color_accent'     => __( 'رنگ تأکید', 'mahan' ),
			'color_success'    => __( 'رنگ موفقیت', 'mahan' ),
			'color_danger'     => __( 'رنگ خطا', 'mahan' ),
			'color_text'       => __( 'رنگ متن', 'mahan' ),
			'color_muted'      => __( 'رنگ متن کم‌رنگ', 'mahan' ),
			'color_surface'    => __( 'رنگ کارت‌ها', 'mahan' ),
			'color_background' => __( 'رنگ پس‌زمینه', 'mahan' ),
			'color_border'     => __( 'رنگ خطوط', 'mahan' ),
		);

		foreach ( $colors as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'     => $label,
					'section'   => 'mahan_colors',
					'type'      => 'color',
					'transport' => 'postMessage',
				)
			);
		}

		$this->add(
			$wp_customize,
			'dark_mode',
			array(
				'label'   => __( 'حالت تاریک', 'mahan' ),
				'section' => 'mahan_colors',
				'type'    => 'select',
				'choices' => array(
					'off'    => __( 'غیرفعال', 'mahan' ),
					'toggle' => __( 'کلید دستی در هدر', 'mahan' ),
					'auto'   => __( 'خودکار بر اساس سیستم کاربر', 'mahan' ),
					'always' => __( 'همیشه تاریک', 'mahan' ),
				),
			)
		);

		$dark_colors = array(
			'dark_color_text'       => __( 'متن (تاریک)', 'mahan' ),
			'dark_color_muted'      => __( 'متن کم‌رنگ (تاریک)', 'mahan' ),
			'dark_color_surface'    => __( 'کارت‌ها (تاریک)', 'mahan' ),
			'dark_color_background' => __( 'پس‌زمینه (تاریک)', 'mahan' ),
			'dark_color_border'     => __( 'خطوط (تاریک)', 'mahan' ),
		);

		foreach ( $dark_colors as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'     => $label,
					'section'   => 'mahan_colors',
					'type'      => 'color',
					'transport' => 'postMessage',
				)
			);
		}
	}

	/**
	 * Typography.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_typography( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_typography',
			array(
				'title' => __( 'تایپوگرافی', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$font_choices = array();

		foreach ( Mahan_Options::fonts() as $key => $font ) {
			$font_choices[ $key ] = $font['label'];
		}

		$this->add(
			$wp_customize,
			'font_family',
			array(
				'label'   => __( 'فونت سایت', 'mahan' ),
				'section' => 'mahan_typography',
				'type'    => 'select',
				'choices' => $font_choices,
			)
		);

		$this->add(
			$wp_customize,
			'font_size_base',
			array(
				'label'       => __( 'اندازهٔ پایهٔ متن (پیکسل)', 'mahan' ),
				'section'     => 'mahan_typography',
				'type'        => 'number',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 13,
					'max'  => 20,
					'step' => 1,
				),
			)
		);

		$this->add(
			$wp_customize,
			'font_scale',
			array(
				'label'       => __( 'نسبت بزرگی عنوان‌ها', 'mahan' ),
				'section'     => 'mahan_typography',
				'type'        => 'number',
				'description' => __( 'عدد بزرگ‌تر یعنی تیترهای درشت‌تر. مقدار پیشنهادی ۱.۲۲ است.', 'mahan' ),
				'input_attrs' => array(
					'min'  => 1.05,
					'max'  => 1.45,
					'step' => 0.01,
				),
			)
		);

		$this->add(
			$wp_customize,
			'line_height',
			array(
				'label'       => __( 'ارتفاع خط', 'mahan' ),
				'section'     => 'mahan_typography',
				'type'        => 'number',
				'transport'   => 'postMessage',
				'description' => __( 'متن فارسی با ارتفاع خط ۱.۸ تا ۲ خواناتر است.', 'mahan' ),
				'input_attrs' => array(
					'min'  => 1.4,
					'max'  => 2.4,
					'step' => 0.05,
				),
			)
		);

		$weights = array(
			400 => __( 'معمولی', 'mahan' ),
			500 => __( 'نیمه‌ضخیم', 'mahan' ),
			600 => __( 'ضخیم', 'mahan' ),
			700 => __( 'خیلی ضخیم', 'mahan' ),
			800 => __( 'سیاه', 'mahan' ),
		);

		$this->add(
			$wp_customize,
			'heading_weight',
			array(
				'label'   => __( 'ضخامت عنوان‌ها', 'mahan' ),
				'section' => 'mahan_typography',
				'type'    => 'select',
				'choices' => $weights,
			)
		);

		$this->add(
			$wp_customize,
			'force_rtl',
			array(
				'label'       => __( 'راست‌چین اجباری', 'mahan' ),
				'section'     => 'mahan_typography',
				'type'        => 'checkbox',
				'description' => __( 'چیدمان سایت را حتی روی زبان‌های چپ‌چین هم راست‌چین نگه می‌دارد. اگر قالب را به زبان دیگری ترجمه کرده‌اید، این گزینه را خاموش کنید.', 'mahan' ),
			)
		);

		$this->add(
			$wp_customize,
			'persian_digits',
			array(
				'label'       => __( 'تبدیل اعداد به فارسی', 'mahan' ),
				'section'     => 'mahan_typography',
				'type'        => 'checkbox',
				'description' => __( 'تاریخ‌ها، قیمت‌ها و شمارنده‌ها با ارقام فارسی نمایش داده می‌شوند.', 'mahan' ),
			)
		);
	}

	/**
	 * Layout and spacing.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_layout( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_layout',
			array(
				'title' => __( 'چیدمان و فاصله‌ها', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$this->add(
			$wp_customize,
			'container_width',
			array(
				'label'       => __( 'عرض محتوا (پیکسل)', 'mahan' ),
				'section'     => 'mahan_layout',
				'type'        => 'number',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 960,
					'max'  => 1600,
					'step' => 20,
				),
			)
		);

		$this->add(
			$wp_customize,
			'radius',
			array(
				'label'       => __( 'گردی گوشه‌ها (پیکسل)', 'mahan' ),
				'section'     => 'mahan_layout',
				'type'        => 'number',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 32,
					'step' => 1,
				),
			)
		);

		$this->add(
			$wp_customize,
			'section_spacing',
			array(
				'label'       => __( 'فاصلهٔ بین بخش‌ها (پیکسل)', 'mahan' ),
				'section'     => 'mahan_layout',
				'type'        => 'number',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 40,
					'max'  => 160,
					'step' => 4,
				),
			)
		);

		$this->add(
			$wp_customize,
			'shadow_strength',
			array(
				'label'   => __( 'شدت سایه‌ها', 'mahan' ),
				'section' => 'mahan_layout',
				'type'    => 'select',
				'choices' => array(
					'none'   => __( 'بدون سایه', 'mahan' ),
					'soft'   => __( 'ملایم', 'mahan' ),
					'strong' => __( 'پررنگ', 'mahan' ),
				),
			)
		);

		foreach ( array(
			'sticky_sidebar'  => __( 'چسبیدن ستون کناری هنگام اسکرول', 'mahan' ),
			'page_transition' => __( 'انیمیشن ورود عناصر', 'mahan' ),
			'back_to_top'     => __( 'دکمهٔ بازگشت به بالا', 'mahan' ),
			'preloader'       => __( 'نمایش لودر هنگام باز شدن صفحه', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_layout',
					'type'    => 'checkbox',
				)
			);
		}
	}

	/**
	 * Header options.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_header( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_header',
			array(
				'title' => __( 'هدر', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$this->add(
			$wp_customize,
			'header_layout',
			array(
				'label'   => __( 'چیدمان هدر', 'mahan' ),
				'section' => 'mahan_header',
				'type'    => 'select',
				'choices' => array(
					'classic'  => __( 'کلاسیک (لوگو راست، منو وسط)', 'mahan' ),
					'centered' => __( 'وسط‌چین', 'mahan' ),
					'split'    => __( 'دو ردیفه', 'mahan' ),
					'minimal'  => __( 'مینیمال', 'mahan' ),
					'shop'     => __( 'فروشگاهی (با جستجوی بزرگ)', 'mahan' ),
				),
			)
		);

		foreach ( array(
			'header_sticky'        => __( 'هدر چسبان هنگام اسکرول', 'mahan' ),
			'header_sticky_shrink' => __( 'کوچک شدن هدر چسبان', 'mahan' ),
			'header_transparent'   => __( 'هدر شیشه‌ای روی صفحهٔ اصلی', 'mahan' ),
			'header_dark'          => __( 'هدر تیره', 'mahan' ),
			'header_search'        => __( 'دکمهٔ جستجو', 'mahan' ),
			'header_account'       => __( 'دکمهٔ حساب کاربری', 'mahan' ),
			'header_cart'          => __( 'دکمهٔ سبد خرید', 'mahan' ),
			'header_wishlist'      => __( 'دکمهٔ علاقه‌مندی‌ها', 'mahan' ),
			'mobile_bottom_bar'    => __( 'نوار پایین صفحه در موبایل', 'mahan' ),
			'topbar_enabled'       => __( 'نمایش نوار اعلان بالای هدر', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_header',
					'type'    => 'checkbox',
				)
			);
		}

		$this->add(
			$wp_customize,
			'topbar_text',
			array(
				'label'     => __( 'متن نوار اعلان', 'mahan' ),
				'section'   => 'mahan_header',
				'type'      => 'text',
				'transport' => 'postMessage',
			)
		);

		$this->add(
			$wp_customize,
			'topbar_phone',
			array(
				'label'   => __( 'شمارهٔ تماس نوار اعلان', 'mahan' ),
				'section' => 'mahan_header',
				'type'    => 'text',
			)
		);

		$this->add(
			$wp_customize,
			'mobile_menu_style',
			array(
				'label'   => __( 'سبک منوی موبایل', 'mahan' ),
				'section' => 'mahan_header',
				'type'    => 'select',
				'choices' => array(
					'drawer'     => __( 'کشویی از کنار', 'mahan' ),
					'fullscreen' => __( 'تمام‌صفحه', 'mahan' ),
				),
			)
		);
	}

	/**
	 * Footer options.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_footer( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_footer',
			array(
				'title' => __( 'فوتر', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$this->add(
			$wp_customize,
			'footer_layout',
			array(
				'label'   => __( 'چیدمان فوتر', 'mahan' ),
				'section' => 'mahan_footer',
				'type'    => 'select',
				'choices' => array(
					'columns'  => __( 'ستونی', 'mahan' ),
					'compact'  => __( 'فشرده', 'mahan' ),
					'centered' => __( 'وسط‌چین', 'mahan' ),
					'shop'     => __( 'فروشگاهی (با نمادها)', 'mahan' ),
				),
			)
		);

		$this->add(
			$wp_customize,
			'footer_columns',
			array(
				'label'       => __( 'تعداد ستون‌ها', 'mahan' ),
				'section'     => 'mahan_footer',
				'type'        => 'number',
				'description' => __( 'پس از تغییر، ابزارک‌های ستون‌های تازه را در بخش ابزارک‌ها پر کنید.', 'mahan' ),
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 6,
					'step' => 1,
				),
			)
		);

		foreach ( array(
			'footer_dark'       => __( 'فوتر تیره', 'mahan' ),
			'footer_newsletter' => __( 'نوار خبرنامه', 'mahan' ),
			'footer_badges'     => __( 'نمایش نمادهای اعتماد', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_footer',
					'type'    => 'checkbox',
				)
			);
		}

		$this->add(
			$wp_customize,
			'footer_about_title',
			array(
				'label'   => __( 'عنوان بخش دربارهٔ فوتر', 'mahan' ),
				'section' => 'mahan_footer',
				'type'    => 'text',
			)
		);

		$this->add(
			$wp_customize,
			'footer_about_text',
			array(
				'label'   => __( 'متن دربارهٔ فوتر', 'mahan' ),
				'section' => 'mahan_footer',
				'type'    => 'textarea',
			)
		);

		$this->add(
			$wp_customize,
			'footer_copyright',
			array(
				'label'       => __( 'متن کپی‌رایت', 'mahan' ),
				'section'     => 'mahan_footer',
				'type'        => 'textarea',
				'description' => __( 'می‌توانید از {site} و {year} استفاده کنید.', 'mahan' ),
			)
		);
	}

	/**
	 * Blog archive options.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_blog( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_blog',
			array(
				'title' => __( 'بلاگ و آرشیو', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$this->add(
			$wp_customize,
			'blog_layout',
			array(
				'label'   => __( 'چیدمان آرشیو', 'mahan' ),
				'section' => 'mahan_blog',
				'type'    => 'select',
				'choices' => array(
					'grid'      => __( 'شبکه‌ای', 'mahan' ),
					'list'      => __( 'فهرستی', 'mahan' ),
					'masonry'   => __( 'آجری', 'mahan' ),
					'magazine'  => __( 'مجله‌ای', 'mahan' ),
				),
			)
		);

		$this->add(
			$wp_customize,
			'blog_columns',
			array(
				'label'       => __( 'تعداد ستون‌ها', 'mahan' ),
				'section'     => 'mahan_blog',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 4,
					'step' => 1,
				),
			)
		);

		$this->add(
			$wp_customize,
			'blog_sidebar',
			array(
				'label'   => __( 'ستون کناری', 'mahan' ),
				'section' => 'mahan_blog',
				'type'    => 'select',
				'choices' => array(
					'right' => __( 'راست', 'mahan' ),
					'left'  => __( 'چپ', 'mahan' ),
					'none'  => __( 'بدون ستون کناری', 'mahan' ),
				),
			)
		);

		$this->add(
			$wp_customize,
			'blog_excerpt_length',
			array(
				'label'       => __( 'تعداد کلمات خلاصه', 'mahan' ),
				'section'     => 'mahan_blog',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 8,
					'max'  => 80,
					'step' => 1,
				),
			)
		);

		$this->add(
			$wp_customize,
			'blog_pagination',
			array(
				'label'   => __( 'نوع صفحه‌بندی', 'mahan' ),
				'section' => 'mahan_blog',
				'type'    => 'select',
				'choices' => array(
					'numbers'  => __( 'شماره‌گذاری', 'mahan' ),
					'loadmore' => __( 'دکمهٔ نمایش بیشتر', 'mahan' ),
					'infinite' => __( 'بارگذاری خودکار', 'mahan' ),
				),
			)
		);

		foreach ( array(
			'blog_show_author'       => __( 'نمایش نویسنده', 'mahan' ),
			'blog_show_date'         => __( 'نمایش تاریخ', 'mahan' ),
			'blog_show_category'     => __( 'نمایش دسته‌بندی', 'mahan' ),
			'blog_show_reading_time' => __( 'نمایش زمان مطالعه', 'mahan' ),
			'blog_show_views'        => __( 'نمایش تعداد بازدید', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_blog',
					'type'    => 'checkbox',
				)
			);
		}
	}

	/**
	 * Single post options.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_single( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_single',
			array(
				'title' => __( 'صفحهٔ نوشته', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$this->add(
			$wp_customize,
			'single_layout',
			array(
				'label'   => __( 'چیدمان', 'mahan' ),
				'section' => 'mahan_single',
				'type'    => 'select',
				'choices' => array(
					'sidebar' => __( 'با ستون کناری', 'mahan' ),
					'narrow'  => __( 'باریک و متمرکز', 'mahan' ),
					'wide'    => __( 'تمام‌عرض', 'mahan' ),
				),
			)
		);

		$this->add(
			$wp_customize,
			'single_sidebar',
			array(
				'label'   => __( 'سمت ستون کناری', 'mahan' ),
				'section' => 'mahan_single',
				'type'    => 'select',
				'choices' => array(
					'right' => __( 'راست', 'mahan' ),
					'left'  => __( 'چپ', 'mahan' ),
					'none'  => __( 'بدون ستون کناری', 'mahan' ),
				),
			)
		);

		foreach ( array(
			'single_share'        => __( 'دکمه‌های اشتراک‌گذاری', 'mahan' ),
			'single_toc'          => __( 'فهرست مطالب خودکار', 'mahan' ),
			'single_author_box'   => __( 'باکس نویسنده', 'mahan' ),
			'single_related'      => __( 'نوشته‌های مرتبط', 'mahan' ),
			'single_progress_bar' => __( 'نوار پیشرفت مطالعه', 'mahan' ),
			'single_prev_next'    => __( 'پیمایش نوشتهٔ قبلی و بعدی', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_single',
					'type'    => 'checkbox',
				)
			);
		}

		$this->add(
			$wp_customize,
			'single_related_count',
			array(
				'label'       => __( 'تعداد نوشته‌های مرتبط', 'mahan' ),
				'section'     => 'mahan_single',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 2,
					'max'  => 8,
					'step' => 1,
				),
			)
		);
	}

	/**
	 * WooCommerce options.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_shop( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_shop',
			array(
				'title' => __( 'فروشگاه', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$this->add(
			$wp_customize,
			'shop_columns',
			array(
				'label'       => __( 'تعداد ستون‌های محصولات', 'mahan' ),
				'section'     => 'mahan_shop',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 2,
					'max'  => 6,
					'step' => 1,
				),
			)
		);

		$this->add(
			$wp_customize,
			'shop_per_page',
			array(
				'label'       => __( 'تعداد محصول در هر صفحه', 'mahan' ),
				'section'     => 'mahan_shop',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 4,
					'max'  => 48,
					'step' => 1,
				),
			)
		);

		$this->add(
			$wp_customize,
			'shop_sidebar',
			array(
				'label'   => __( 'ستون کناری فروشگاه', 'mahan' ),
				'section' => 'mahan_shop',
				'type'    => 'select',
				'choices' => array(
					'right' => __( 'راست', 'mahan' ),
					'left'  => __( 'چپ', 'mahan' ),
					'none'  => __( 'بدون ستون کناری', 'mahan' ),
				),
			)
		);

		$this->add(
			$wp_customize,
			'shop_card_style',
			array(
				'label'   => __( 'سبک کارت محصول', 'mahan' ),
				'section' => 'mahan_shop',
				'type'    => 'select',
				'choices' => array(
					'modern'  => __( 'مدرن', 'mahan' ),
					'classic' => __( 'کلاسیک', 'mahan' ),
					'minimal' => __( 'مینیمال', 'mahan' ),
					'overlay' => __( 'روی تصویر', 'mahan' ),
				),
			)
		);

		$this->add(
			$wp_customize,
			'single_product_layout',
			array(
				'label'   => __( 'چیدمان صفحهٔ محصول', 'mahan' ),
				'section' => 'mahan_shop',
				'type'    => 'select',
				'choices' => array(
					'modern'  => __( 'مدرن', 'mahan' ),
					'classic' => __( 'کلاسیک', 'mahan' ),
					'wide'    => __( 'تمام‌عرض', 'mahan' ),
				),
			)
		);

		foreach ( array(
			'shop_quick_view'         => __( 'مشاهدهٔ سریع محصول', 'mahan' ),
			'shop_ajax_add_to_cart'   => __( 'افزودن به سبد بدون بارگذاری صفحه', 'mahan' ),
			'shop_wishlist'           => __( 'فهرست علاقه‌مندی‌ها', 'mahan' ),
			'shop_badge_discount'     => __( 'نمایش درصد تخفیف', 'mahan' ),
			'shop_hover_gallery'      => __( 'تعویض تصویر با هاور', 'mahan' ),
			'shop_stock_bar'          => __( 'نوار موجودی کم', 'mahan' ),
			'shop_sticky_add_to_cart' => __( 'نوار خرید چسبان در صفحهٔ محصول', 'mahan' ),
			'shop_trust_badges'       => __( 'نمادهای اعتماد در صفحهٔ محصول', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_shop',
					'type'    => 'checkbox',
				)
			);
		}
	}

	/**
	 * Social and contact details.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_social( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_social',
			array(
				'title' => __( 'شبکه‌های اجتماعی و تماس', 'mahan' ),
				'panel' => 'mahan',
			)
		);

		$networks = array(
			'social_instagram' => __( 'اینستاگرام', 'mahan' ),
			'social_telegram'  => __( 'تلگرام', 'mahan' ),
			'social_whatsapp'  => __( 'واتساپ', 'mahan' ),
			'social_linkedin'  => __( 'لینکدین', 'mahan' ),
			'social_twitter'   => __( 'ایکس (توییتر)', 'mahan' ),
			'social_youtube'   => __( 'یوتیوب', 'mahan' ),
			'social_aparat'    => __( 'آپارات', 'mahan' ),
		);

		foreach ( $networks as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_social',
					'type'    => 'url',
				)
			);
		}

		$this->add(
			$wp_customize,
			'contact_phone',
			array(
				'label'   => __( 'شمارهٔ تماس', 'mahan' ),
				'section' => 'mahan_social',
				'type'    => 'text',
			)
		);

		$this->add(
			$wp_customize,
			'contact_email',
			array(
				'label'   => __( 'ایمیل', 'mahan' ),
				'section' => 'mahan_social',
				'type'    => 'text',
			)
		);

		$this->add(
			$wp_customize,
			'contact_address',
			array(
				'label'   => __( 'نشانی', 'mahan' ),
				'section' => 'mahan_social',
				'type'    => 'textarea',
			)
		);
	}

	/**
	 * Performance switches.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	private function section_performance( $wp_customize ) {
		$wp_customize->add_section(
			'mahan_performance',
			array(
				'title'       => __( 'کارایی', 'mahan' ),
				'panel'       => 'mahan',
				'description' => __( 'گزینه‌هایی برای سبک‌تر شدن صفحه‌ها.', 'mahan' ),
			)
		);

		foreach ( array(
			'lazy_load'      => __( 'بارگذاری تنبل تصاویر', 'mahan' ),
			'disable_emoji'  => __( 'غیرفعال کردن اسکریپت ایموجی وردپرس', 'mahan' ),
			'disable_embeds' => __( 'غیرفعال کردن اسکریپت embed وردپرس', 'mahan' ),
			'preload_fonts'  => __( 'پیش‌بارگذاری فونت‌ها', 'mahan' ),
		) as $key => $label ) {
			$this->add(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'mahan_performance',
					'type'    => 'checkbox',
				)
			);
		}
	}

	/**
	 * Loads the live-preview script.
	 */
	public function preview_script() {
		wp_enqueue_script(
			'mahan-customizer-preview',
			MAHAN_URI . 'assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			MAHAN_VERSION,
			true
		);
	}

	/**
	 * Small style tweaks for the customizer controls.
	 */
	public function controls_style() {
		wp_add_inline_style(
			'customize-controls',
			'#sub-accordion-panel-mahan .customize-control-title{font-weight:600;}'
		);
	}
}
