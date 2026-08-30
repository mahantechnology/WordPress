<?php
/**
 * Team member grid.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_team_grid extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-team-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'اعضای تیم', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'team_section',
			array(
				'label' => __( 'اعضا', 'mahan' ),
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
					'cpt'    => __( 'از بخش «اعضای تیم»', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'cpt_count',
			array(
				'label'     => __( 'تعداد', 'mahan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,
				'min'       => 1,
				'max'       => 30,
				'condition' => array( 'source' => 'cpt' ),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک کارت', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card'    => __( 'کارت', 'mahan' ),
					'overlay' => __( 'اطلاعات روی تصویر', 'mahan' ),
					'circle'  => __( 'تصویر دایره‌ای', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'photo',
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
				'default' => __( 'رضا احمدی', 'mahan' ),
			)
		);

		$repeater->add_control(
			'role',
			array(
				'label'   => __( 'سمت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مدیر فنی', 'mahan' ),
			)
		);

		$repeater->add_control(
			'bio',
			array(
				'label' => __( 'توضیح کوتاه', 'mahan' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
			)
		);

		foreach ( array(
			'linkedin'  => __( 'لینکدین', 'mahan' ),
			'twitter'   => __( 'ایکس', 'mahan' ),
			'instagram' => __( 'اینستاگرام', 'mahan' ),
			'mail'      => __( 'ایمیل', 'mahan' ),
		) as $key => $label ) {
			$repeater->add_control(
				'social_' . $key,
				array(
					'label' => $label,
					'type'  => Controls_Manager::TEXT,
				)
			);
		}

		$this->add_control(
			'members',
			array(
				'label'       => __( 'اعضا', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array(
						'name' => __( 'رضا احمدی', 'mahan' ),
						'role' => __( 'مدیر فنی', 'mahan' ),
					),
					array(
						'name' => __( 'مریم سلطانی', 'mahan' ),
						'role' => __( 'طراح محصول', 'mahan' ),
					),
					array(
						'name' => __( 'کامران نوری', 'mahan' ),
						'role' => __( 'توسعه‌دهندهٔ ارشد', 'mahan' ),
					),
					array(
						'name' => __( 'هستی مرادی', 'mahan' ),
						'role' => __( 'مدیر بازاریابی', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 4 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-member' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$members  = 'cpt' === $settings['source'] ? $this->from_cpt( (int) $settings['cpt_count'] ) : $this->from_repeater( $settings['members'] );

		if ( ! $members ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-team mahan-team--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $members as $member ) : ?>
				<article class="mahan-member">
					<?php if ( $member['photo'] ) : ?>
						<div class="mahan-member__photo">
							<img src="<?php echo esc_url( $member['photo'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>" loading="lazy" />
						</div>
					<?php endif; ?>
					<div class="mahan-member__body">
						<h3 class="mahan-member__name"><?php echo esc_html( $member['name'] ); ?></h3>
						<?php if ( $member['role'] ) : ?>
							<span class="mahan-member__role"><?php echo esc_html( $member['role'] ); ?></span>
						<?php endif; ?>
						<?php if ( $member['bio'] ) : ?>
							<p class="mahan-member__bio"><?php echo esc_html( $member['bio'] ); ?></p>
						<?php endif; ?>
						<?php if ( $member['social'] ) : ?>
							<div class="mahan-member__social">
								<?php foreach ( $member['social'] as $network => $url ) : ?>
									<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $network ); ?>">
										<?php $this->render_icon( $network, 18 ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Normalises the repeater rows.
	 *
	 * @param array $rows Repeater rows.
	 * @return array
	 */
	private function from_repeater( $rows ) {
		$members = array();

		foreach ( (array) $rows as $row ) {
			$social = array();

			foreach ( array( 'linkedin', 'twitter', 'instagram', 'mail' ) as $network ) {
				$value = $row[ 'social_' . $network ];

				if ( ! $value ) {
					continue;
				}

				$social[ $network ] = 'mail' === $network && is_email( $value ) ? 'mailto:' . $value : $value;
			}

			$members[] = array(
				'photo'  => $this->image_url( $row['photo'] ),
				'name'   => $row['name'],
				'role'   => $row['role'],
				'bio'    => $row['bio'],
				'social' => $social,
			);
		}

		return $members;
	}

	/**
	 * Reads members from the custom post type.
	 *
	 * @param int $count How many to fetch.
	 * @return array
	 */
	private function from_cpt( $count ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'mahan_team',
				'post_status'    => 'publish',
				'posts_per_page' => max( 1, $count ),
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);

		$members = array();

		while ( $query->have_posts() ) {
			$query->the_post();

			$social = array();

			foreach ( array( 'linkedin', 'twitter', 'instagram' ) as $network ) {
				$url = (string) get_post_meta( get_the_ID(), '_mahan_team_' . $network, true );

				if ( $url ) {
					$social[ $network ] = $url;
				}
			}

			$members[] = array(
				'photo'  => get_the_post_thumbnail_url( get_the_ID(), 'mahan-portrait' ),
				'name'   => get_the_title(),
				'role'   => (string) get_post_meta( get_the_ID(), '_mahan_team_role', true ),
				'bio'    => wp_strip_all_tags( get_the_excerpt() ),
				'social' => $social,
			);
		}

		wp_reset_postdata();

		return $members;
	}
}
