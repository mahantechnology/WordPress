<?php
/**
 * The catalogue of Elementor elements the theme ships, grouped for the panel.
 *
 * The list is described here rather than read from Elementor so the Elements
 * screen still works with Elementor deactivated — someone deciding whether to
 * install it should be able to see what they would get.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Elements_Catalog {

	/**
	 * Every element, grouped by what it is for.
	 *
	 * @return array<string,array{label:string,icon:string,items:array}>
	 */
	public static function all() {
		$catalog = array(
			'hero'    => array(
				'label' => __( 'سربرگ و فراخوان', 'mahan' ),
				'icon'  => 'sparkles',
				'items' => array(
					'hero-banner'     => array( __( 'بنر سربرگ (هیرو)', 'mahan' ), __( 'عنوان، توضیح، دو دکمه، آمار و تصویر.', 'mahan' ) ),
					'hero-slider'     => array( __( 'اسلایدر تمام‌عرض', 'mahan' ), __( 'اسلایدهای تصویری با متن و دکمه.', 'mahan' ) ),
					'cta-banner'      => array( __( 'بنر فراخوان', 'mahan' ), __( 'چهار سبک: گرادیانی، یک‌رنگ، خط‌دار و شیشه‌ای.', 'mahan' ) ),
					'section-heading' => array( __( 'سربرگ بخش', 'mahan' ), __( 'عنوان با کلمات برجسته و خط تزئینی.', 'mahan' ) ),
					'typewriter'      => array( __( 'عنوان تایپی', 'mahan' ), __( 'عبارت‌های متغیر با افکت تایپ.', 'mahan' ) ),
					'marquee'         => array( __( 'نوار متن متحرک', 'mahan' ), __( 'نوار پیوستهٔ راست‌به‌چپ یا برعکس.', 'mahan' ) ),
					'divider'         => array( __( 'جداکنندهٔ تزئینی', 'mahan' ), __( 'پنج سبک، با آیکون میانی اختیاری.', 'mahan' ) ),
					'button-group'    => array( __( 'گروه دکمه‌ها', 'mahan' ), __( 'چند دکمه با سبک و آیکون مستقل.', 'mahan' ) ),
				),
			),
			'content' => array(
				'label' => __( 'معرفی و محتوا', 'mahan' ),
				'icon'  => 'layers',
				'items' => array(
					'icon-box'      => array( __( 'باکس آیکون‌دار', 'mahan' ), __( 'پنج سبک نمایش ویژگی‌ها.', 'mahan' ) ),
					'image-box'     => array( __( 'باکس تصویری', 'mahan' ), __( 'تصویر بالا، روی تصویر یا کنار متن.', 'mahan' ) ),
					'feature-grid'  => array( __( 'فهرست ویژگی‌ها با تصویر', 'mahan' ), __( 'ویژگی‌های کلیدی کنار یک تصویر.', 'mahan' ) ),
					'process-steps' => array( __( 'مراحل کار', 'mahan' ), __( 'گام‌های شماره‌دار با خط اتصال.', 'mahan' ) ),
					'timeline'      => array( __( 'خط زمانی', 'mahan' ), __( 'یک‌درمیان یا یک‌طرفه.', 'mahan' ) ),
					'tabs'          => array( __( 'تب‌های محتوا', 'mahan' ), __( 'افقی یا عمودی، با پیمایش صفحه‌کلید.', 'mahan' ) ),
					'faq-accordion' => array( __( 'پرسش‌های متداول', 'mahan' ), __( 'به‌همراه دادهٔ ساختاریافتهٔ FAQ برای گوگل.', 'mahan' ) ),
					'card-flip'     => array( __( 'کارت چرخشی', 'mahan' ), __( 'با نگه داشتن نشانگر برمی‌گردد.', 'mahan' ) ),
					'compare-table' => array( __( 'جدول مقایسه', 'mahan' ), __( 'مقایسهٔ پلن‌ها یا محصولات.', 'mahan' ) ),
					'before-after'  => array( __( 'مقایسهٔ قبل و بعد', 'mahan' ), __( 'دستگیرهٔ کشویی روی دو تصویر.', 'mahan' ) ),
				),
			),
			'proof'   => array(
				'label' => __( 'اعتماد و آمار', 'mahan' ),
				'icon'  => 'star',
				'items' => array(
					'stats-counter'         => array( __( 'شمارندهٔ آماری', 'mahan' ), __( 'اعداد با انیمیشن شمارش و ارقام فارسی.', 'mahan' ) ),
					'progress-bars'         => array( __( 'نوارهای مهارت', 'mahan' ), __( 'درصدها با انیمیشن ورود.', 'mahan' ) ),
					'testimonial-carousel'  => array( __( 'نظرات مشتریان', 'mahan' ), __( 'دستی یا از بخش «نظرات مشتریان».', 'mahan' ) ),
					'team-grid'             => array( __( 'اعضای تیم', 'mahan' ), __( 'سه سبک، با شبکه‌های اجتماعی.', 'mahan' ) ),
					'logo-carousel'         => array( __( 'لوگوی مشتریان', 'mahan' ), __( 'اسلایدر با حالت سیاه‌وسفید.', 'mahan' ) ),
					'pricing-table'         => array( __( 'جدول قیمت‌گذاری', 'mahan' ), __( 'با پلن پیشنهادی و ویژگی‌های فعال/غیرفعال.', 'mahan' ) ),
				),
			),
			'media'   => array(
				'label' => __( 'رسانه', 'mahan' ),
				'icon'  => 'camera',
				'items' => array(
					'gallery-grid'   => array( __( 'گالری تصاویر', 'mahan' ), __( 'شبکه‌ای، آجری یا موزائیکی با نمای بزرگ.', 'mahan' ) ),
					'video-popup'    => array( __( 'ویدیو با پخش‌کنندهٔ پاپ‌آپ', 'mahan' ), __( 'آپارات، یوتیوب یا فایل mp4.', 'mahan' ) ),
					'lottie-player'  => array( __( 'انیمیشن لوتی', 'mahan' ), __( 'با تصویر جایگزین در نبود پخش‌کننده.', 'mahan' ) ),
					'map-embed'      => array( __( 'نقشه', 'mahan' ), __( 'OpenStreetMap بدون نیاز به کلید، یا کد دلخواه.', 'mahan' ) ),
				),
			),
			'posts'   => array(
				'label' => __( 'نوشته‌ها و آرشیو', 'mahan' ),
				'icon'  => 'book',
				'items' => array(
					'post-grid'      => array( __( 'شبکهٔ نوشته‌ها', 'mahan' ), __( 'سه سبک کارت با فیلتر دسته و مرتب‌سازی.', 'mahan' ) ),
					'post-carousel'  => array( __( 'اسلایدر نوشته‌ها', 'mahan' ), __( 'همان کارت‌ها، به‌صورت اسلایدر.', 'mahan' ) ),
					'post-list'      => array( __( 'فهرست نوشته‌ها', 'mahan' ), __( 'فشرده، مناسب ستون کناری.', 'mahan' ) ),
					'post-tabs'      => array( __( 'نوشته‌ها در تب دسته‌بندی', 'mahan' ), __( 'هر دسته یک تب.', 'mahan' ) ),
					'category-boxes' => array( __( 'باکس دسته‌بندی‌ها', 'mahan' ), __( 'هر تاکسونومی عمومی، با تصویر یا آیکون.', 'mahan' ) ),
					'portfolio-grid' => array( __( 'نمونه‌کارها', 'mahan' ), __( 'با فیلتر زندهٔ دسته‌بندی.', 'mahan' ) ),
					'service-grid'   => array( __( 'شبکهٔ خدمات', 'mahan' ), __( 'از بخش «خدمات».', 'mahan' ) ),
					'breadcrumb'     => array( __( 'مسیر صفحه', 'mahan' ), __( 'با دادهٔ ساختاریافتهٔ BreadcrumbList.', 'mahan' ) ),
					'search-box'     => array( __( 'جعبهٔ جستجو', 'mahan' ), __( 'با نتایج زنده.', 'mahan' ) ),
				),
			),
			'contact' => array(
				'label' => __( 'تماس', 'mahan' ),
				'icon'  => 'phone',
				'items' => array(
					'contact-info'    => array( __( 'اطلاعات تماس', 'mahan' ), __( 'کارت‌های تلفن، ایمیل، نشانی و ساعت کاری.', 'mahan' ) ),
					'newsletter-form' => array( __( 'فرم خبرنامه', 'mahan' ), __( 'ذخیره در سایت یا اتصال به افزونهٔ ایمیلی.', 'mahan' ) ),
					'social-icons'    => array( __( 'شبکه‌های اجتماعی', 'mahan' ), __( 'از تنظیمات قالب یا ورود دستی.', 'mahan' ) ),
					'countdown'       => array( __( 'شمارش معکوس', 'mahan' ), __( 'سه سبک، با ارقام فارسی.', 'mahan' ) ),
				),
			),
		);

		if ( mahan_has_woocommerce() ) {
			$catalog['shop'] = array(
				'label' => __( 'فروشگاه', 'mahan' ),
				'icon'  => 'cart',
				'items' => array(
					'product-grid'       => array( __( 'شبکهٔ محصولات', 'mahan' ), __( 'با فیلترهای ویژه، حراج، پرفروش و موجود.', 'mahan' ) ),
					'product-carousel'   => array( __( 'اسلایدر محصولات', 'mahan' ), __( 'همان فیلترها، به‌صورت اسلایدر.', 'mahan' ) ),
					'product-categories' => array( __( 'دسته‌بندی محصولات', 'mahan' ), __( 'کاشی، دایره‌ای یا روی تصویر.', 'mahan' ) ),
					'product-tabs'       => array( __( 'محصولات در تب دسته‌بندی', 'mahan' ), __( 'هر دستهٔ محصول یک تب.', 'mahan' ) ),
					'product-deal'       => array( __( 'پیشنهاد شگفت‌انگیز', 'mahan' ), __( 'یک محصول کنار شمارش معکوس.', 'mahan' ) ),
					'product-banner'     => array( __( 'بنرهای تبلیغاتی فروشگاه', 'mahan' ), __( 'بنرهای دسته با متن و دکمه.', 'mahan' ) ),
				),
			);
		}

		/**
		 * Filters the element catalogue shown in the panel.
		 *
		 * @param array $catalog Groups of elements.
		 */
		return apply_filters( 'mahan_elements_catalog', $catalog );
	}

	/**
	 * How many elements the catalogue lists.
	 *
	 * @return int
	 */
	public static function count() {
		$total = 0;

		foreach ( self::all() as $group ) {
			$total += count( $group['items'] );
		}

		return $total;
	}
}
