<?php
/**
 * Testimonial carousel element.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_testimonial_carousel extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-testimonial-carousel';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نظرات مشتریان (اسلایدر)', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'testimonials_section',
			array(
				'label' => __( 'نظرات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'منبع', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => array(
					'manual' => __( 'ورود دستی', 'mahan' ),
					'cpt'    => __( 'از بخش «نظرات مشتریان»', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'cpt_count',
			array(
				'label'     => __( 'تعداد', 'mahan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 9,
				'min'       => 1,
				'max'       => 30,
				'condition' => array( 'source' => 'cpt' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'avatar',
			array(
				'label'   => __( 'تصویر', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'نام', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'سارا محمدی', 'mahan' ),
			)
		);

		$repeater->add_control(
			'role',
			array(
				'label'   => __( 'سمت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مدیر بازاریابی', 'mahan' ),
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label'   => __( 'امتیاز', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 5,
				'step'    => 0.5,
				'default' => 5,
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'متن نظر', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'تجربهٔ کار با این تیم فوق‌العاده بود؛ سرعت، دقت و پشتیبانی در بالاترین سطح.', 'mahan' ),
			)
		);

		$this->add_control(
			'testimonials',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array(
						'name' => __( 'سارا محمدی', 'mahan' ),
						'role' => __( 'مدیر بازاریابی', 'mahan' ),
						'text' => __( 'تجربهٔ کار با این تیم فوق‌العاده بود؛ سرعت، دقت و پشتیبانی در بالاترین سطح.', 'mahan' ),
					),
					array(
						'name' => __( 'امیر رستمی', 'mahan' ),
						'role' => __( 'بنیان‌گذار استارتاپ', 'mahan' ),
						'text' => __( 'در کمتر از یک هفته سایت فروشگاهی‌مان راه افتاد و فروش‌مان دو برابر شد.', 'mahan' ),
					),
					array(
						'name' => __( 'نگار کریمی', 'mahan' ),
						'role' => __( 'طراح محصول', 'mahan' ),
						'text' => __( 'المان‌های آماده دست ما را برای طراحی صفحه‌های تازه کاملاً باز گذاشت.', 'mahan' ),
					),
				),
			)
		);

		$this->add_control(
			'card_style',
			array(
				'label'   => __( 'سبک کارت', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'quote',
				'options' => array(
					'quote'   => __( 'با نشان نقل‌قول', 'mahan' ),
					'bubble'  => __( 'حبابی', 'mahan' ),
					'minimal' => __( 'مینیمال', 'mahan' ),
				),
			)
		);

		$this->end_controls_section();

		$this->add_carousel_controls( 3 );
		$this->add_card_style_controls( '.mahan-testimonial' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = 'cpt' === $settings['source'] ? $this->from_cpt( (int) $settings['cpt_count'] ) : $this->from_repeater( $settings['testimonials'] );

		if ( ! $items ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-carousel mahan-testimonials mahan-testimonials--<?php echo esc_attr( $settings['card_style'] ); ?>"<?php $this->carousel_attributes( $settings ); ?>>
			<div class="mahan-carousel__viewport">
				<div class="mahan-carousel__track" data-mahan-carousel-track>
					<?php foreach ( $items as $item ) : ?>
						<div class="mahan-carousel__slide">
							<figure class="mahan-testimonial">
								<span class="mahan-testimonial__quote" aria-hidden="true"><?php $this->render_icon( 'quote', 34 ); ?></span>

								<?php if ( $item['rating'] > 0 ) : ?>
									<div class="mahan-testimonial__rating"><?php mahan_stars( $item['rating'] ); ?></div>
								<?php endif; ?>

								<blockquote class="mahan-testimonial__text"><?php echo esc_html( $item['text'] ); ?></blockquote>

								<figcaption class="mahan-testimonial__author">
									<?php if ( $item['avatar'] ) : ?>
										<img class="mahan-testimonial__avatar" src="<?php echo esc_url( $item['avatar'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" loading="lazy" />
									<?php endif; ?>
									<span class="mahan-testimonial__meta">
										<strong><?php echo esc_html( $item['name'] ); ?></strong>
										<?php if ( $item['role'] ) : ?>
											<span><?php echo esc_html( $item['role'] ); ?></span>
										<?php endif; ?>
									</span>
								</figcaption>
							</figure>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php $this->render_carousel_nav( $settings ); ?>
		</div>
		<?php
	}

	/**
	 * Normalises the repeater rows into the shape render() expects.
	 *
	 * @param array $rows Repeater rows.
	 * @return array
	 */
	private function from_repeater( $rows ) {
		$items = array();

		foreach ( (array) $rows as $row ) {
			$items[] = array(
				'avatar' => $this->image_url( $row['avatar'] ),
				'name'   => $row['name'],
				'role'   => $row['role'],
				'rating' => (float) $row['rating'],
				'text'   => $row['text'],
			);
		}

		return $items;
	}

	/**
	 * Reads the testimonials from the custom post type.
	 *
	 * @param int $count How many to fetch.
	 * @return array
	 */
	private function from_cpt( $count ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'mahan_testimonial',
				'post_status'    => 'publish',
				'posts_per_page' => max( 1, $count ),
				'no_found_rows'  => true,
			)
		);

		$items = array();

		while ( $query->have_posts() ) {
			$query->the_post();

			$items[] = array(
				'avatar' => get_the_post_thumbnail_url( get_the_ID(), 'mahan-square' ),
				'name'   => get_the_title(),
				'role'   => (string) get_post_meta( get_the_ID(), '_mahan_testimonial_role', true ),
				'rating' => (float) get_post_meta( get_the_ID(), '_mahan_testimonial_rating', true ),
				'text'   => wp_strip_all_tags( get_the_content() ),
			);
		}

		wp_reset_postdata();

		return $items;
	}
}
