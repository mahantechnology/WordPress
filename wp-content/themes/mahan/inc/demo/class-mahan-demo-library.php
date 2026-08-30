<?php
/**
 * The catalogue of bundled starter sites.
 *
 * Each pack lives in inc/demo/data/<id>.php and returns an array describing the
 * palette, options, pages, menus, widgets and sample content for one niche.
 * Nothing is fetched from a remote server, so importing works offline.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Demo_Library {

	/**
	 * Loaded packs, keyed by demo ID.
	 *
	 * @var array<string,array>
	 */
	private static $loaded = array();

	/**
	 * The demo list, without loading each pack's page content.
	 *
	 * @return array<string,array>
	 */
	public static function index() {
		$demos = array(
			'shop'       => array(
				'label'       => __( 'فروشگاه اینترنتی', 'mahan' ),
				'description' => __( 'فروشگاه چندمنظوره با اسلایدر، پیشنهاد شگفت‌انگیز، دسته‌بندی‌ها و بنرهای تبلیغاتی.', 'mahan' ),
				'icon'        => 'cart',
				'palette'     => 'crimson',
				'requires'    => array( 'elementor', 'woocommerce' ),
				'tags'        => array( __( 'فروشگاهی', 'mahan' ), __( 'ووکامرس', 'mahan' ) ),
			),
			'corporate'  => array(
				'label'       => __( 'شرکتی و سازمانی', 'mahan' ),
				'description' => __( 'معرفی شرکت، خدمات، تیم، نمونه‌کارها و فرم تماس با ظاهری رسمی و مطمئن.', 'mahan' ),
				'icon'        => 'building',
				'palette'     => 'midnight',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'شرکتی', 'mahan' ), __( 'خدماتی', 'mahan' ) ),
			),
			'magazine'   => array(
				'label'       => __( 'مجله و خبرگزاری', 'mahan' ),
				'description' => __( 'صفحهٔ اصلی مجله‌ای با تیتر یک، تب دسته‌بندی‌ها، پربازدیدها و نوار خبر.', 'mahan' ),
				'icon'        => 'book',
				'palette'     => 'ocean',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'خبری', 'mahan' ), __( 'وبلاگ', 'mahan' ) ),
			),
			'portfolio'  => array(
				'label'       => __( 'شخصی و نمونه‌کار', 'mahan' ),
				'description' => __( 'رزومهٔ آنلاین با معرفی، مهارت‌ها، نمونه‌کارها و راه‌های تماس.', 'mahan' ),
				'icon'        => 'camera',
				'palette'     => 'graphite',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'شخصی', 'mahan' ), __( 'نمونه‌کار', 'mahan' ) ),
			),
			'education'  => array(
				'label'       => __( 'آموزشگاه و دوره‌ها', 'mahan' ),
				'description' => __( 'معرفی دوره‌ها، مدرسان، نظرات هنرجویان و فرم ثبت‌نام.', 'mahan' ),
				'icon'        => 'graduation',
				'palette'     => 'royal',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'آموزشی', 'mahan' ), __( 'دوره', 'mahan' ) ),
			),
			'medical'    => array(
				'label'       => __( 'پزشکی و کلینیک', 'mahan' ),
				'description' => __( 'معرفی کلینیک، تخصص‌ها، پزشکان، نوبت‌دهی و پرسش‌های متداول.', 'mahan' ),
				'icon'        => 'stethoscope',
				'palette'     => 'turquoise',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'پزشکی', 'mahan' ), __( 'سلامت', 'mahan' ) ),
			),
			'restaurant' => array(
				'label'       => __( 'رستوران و کافه', 'mahan' ),
				'description' => __( 'منوی غذا، گالری، رزرو میز و معرفی سرآشپز با فضایی گرم و اشتهاآور.', 'mahan' ),
				'icon'        => 'utensils',
				'palette'     => 'saffron',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'رستوران', 'mahan' ), __( 'کافه', 'mahan' ) ),
			),
			'realestate' => array(
				'label'       => __( 'املاک و مستغلات', 'mahan' ),
				'description' => __( 'نمایش ملک‌ها، جستجوی پیشرفته، معرفی مشاوران و محله‌ها.', 'mahan' ),
				'icon'        => 'home',
				'palette'     => 'emerald',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'املاک', 'mahan' ) ),
			),
			'startup'    => array(
				'label'       => __( 'استارتاپ و اپلیکیشن', 'mahan' ),
				'description' => __( 'صفحهٔ فرود محصول با ویژگی‌ها، پلن‌های قیمتی، آمار و فراخوان نصب.', 'mahan' ),
				'icon'        => 'lightning',
				'palette'     => 'sakura',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'استارتاپ', 'mahan' ), __( 'اپلیکیشن', 'mahan' ) ),
			),
			'agency'     => array(
				'label'       => __( 'آژانس دیجیتال', 'mahan' ),
				'description' => __( 'معرفی آژانس با نمونه‌کارهای چشم‌نواز، فرایند کار و نظرات مشتریان.', 'mahan' ),
				'icon'        => 'sparkles',
				'palette'     => 'sunset',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'آژانس', 'mahan' ), __( 'خلاق', 'mahan' ) ),
			),
			'gym'        => array(
				'label'       => __( 'باشگاه و تناسب اندام', 'mahan' ),
				'description' => __( 'برنامهٔ کلاس‌ها، معرفی مربیان، پلن‌های عضویت و شمارش دستاوردها.', 'mahan' ),
				'icon'        => 'lightning',
				'palette'     => 'midnight',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'ورزشی', 'mahan' ), __( 'باشگاه', 'mahan' ), __( 'سلامت', 'mahan' ) ),
			),
			'travel'     => array(
				'label'       => __( 'آژانس گردشگری', 'mahan' ),
				'description' => __( 'تورهای داخلی و خارجی، مقصدهای محبوب، گالری سفر و فرم رزرو.', 'mahan' ),
				'icon'        => 'globe',
				'palette'     => 'turquoise',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'گردشگری', 'mahan' ), __( 'سفر', 'mahan' ), __( 'خدماتی', 'mahan' ) ),
			),
			'law'        => array(
				'label'       => __( 'دفتر وکالت و حقوقی', 'mahan' ),
				'description' => __( 'زمینه‌های تخصص، معرفی وکلا، پرونده‌های موفق و درخواست مشاوره.', 'mahan' ),
				'icon'        => 'shield',
				'palette'     => 'graphite',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'حقوقی', 'mahan' ), __( 'وکالت', 'mahan' ), __( 'خدماتی', 'mahan' ) ),
			),
			'building'   => array(
				'label'       => __( 'ساختمانی و معماری', 'mahan' ),
				'description' => __( 'پروژه‌های اجراشده، خدمات پیمانکاری، مراحل کار و تیم مهندسی.', 'mahan' ),
				'icon'        => 'building',
				'palette'     => 'saffron',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'ساختمانی', 'mahan' ), __( 'معماری', 'mahan' ), __( 'شرکتی', 'mahan' ) ),
			),
			'beauty'     => array(
				'label'       => __( 'سالن زیبایی و اسپا', 'mahan' ),
				'description' => __( 'خدمات و تعرفه‌ها، نوبت‌دهی آنلاین، گالری کارها و معرفی متخصص‌ها.', 'mahan' ),
				'icon'        => 'heart',
				'palette'     => 'sakura',
				'requires'    => array( 'elementor' ),
				'tags'        => array( __( 'زیبایی', 'mahan' ), __( 'اسپا', 'mahan' ), __( 'خدماتی', 'mahan' ) ),
			),
		);

		foreach ( $demos as $id => $demo ) {
			$demos[ $id ]['id']      = $id;
			$demos[ $id ]['preview'] = MAHAN_URI . 'assets/images/demos/' . $id . '.svg';
		}

		/**
		 * Filters the bundled starter-site catalogue.
		 *
		 * @param array $demos Demo definitions keyed by ID.
		 */
		return apply_filters( 'mahan_demo_index', $demos );
	}

	/**
	 * Whether a demo ID exists.
	 *
	 * @param string $id Demo ID.
	 * @return bool
	 */
	public static function exists( $id ) {
		return array_key_exists( $id, self::index() );
	}

	/**
	 * Loads one pack, including its page content.
	 *
	 * @param string $id Demo ID.
	 * @return array|null
	 */
	public static function get( $id ) {
		if ( ! self::exists( $id ) ) {
			return null;
		}

		if ( isset( self::$loaded[ $id ] ) ) {
			return self::$loaded[ $id ];
		}

		$index = self::index();
		$path  = MAHAN_INC . 'demo/data/' . $id . '.php';

		$pack = file_exists( $path ) ? require $path : array();
		$pack = is_array( $pack ) ? $pack : array();

		self::$loaded[ $id ] = array_merge( $index[ $id ], $pack );

		return self::$loaded[ $id ];
	}

	/**
	 * Which required plugins are missing for a demo.
	 *
	 * @param string $id Demo ID.
	 * @return array<string,array> Plugin definitions keyed by slug.
	 */
	public static function missing_plugins( $id ) {
		$index = self::index();

		if ( ! isset( $index[ $id ] ) ) {
			return array();
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$recommended = Mahan_Plugin_Notice::recommended();
		$missing     = array();

		foreach ( $index[ $id ]['requires'] as $slug ) {
			if ( isset( $recommended[ $slug ] ) && ! is_plugin_active( $recommended[ $slug ]['file'] ) ) {
				$missing[ $slug ] = $recommended[ $slug ];
			}
		}

		return $missing;
	}

	/**
	 * The shared sample copy demos reuse for posts and testimonials.
	 *
	 * @return array
	 */
	public static function shared_content() {
		return array(
			'posts'        => array(
				array(
					'title'   => __( 'چگونه سرعت سایت وردپرسی را دو برابر کنیم؟', 'mahan' ),
					'excerpt' => __( 'سرعت، اولین چیزی است که بازدیدکننده حس می‌کند. در این نوشته هفت گام عملی برای سبک‌تر کردن سایت را مرور می‌کنیم.', 'mahan' ),
					'content' => __( "سرعت بارگذاری، هم بر تجربهٔ کاربر اثر می‌گذارد و هم بر رتبهٔ سایت در موتورهای جستجو.\n\n<h2>۱. تصاویر را بهینه کنید</h2>\nبیشترین حجم یک صفحهٔ معمولی را تصاویر می‌سازند. استفاده از فرمت WebP و اندازهٔ درست، به‌تنهایی می‌تواند نیمی از حجم صفحه را کم کند.\n\n<h2>۲. افزونه‌های بی‌استفاده را حذف کنید</h2>\nهر افزونهٔ فعال، مقداری کد به هر درخواست اضافه می‌کند. فهرست افزونه‌ها را هر چند وقت یک‌بار مرور کنید.\n\n<h2>۳. از کش استفاده کنید</h2>\nکش صفحه باعث می‌شود وردپرس برای هر بازدید، صفحه را از نو نسازد.", 'mahan' ),
					'category'=> __( 'آموزش', 'mahan' ),
				),
				array(
					'title'   => __( 'راهنمای انتخاب رنگ برای وب‌سایت فارسی', 'mahan' ),
					'excerpt' => __( 'رنگ‌ها پیش از متن دیده می‌شوند. در این راهنما یاد می‌گیرید چطور یک پالت هماهنگ و خوانا بسازید.', 'mahan' ),
					'content' => __( "انتخاب رنگ فقط سلیقه نیست؛ یک تصمیم کارکردی است که بر خوانایی و اعتماد اثر می‌گذارد.\n\n<h2>از یک رنگ اصلی شروع کنید</h2>\nیک رنگ را به‌عنوان رنگ برند انتخاب کنید و بقیهٔ پالت را حول آن بسازید.\n\n<h2>تضاد کافی را رعایت کنید</h2>\nنسبت تضاد متن و پس‌زمینه باید دست‌کم ۴.۵ به ۱ باشد تا متن برای همه خوانا بماند.", 'mahan' ),
					'category'=> __( 'طراحی', 'mahan' ),
				),
				array(
					'title'   => __( 'پنج اشتباه رایج در طراحی صفحهٔ فرود', 'mahan' ),
					'excerpt' => __( 'صفحهٔ فرود خوب یک کار را عالی انجام می‌دهد. این پنج اشتباه، بیشترین آسیب را به نرخ تبدیل می‌زنند.', 'mahan' ),
					'content' => __( "یک صفحهٔ فرود موفق، بازدیدکننده را بدون سردرگمی به یک اقدام مشخص می‌رساند.\n\n<h2>۱. چند فراخوان هم‌زمان</h2>\nوقتی از کاربر سه کار متفاوت می‌خواهید، احتمالاً هیچ‌کدام را انجام نمی‌دهد.\n\n<h2>۲. متن‌های طولانی بدون سرتیتر</h2>\nکاربر ابتدا صفحه را اسکن می‌کند، بعد می‌خواند.", 'mahan' ),
					'category'=> __( 'بازاریابی', 'mahan' ),
				),
				array(
					'title'   => __( 'سئوی فارسی: از کلیدواژه تا ساختار محتوا', 'mahan' ),
					'excerpt' => __( 'بهینه‌سازی برای زبان فارسی نکته‌های خاص خودش را دارد؛ از نیم‌فاصله تا ساختار نشانی‌ها.', 'mahan' ),
					'content' => __( "موتورهای جستجو متن فارسی را می‌فهمند، اما رعایت چند نکته کار آن‌ها را ساده‌تر می‌کند.\n\n<h2>نشانی‌های خوانا</h2>\nنشانی فارسی مشکلی ندارد، اما نشانی کوتاه و بدون کاراکترهای اضافی بهتر است.\n\n<h2>نیم‌فاصله را جدی بگیرید</h2>\nنوشتن درست «می‌شود» به‌جای «می شود» هم بر خوانایی و هم بر تطبیق کلیدواژه اثر دارد.", 'mahan' ),
					'category'=> __( 'سئو', 'mahan' ),
				),
				array(
					'title'   => __( 'چک‌لیست راه‌اندازی فروشگاه اینترنتی', 'mahan' ),
					'excerpt' => __( 'پیش از انتشار فروشگاه، این فهرست را یک‌بار مرور کنید تا چیزی از قلم نیفتد.', 'mahan' ),
					'content' => __( "راه‌اندازی فروشگاه فقط افزودن محصول نیست.\n\n<h2>درگاه پرداخت را آزمایش کنید</h2>\nیک خرید واقعی با مبلغ کم انجام دهید تا از سلامت مسیر پرداخت مطمئن شوید.\n\n<h2>هزینهٔ ارسال را شفاف بنویسید</h2>\nهزینهٔ پنهان، رایج‌ترین دلیل رها شدن سبد خرید است.", 'mahan' ),
					'category'=> __( 'فروشگاه', 'mahan' ),
				),
				array(
					'title'   => __( 'دسترس‌پذیری وب: نوشتن برای همه', 'mahan' ),
					'excerpt' => __( 'سایت دسترس‌پذیر فقط برای کاربران کم‌توان مفید نیست؛ تجربهٔ همه را بهتر می‌کند.', 'mahan' ),
					'content' => __( "دسترس‌پذیری یعنی هر کسی، با هر ابزاری، بتواند از سایت شما استفاده کند.\n\n<h2>متن جایگزین تصاویر</h2>\nهر تصویر معنادار باید متن جایگزین داشته باشد.\n\n<h2>پیمایش با صفحه‌کلید</h2>\nتمام بخش‌های تعاملی باید تنها با کلید Tab قابل استفاده باشند.", 'mahan' ),
					'category'=> __( 'آموزش', 'mahan' ),
				),
			),
			'testimonials' => array(
				array(
					'name'   => __( 'سارا محمدی', 'mahan' ),
					'role'   => __( 'مدیر بازاریابی، هم‌آوا', 'mahan' ),
					'text'   => __( 'از روزی که سایت‌مان را با ماهان بازطراحی کردیم، مدت ماندگاری کاربران نزدیک به دو برابر شده است.', 'mahan' ),
					'rating' => 5,
				),
				array(
					'name'   => __( 'امیر رستمی', 'mahan' ),
					'role'   => __( 'بنیان‌گذار، کالانو', 'mahan' ),
					'text'   => __( 'راه‌اندازی فروشگاه کمتر از یک هفته طول کشید و تیم پشتیبانی در هر مرحله همراه‌مان بود.', 'mahan' ),
					'rating' => 5,
				),
				array(
					'name'   => __( 'نگار کریمی', 'mahan' ),
					'role'   => __( 'طراح محصول', 'mahan' ),
					'text'   => __( 'المان‌های آمادهٔ قالب دست ما را برای ساختن صفحه‌های تازه کاملاً باز گذاشت؛ بدون یک خط کد.', 'mahan' ),
					'rating' => 4.5,
				),
				array(
					'name'   => __( 'حسین طاهری', 'mahan' ),
					'role'   => __( 'مدیر فنی، دیتاپل', 'mahan' ),
					'text'   => __( 'کد قالب تمیز و مستند است؛ توسعهٔ قالب فرزند برای نیازهای خاص‌مان خیلی ساده بود.', 'mahan' ),
					'rating' => 5,
				),
			),
		);
	}
}
