<?php
/**
 * The theme's sidebar widgets.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shows a list of posts with thumbnails, sorted by date, comments or views.
 */
class Mahan_Widget_Posts extends WP_Widget {

	/**
	 * Registers the widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'mahan_posts',
			__( 'ماهان: نوشته‌های ویژه', 'mahan' ),
			array(
				'description' => __( 'فهرست نوشته‌ها با تصویر شاخص، بر اساس جدیدترین، پربازدیدترین یا پربحث‌ترین.', 'mahan' ),
				'classname'   => 'mahan-widget-posts',
			)
		);
	}

	/**
	 * Prints the widget.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Saved settings.
	 */
	public function widget( $args, $instance ) {
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : __( 'جدیدترین نوشته‌ها', 'mahan' );
		$count  = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$order  = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
		$thumbs = ! isset( $instance['thumbnail'] ) || $instance['thumbnail'];

		$query_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( 'comment_count' === $order ) {
			$query_args['orderby'] = 'comment_count';
		} elseif ( 'views' === $order ) {
			$query_args['meta_key'] = '_mahan_views'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Widget query, bounded by posts_per_page.
			$query_args['orderby']  = 'meta_value_num';
		} elseif ( 'rand' === $order ) {
			$query_args['orderby'] = 'rand';
		}

		$query = new WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.
		echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.

		echo '<ul class="mahan-widget-posts__list">';

		while ( $query->have_posts() ) {
			$query->the_post();

			echo '<li class="mahan-widget-posts__item">';

			if ( $thumbs ) {
				printf(
					'<a class="mahan-widget-posts__thumb" href="%1$s" aria-hidden="true" tabindex="-1">%2$s</a>',
					esc_url( get_permalink() ),
					mahan_thumbnail( get_the_ID(), 'mahan-thumb' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup.
				);
			}

			printf(
				'<div class="mahan-widget-posts__body"><a class="mahan-widget-posts__title" href="%1$s">%2$s</a><span class="mahan-widget-posts__date">%3$s</span></div>',
				esc_url( get_permalink() ),
				esc_html( get_the_title() ),
				esc_html( mahan_time_ago() )
			);

			echo '</li>';
		}

		echo '</ul>';
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.

		wp_reset_postdata();
	}

	/**
	 * Prints the widget form.
	 *
	 * @param array $instance Saved settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : __( 'جدیدترین نوشته‌ها', 'mahan' );
		$count     = isset( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$orderby   = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
		$thumbnail = ! isset( $instance['thumbnail'] ) || $instance['thumbnail'];
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان:', 'mahan' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'تعداد:', 'mahan' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="20" value="<?php echo esc_attr( $count ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'مرتب‌سازی:', 'mahan' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
				<option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'جدیدترین', 'mahan' ); ?></option>
				<option value="views" <?php selected( $orderby, 'views' ); ?>><?php esc_html_e( 'پربازدیدترین', 'mahan' ); ?></option>
				<option value="comment_count" <?php selected( $orderby, 'comment_count' ); ?>><?php esc_html_e( 'پربحث‌ترین', 'mahan' ); ?></option>
				<option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php esc_html_e( 'تصادفی', 'mahan' ); ?></option>
			</select>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $thumbnail ); ?> name="<?php echo esc_attr( $this->get_field_name( 'thumbnail' ) ); ?>" value="1" />
				<?php esc_html_e( 'نمایش تصویر شاخص', 'mahan' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Sanitizes the saved settings.
	 *
	 * @param array $new_instance Submitted values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'     => sanitize_text_field( $new_instance['title'] ),
			'count'     => min( 20, max( 1, absint( $new_instance['count'] ) ) ),
			'orderby'   => mahan_sanitize_choice( $new_instance['orderby'], array( 'date', 'views', 'comment_count', 'rand' ) ),
			'thumbnail' => ! empty( $new_instance['thumbnail'] ),
		);
	}
}

/**
 * Shows the site's contact details and social links.
 */
class Mahan_Widget_Contact extends WP_Widget {

	/**
	 * Registers the widget.
	 */
	public function __construct() {
		parent::__construct(
			'mahan_contact',
			__( 'ماهان: اطلاعات تماس', 'mahan' ),
			array(
				'description' => __( 'شماره تماس، ایمیل، آدرس و شبکه‌های اجتماعی.', 'mahan' ),
				'classname'   => 'mahan-widget-contact',
			)
		);
	}

	/**
	 * Prints the widget.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Saved settings.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'راه‌های ارتباطی', 'mahan' );

		$rows = array(
			'phone'   => array( mahan_option( 'contact_phone' ), 'phone', 'tel:' ),
			'mail'    => array( mahan_option( 'contact_email' ), 'mail', 'mailto:' ),
			'map-pin' => array( mahan_option( 'contact_address' ), 'map-pin', '' ),
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.
		echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.

		echo '<ul class="mahan-contact-list">';

		foreach ( $rows as $icon => $row ) {
			list( $value, $icon_name, $scheme ) = $row;

			if ( ! $value ) {
				continue;
			}

			echo '<li class="mahan-contact-list__item">' . mahan_icon( $icon_name, 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.

			if ( $scheme ) {
				printf(
					'<a href="%1$s%2$s">%3$s</a>',
					esc_attr( $scheme ),
					esc_attr( 'tel:' === $scheme ? mahan_en_numbers( $value ) : $value ),
					esc_html( $value )
				);
			} else {
				echo '<span>' . esc_html( $value ) . '</span>';
			}

			echo '</li>';
		}

		echo '</ul>';

		mahan_social_links( 'mahan-social--widget' );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.
	}

	/**
	 * Prints the widget form.
	 *
	 * @param array $instance Saved settings.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'راه‌های ارتباطی', 'mahan' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان:', 'mahan' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p class="description">
			<?php esc_html_e( 'مقادیر تماس از بخش «شبکه‌های اجتماعی و تماس» در سفارشی‌سازی خوانده می‌شوند.', 'mahan' ); ?>
		</p>
		<?php
	}

	/**
	 * Sanitizes the saved settings.
	 *
	 * @param array $new_instance Submitted values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array( 'title' => sanitize_text_field( $new_instance['title'] ) );
	}
}

/**
 * Renders a tag cloud styled as pills.
 */
class Mahan_Widget_Tags extends WP_Widget {

	/**
	 * Registers the widget.
	 */
	public function __construct() {
		parent::__construct(
			'mahan_tags',
			__( 'ماهان: برچسب‌های داغ', 'mahan' ),
			array(
				'description' => __( 'برچسب‌های پرکاربرد به‌صورت قرص‌های رنگی.', 'mahan' ),
				'classname'   => 'mahan-widget-tags',
			)
		);
	}

	/**
	 * Prints the widget.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Saved settings.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'برچسب‌های داغ', 'mahan' );
		$count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 15;

		$tags = get_terms(
			array(
				'taxonomy'   => 'post_tag',
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => $count,
				'hide_empty' => true,
			)
		);

		if ( ! $tags || is_wp_error( $tags ) ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.
		echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.

		echo '<div class="mahan-tags mahan-tags--cloud">';

		foreach ( $tags as $tag ) {
			printf(
				'<a class="mahan-tags__item" href="%1$s">%2$s<span>%3$s</span></a>',
				esc_url( get_term_link( $tag ) ),
				esc_html( $tag->name ),
				esc_html( mahan_fa_numbers( $tag->count ) )
			);
		}

		echo '</div>';
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sidebar markup.
	}

	/**
	 * Prints the widget form.
	 *
	 * @param array $instance Saved settings.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'برچسب‌های داغ', 'mahan' );
		$count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 15;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'عنوان:', 'mahan' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'تعداد:', 'mahan' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="4" max="40" value="<?php echo esc_attr( $count ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitizes the saved settings.
	 *
	 * @param array $new_instance Submitted values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title' => sanitize_text_field( $new_instance['title'] ),
			'count' => min( 40, max( 4, absint( $new_instance['count'] ) ) ),
		);
	}
}

/**
 * Registers the theme widgets.
 */
class Mahan_Widgets {

	/**
	 * Hooks registration.
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register' ), 20 );
	}

	/**
	 * Registers each widget class.
	 */
	public function register() {
		register_widget( 'Mahan_Widget_Posts' );
		register_widget( 'Mahan_Widget_Contact' );
		register_widget( 'Mahan_Widget_Tags' );
	}
}
