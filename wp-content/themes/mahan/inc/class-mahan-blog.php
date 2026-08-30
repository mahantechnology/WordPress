<?php
/**
 * Blog behaviour: view counting, heading anchors, and the reading progress bar.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Blog {

	/**
	 * Hooks the blog features.
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'count_view' ) );
		add_filter( 'the_content', array( $this, 'anchor_headings' ), 12 );
		add_filter( 'the_content', array( $this, 'prepend_toc' ), 14 );
		add_filter( 'get_the_archive_title', array( $this, 'clean_archive_title' ) );
		add_filter( 'the_password_form', array( $this, 'password_form' ) );
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults' ) );
	}

	/**
	 * Increments the stored view count once per post view.
	 */
	public function count_view() {
		if ( ! is_singular( array( 'post', 'mahan_portfolio' ) ) || is_preview() ) {
			return;
		}

		// Skip bots and logged-in editors so the number reflects readers.
		if ( current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post_id = get_queried_object_id();

		if ( ! $post_id ) {
			return;
		}

		$views = (int) get_post_meta( $post_id, '_mahan_views', true );
		update_post_meta( $post_id, '_mahan_views', $views + 1 );
	}

	/**
	 * Gives every h2/h3 an id so the table of contents can link to it.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function anchor_headings( $content ) {
		if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( ! mahan_option( 'single_toc' ) ) {
			return $content;
		}

		$index = 0;

		return preg_replace_callback(
			'/<h([2-3])([^>]*)>/i',
			static function ( $matches ) use ( &$index ) {
				$attrs = $matches[2];

				if ( false !== stripos( $attrs, 'id=' ) ) {
					++$index;
					return $matches[0];
				}

				$replacement = sprintf( '<h%1$d%2$s id="mahan-heading-%3$d">', (int) $matches[1], $attrs, $index );
				++$index;

				return $replacement;
			},
			$content
		);
	}

	/**
	 * Inserts the table of contents above the content.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function prepend_toc( $content ) {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( ! mahan_option( 'single_toc' ) ) {
			return $content;
		}

		ob_start();
		mahan_table_of_contents( $content );
		$toc = ob_get_clean();

		return $toc . $content;
	}

	/**
	 * Strips the "Category:" style prefix from archive titles.
	 *
	 * @param string $title Archive title.
	 * @return string
	 */
	public function clean_archive_title( $title ) {
		if ( is_category() || is_tag() || is_tax() ) {
			return single_term_title( '', false );
		}

		if ( is_author() ) {
			return get_the_author();
		}

		if ( is_post_type_archive() ) {
			return post_type_archive_title( '', false );
		}

		return $title;
	}

	/**
	 * Themes the password form.
	 *
	 * @return string
	 */
	public function password_form() {
		$id = 'mahan-pwbox-' . wp_rand();

		return sprintf(
			'<form action="%1$s" class="mahan-password-form" method="post">
				<p class="mahan-password-form__text">%2$s</p>
				<div class="mahan-password-form__row">
					<label class="screen-reader-text" for="%3$s">%4$s</label>
					<input name="post_password" id="%3$s" type="password" autocomplete="off" placeholder="%4$s" required />
					<button type="submit" class="mahan-btn mahan-btn--primary">%5$s</button>
				</div>
			</form>',
			esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ),
			esc_html__( 'این محتوا با رمز محافظت می‌شود. برای مشاهده، رمز را وارد کنید.', 'mahan' ),
			esc_attr( $id ),
			esc_attr__( 'رمز عبور', 'mahan' ),
			esc_html__( 'مشاهده', 'mahan' )
		);
	}

	/**
	 * Persian labels and theme classes for the comment form.
	 *
	 * @param array $defaults Comment form defaults.
	 * @return array
	 */
	public function comment_form_defaults( $defaults ) {
		$defaults['title_reply']          = __( 'دیدگاه شما', 'mahan' );
		$defaults['title_reply_to']       = __( 'پاسخ به %s', 'mahan' );
		$defaults['cancel_reply_link']    = __( 'انصراف از پاسخ', 'mahan' );
		$defaults['label_submit']         = __( 'ثبت دیدگاه', 'mahan' );
		$defaults['class_submit']         = 'mahan-btn mahan-btn--primary';
		$defaults['comment_notes_before'] = '<p class="mahan-comment-notes">' . esc_html__( 'نشانی ایمیل شما منتشر نخواهد شد.', 'mahan' ) . '</p>';
		$defaults['comment_field']        = sprintf(
			'<p class="comment-form-comment"><label for="comment">%1$s</label><textarea id="comment" name="comment" rows="5" required placeholder="%2$s"></textarea></p>',
			esc_html__( 'دیدگاه', 'mahan' ),
			esc_attr__( 'نظر خود را بنویسید…', 'mahan' )
		);

		return $defaults;
	}
}
