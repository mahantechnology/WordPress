<?php
/**
 * Template tags used by the theme templates and elements.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Which side the sidebar sits on for the current view.
 *
 * Per-post overrides win, then the matching global option.
 *
 * @return string right|left|none
 */
function mahan_current_sidebar_position() {
	$override = '';

	if ( is_singular() ) {
		$override = get_post_meta( get_the_ID(), '_mahan_sidebar', true );
	}

	if ( $override && 'default' !== $override ) {
		return mahan_sanitize_choice( $override, array( 'right', 'left', 'none' ) );
	}

	if ( mahan_has_woocommerce() && ( is_shop() || is_product_taxonomy() ) ) {
		$position = mahan_option( 'shop_sidebar', 'right' );
	} elseif ( mahan_has_woocommerce() && is_product() ) {
		$position = is_active_sidebar( 'sidebar-product' ) ? mahan_option( 'shop_sidebar', 'right' ) : 'none';
	} elseif ( is_singular( 'post' ) ) {
		$position = mahan_option( 'single_sidebar', 'right' );
	} elseif ( is_home() || is_archive() || is_search() ) {
		$position = mahan_option( 'blog_sidebar', 'right' );
	} elseif ( is_page() ) {
		$position = 'none';
	} else {
		$position = mahan_option( 'blog_sidebar', 'right' );
	}

	if ( mahan_is_built_with_elementor() ) {
		$position = 'none';
	}

	/**
	 * Filters the sidebar position for the current view.
	 *
	 * @param string $position One of right, left or none.
	 */
	return apply_filters( 'mahan_sidebar_position', mahan_sanitize_choice( $position, array( 'right', 'left', 'none' ) ) );
}

/**
 * The widget area to print for the current view.
 *
 * @return string
 */
function mahan_current_sidebar_id() {
	if ( mahan_has_woocommerce() && is_product() && is_active_sidebar( 'sidebar-product' ) ) {
		return 'sidebar-product';
	}

	if ( mahan_has_woocommerce() && ( is_shop() || is_product_taxonomy() ) && is_active_sidebar( 'sidebar-shop' ) ) {
		return 'sidebar-shop';
	}

	if ( ( is_home() || is_singular( 'post' ) || is_category() || is_tag() ) && is_active_sidebar( 'sidebar-blog' ) ) {
		return 'sidebar-blog';
	}

	return 'sidebar-main';
}

/**
 * Whether the sidebar should render.
 *
 * @return bool
 */
function mahan_has_sidebar() {
	return 'none' !== mahan_current_sidebar_position() && is_active_sidebar( mahan_current_sidebar_id() );
}

/**
 * Prints the site logo, falling back to the site title.
 *
 * @param array $args Optional. `class` and `dark` keys.
 */
function mahan_site_logo( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class' => '',
			'dark'  => false,
		)
	);

	$classes = trim( 'mahan-logo ' . $args['class'] );

	if ( has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		$image   = wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'class'    => 'mahan-logo__img',
				'alt'      => get_bloginfo( 'name' ),
				'loading'  => 'eager',
				'decoding' => 'async',
			)
		);
	} else {
		$image = '<span class="mahan-logo__text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
	}

	printf(
		'<a href="%1$s" class="%2$s" rel="home" aria-label="%3$s">%4$s</a>',
		esc_url( home_url( '/' ) ),
		esc_attr( $classes ),
		esc_attr( get_bloginfo( 'name' ) ),
		$image // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from core escaping helpers above.
	);
}

/**
 * Prints the post meta row (date, author, reading time, comments, views).
 *
 * @param array $show Which items to print.
 */
function mahan_post_meta( $show = array() ) {
	$show = wp_parse_args(
		$show,
		array(
			'author'  => mahan_option( 'blog_show_author' ),
			'date'    => mahan_option( 'blog_show_date' ),
			'reading' => mahan_option( 'blog_show_reading_time' ),
			'comments'=> true,
			'views'   => mahan_option( 'blog_show_views' ),
		)
	);

	$items = array();

	if ( $show['author'] ) {
		$items[] = sprintf(
			'<span class="mahan-meta__item mahan-meta__author">%1$s<a href="%2$s">%3$s</a></span>',
			mahan_icon( 'user', 16 ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}

	if ( $show['date'] ) {
		$items[] = sprintf(
			'<span class="mahan-meta__item mahan-meta__date">%1$s<time datetime="%2$s">%3$s</time></span>',
			mahan_icon( 'calendar', 16 ),
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( mahan_fa_numbers( get_the_date() ) )
		);
	}

	if ( $show['reading'] ) {
		$items[] = sprintf(
			'<span class="mahan-meta__item mahan-meta__reading">%1$s%2$s</span>',
			mahan_icon( 'clock', 16 ),
			esc_html(
				sprintf(
					/* translators: %s: number of minutes. */
					__( '%s دقیقه مطالعه', 'mahan' ),
					mahan_fa_numbers( mahan_reading_time() )
				)
			)
		);
	}

	if ( $show['views'] ) {
		$views = (int) get_post_meta( get_the_ID(), '_mahan_views', true );

		$items[] = sprintf(
			'<span class="mahan-meta__item mahan-meta__views">%1$s%2$s</span>',
			mahan_icon( 'eye', 16 ),
			esc_html(
				sprintf(
					/* translators: %s: view count. */
					__( '%s بازدید', 'mahan' ),
					mahan_fa_numbers( number_format_i18n( $views ) )
				)
			)
		);
	}

	if ( $show['comments'] && comments_open() ) {
		$items[] = sprintf(
			'<span class="mahan-meta__item mahan-meta__comments">%1$s<a href="%2$s">%3$s</a></span>',
			mahan_icon( 'comment', 16 ),
			esc_url( get_comments_link() ),
			esc_html(
				sprintf(
					/* translators: %s: comment count. */
					__( '%s دیدگاه', 'mahan' ),
					mahan_fa_numbers( number_format_i18n( get_comments_number() ) )
				)
			)
		);
	}

	if ( ! $items ) {
		return;
	}

	echo '<div class="mahan-meta">' . implode( '', $items ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Each item is escaped above.
}

/**
 * Prints the first category of the current post as a pill.
 *
 * @param string $taxonomy Taxonomy to read.
 */
function mahan_post_category_badge( $taxonomy = 'category' ) {
	$terms = get_the_terms( get_the_ID(), $taxonomy );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}

	$term = reset( $terms );

	printf(
		'<a class="mahan-badge mahan-badge--category" href="%1$s">%2$s</a>',
		esc_url( get_term_link( $term ) ),
		esc_html( $term->name )
	);
}

/**
 * Prints the share row for the current post.
 */
function mahan_share_buttons() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );

	$networks = array(
		'telegram'  => array( 'https://t.me/share/url?url=' . $url . '&text=' . $title, __( 'تلگرام', 'mahan' ) ),
		'whatsapp'  => array( 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url, __( 'واتساپ', 'mahan' ) ),
		'twitter'   => array( 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title, __( 'ایکس', 'mahan' ) ),
		'linkedin'  => array( 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url, __( 'لینکدین', 'mahan' ) ),
	);

	echo '<div class="mahan-share"><span class="mahan-share__label">' . esc_html__( 'اشتراک‌گذاری:', 'mahan' ) . '</span>';

	foreach ( $networks as $key => $network ) {
		printf(
			'<a class="mahan-share__btn mahan-share__btn--%1$s" href="%2$s" target="_blank" rel="noopener nofollow" aria-label="%3$s">%4$s</a>',
			esc_attr( $key ),
			esc_url( $network[0] ),
			esc_attr( $network[1] ),
			mahan_icon( $key, 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
		);
	}

	printf(
		'<button type="button" class="mahan-share__btn mahan-share__btn--copy" data-mahan-copy="%1$s" aria-label="%2$s">%3$s</button>',
		esc_url( get_permalink() ),
		esc_attr__( 'کپی لینک', 'mahan' ),
		mahan_icon( 'external', 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
	);

	echo '</div>';
}

/**
 * Prints the theme's pagination.
 *
 * @param WP_Query|null $query Query to paginate. Defaults to the main query.
 */
function mahan_pagination( $query = null ) {
	$query = $query ? $query : $GLOBALS['wp_query'];

	if ( $query->max_num_pages < 2 ) {
		return;
	}

	$links = paginate_links(
		array(
			'total'     => $query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'mid_size'  => 1,
			'end_size'  => 1,
			'type'      => 'array',
			'prev_text' => mahan_icon( 'chevron-right', 18 ) . '<span>' . esc_html__( 'قبلی', 'mahan' ) . '</span>',
			'next_text' => '<span>' . esc_html__( 'بعدی', 'mahan' ) . '</span>' . mahan_icon( 'chevron-left', 18 ),
		)
	);

	if ( ! $links ) {
		return;
	}

	echo '<nav class="mahan-pagination" aria-label="' . esc_attr__( 'صفحه‌بندی', 'mahan' ) . '"><ul>';

	foreach ( $links as $link ) {
		if ( mahan_option( 'persian_digits' ) ) {
			$link = preg_replace_callback(
				'/>(\s*)(\d+)(\s*)</',
				static function ( $matches ) {
					return '>' . $matches[1] . mahan_fa_numbers( $matches[2] ) . $matches[3] . '<';
				},
				$link
			);
		}

		echo '<li>' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links() output.
	}

	echo '</ul></nav>';
}

/**
 * Prints the breadcrumb trail.
 */
function mahan_breadcrumb() {
	Mahan_Breadcrumb::render();
}

/**
 * Prints the social links configured in the customizer.
 *
 * @param string $class Extra CSS classes for the wrapper.
 */
function mahan_social_links( $class = '' ) {
	$networks = array( 'instagram', 'telegram', 'whatsapp', 'linkedin', 'twitter', 'youtube', 'aparat' );
	$links    = array();

	foreach ( $networks as $network ) {
		$url = mahan_option( 'social_' . $network );

		if ( ! $url ) {
			continue;
		}

		$links[] = sprintf(
			'<a class="mahan-social__link mahan-social__link--%1$s" href="%2$s" target="_blank" rel="noopener" aria-label="%1$s">%3$s</a>',
			esc_attr( $network ),
			esc_url( $url ),
			mahan_icon( $network, 20 )
		);
	}

	if ( ! $links ) {
		return;
	}

	printf(
		'<div class="mahan-social %1$s">%2$s</div>',
		esc_attr( $class ),
		implode( '', $links ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
	);
}

/**
 * Prints a section heading used by templates and the demo pages.
 *
 * @param array $args Heading arguments.
 */
function mahan_section_title( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'eyebrow'   => '',
			'title'     => '',
			'highlight' => 0,
			'subtitle'  => '',
			'align'     => 'center',
			'tag'       => 'h2',
			'link'      => '',
			'link_text' => '',
		)
	);

	if ( ! $args['title'] && ! $args['subtitle'] ) {
		return;
	}

	$tag = in_array( $args['tag'], array( 'h1', 'h2', 'h3', 'h4' ), true ) ? $args['tag'] : 'h2';

	printf( '<div class="mahan-section-title mahan-section-title--%s">', esc_attr( $args['align'] ) );

	if ( $args['eyebrow'] ) {
		printf( '<span class="mahan-section-title__eyebrow">%s</span>', esc_html( $args['eyebrow'] ) );
	}

	if ( $args['title'] ) {
		printf(
			'<%1$s class="mahan-section-title__heading">%2$s</%1$s>',
			esc_html( $tag ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Whitelisted above.
			$args['highlight']
				? mahan_highlight_words( $args['title'], (int) $args['highlight'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escapes internally.
				: esc_html( $args['title'] )
		);
	}

	if ( $args['subtitle'] ) {
		printf( '<p class="mahan-section-title__subtitle">%s</p>', esc_html( $args['subtitle'] ) );
	}

	if ( $args['link'] && $args['link_text'] ) {
		printf(
			'<a class="mahan-section-title__link" href="%1$s">%2$s%3$s</a>',
			esc_url( $args['link'] ),
			esc_html( $args['link_text'] ),
			mahan_icon( 'arrow-left', 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
		);
	}

	echo '</div>';
}

/**
 * Prints the "no results" block.
 */
function mahan_no_results() {
	?>
	<div class="mahan-empty">
		<?php mahan_icon_e( 'search', 48, 'mahan-empty__icon' ); ?>
		<h2 class="mahan-empty__title"><?php esc_html_e( 'چیزی پیدا نشد', 'mahan' ); ?></h2>
		<p class="mahan-empty__text"><?php esc_html_e( 'متأسفانه محتوایی با این مشخصات وجود ندارد. عبارت دیگری را جستجو کنید.', 'mahan' ); ?></p>
		<?php get_search_form(); ?>
	</div>
	<?php
}

/**
 * Prints an entry's tags as pills.
 */
function mahan_post_tags() {
	$tags = get_the_tags();

	if ( ! $tags || is_wp_error( $tags ) ) {
		return;
	}

	echo '<div class="mahan-tags">' . mahan_icon( 'tag', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.

	foreach ( $tags as $tag ) {
		printf(
			'<a class="mahan-tags__item" href="%1$s">%2$s</a>',
			esc_url( get_tag_link( $tag ) ),
			esc_html( $tag->name )
		);
	}

	echo '</div>';
}

/**
 * Prints the table of contents built from the post headings.
 *
 * @param string $content Post content.
 */
function mahan_table_of_contents( $content ) {
	if ( ! preg_match_all( '/<h([2-3])[^>]*>(.*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER ) ) {
		return;
	}

	if ( count( $matches ) < 3 ) {
		return;
	}

	echo '<details class="mahan-toc" open><summary class="mahan-toc__summary">' . mahan_icon( 'list', 20 ) . '<span>' . esc_html__( 'فهرست مطالب', 'mahan' ) . '</span></summary><ol class="mahan-toc__list">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.

	foreach ( $matches as $index => $match ) {
		printf(
			'<li class="mahan-toc__item mahan-toc__item--h%1$d"><a href="#mahan-heading-%2$d">%3$s</a></li>',
			(int) $match[1],
			(int) $index,
			esc_html( wp_strip_all_tags( $match[2] ) )
		);
	}

	echo '</ol></details>';
}

/**
 * Prints a star rating.
 *
 * @param float $rating Rating out of five.
 * @param int   $count  Number of ratings.
 */
function mahan_stars( $rating, $count = 0 ) {
	$rating = max( 0, min( 5, (float) $rating ) );

	echo '<span class="mahan-stars" role="img" aria-label="' . esc_attr( sprintf( /* translators: %s: rating. */ __( 'امتیاز %s از ۵', 'mahan' ), mahan_fa_numbers( $rating ) ) ) . '">';
	echo '<span class="mahan-stars__track">';

	for ( $i = 0; $i < 5; $i++ ) {
		echo mahan_icon( 'star', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
	}

	echo '</span><span class="mahan-stars__fill" style="width:' . esc_attr( ( $rating / 5 ) * 100 ) . '%">';

	for ( $i = 0; $i < 5; $i++ ) {
		echo mahan_icon( 'star', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
	}

	echo '</span></span>';

	if ( $count > 0 ) {
		printf(
			'<span class="mahan-stars__count">(%s)</span>',
			esc_html( mahan_fa_numbers( number_format_i18n( $count ) ) )
		);
	}
}

/**
 * Prints one post card.
 *
 * Used by the archive templates, the AJAX load-more handler and the Elementor
 * content elements, so they all produce identical markup.
 *
 * @param array $args Card options.
 */
function mahan_render_post_card( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'style'         => 'grid',
			'show_image'    => true,
			'show_category' => true,
			'show_meta'     => true,
			'show_excerpt'  => true,
			'show_more'     => true,
			'excerpt_words' => (int) mahan_option( 'blog_excerpt_length', 26 ),
			'image_size'    => 'mahan-card',
		)
	);

	$style = mahan_sanitize_choice( $args['style'], array( 'grid', 'list', 'overlay', 'minimal', 'masonry', 'magazine' ) );
	?>
	<article <?php post_class( 'mahan-card mahan-card--' . $style ); ?>>
		<?php if ( $args['show_image'] ) : ?>
			<a class="mahan-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<?php echo mahan_thumbnail( get_the_ID(), $args['image_size'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?>
				<?php if ( $args['show_category'] && 'overlay' !== $style ) : ?>
					<span class="mahan-card__badge"><?php mahan_post_category_badge(); ?></span>
				<?php endif; ?>
			</a>
		<?php endif; ?>

		<div class="mahan-card__body">
			<?php if ( $args['show_category'] && 'overlay' === $style ) : ?>
				<?php mahan_post_category_badge(); ?>
			<?php endif; ?>

			<h3 class="mahan-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<?php if ( $args['show_meta'] ) : ?>
				<?php
				mahan_post_meta(
					array(
						'author'   => (bool) mahan_option( 'blog_show_author' ),
						'date'     => true,
						'reading'  => (bool) mahan_option( 'blog_show_reading_time' ),
						'comments' => false,
						'views'    => false,
					)
				);
				?>
			<?php endif; ?>

			<?php if ( $args['show_excerpt'] ) : ?>
				<p class="mahan-card__excerpt">
					<?php echo esc_html( wp_trim_words( get_the_excerpt(), (int) $args['excerpt_words'], '…' ) ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $args['show_more'] ) : ?>
				<a class="mahan-card__more" href="<?php the_permalink(); ?>">
					<?php esc_html_e( 'ادامهٔ مطلب', 'mahan' ); ?>
					<?php mahan_icon_e( 'arrow-left', 18 ); ?>
				</a>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Prints one product-like card for the custom post types.
 *
 * @param array $args Card options.
 */
function mahan_render_cpt_card( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'show_excerpt' => true,
			'excerpt_words' => 18,
			'icon_meta'    => '_mahan_service_icon',
			'image_size'   => 'mahan-card',
		)
	);

	$icon = $args['icon_meta'] ? (string) get_post_meta( get_the_ID(), $args['icon_meta'], true ) : '';
	?>
	<article <?php post_class( 'mahan-card mahan-card--cpt' ); ?>>
		<a class="mahan-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php echo mahan_thumbnail( get_the_ID(), $args['image_size'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?>
		</a>
		<div class="mahan-card__body">
			<?php if ( $icon ) : ?>
				<span class="mahan-card__icon"><?php mahan_icon_e( $icon, 26 ); ?></span>
			<?php endif; ?>
			<h3 class="mahan-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if ( $args['show_excerpt'] ) : ?>
				<p class="mahan-card__excerpt">
					<?php echo esc_html( wp_trim_words( get_the_excerpt(), (int) $args['excerpt_words'], '…' ) ); ?>
				</p>
			<?php endif; ?>
			<a class="mahan-card__more" href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'جزئیات', 'mahan' ); ?>
				<?php mahan_icon_e( 'arrow-left', 18 ); ?>
			</a>
		</div>
	</article>
	<?php
}

/**
 * Renders one comment in the theme's markup.
 *
 * @param WP_Comment $comment Comment being rendered.
 * @param array      $args    wp_list_comments() arguments.
 * @param int        $depth   Nesting depth.
 */
function mahan_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo esc_html( $tag ); ?> <?php comment_class( 'mahan-comment' ); ?> id="comment-<?php comment_ID(); ?>">
		<article class="mahan-comment__body">
			<header class="mahan-comment__header">
				<?php echo get_avatar( $comment, (int) $args['avatar_size'], '', '', array( 'class' => 'mahan-comment__avatar' ) ); ?>
				<div class="mahan-comment__meta">
					<span class="mahan-comment__author"><?php comment_author(); ?></span>
					<time class="mahan-comment__date" datetime="<?php comment_time( 'c' ); ?>">
						<?php echo esc_html( mahan_time_ago( get_comment_time( 'U' ) ) ); ?>
					</time>
				</div>
			</header>

			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="mahan-comment__pending"><?php esc_html_e( 'دیدگاه شما در انتظار تأیید است.', 'mahan' ); ?></p>
			<?php endif; ?>

			<div class="mahan-comment__content"><?php comment_text(); ?></div>

			<footer class="mahan-comment__footer">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => esc_html__( 'پاسخ', 'mahan' ),
							'before'     => '<span class="mahan-comment__reply">',
							'after'      => '</span>',
						)
					)
				);

				edit_comment_link( esc_html__( 'ویرایش', 'mahan' ), '<span class="mahan-comment__edit">', '</span>' );
				?>
			</footer>
		</article>
	<?php
	// wp_list_comments() closes the element for us.
}

/**
 * Printed in place of a navigation menu when no menu is assigned yet.
 *
 * Rather than dumping every page, it points an administrator at the menu
 * screen and shows the visitor nothing.
 */
function mahan_menu_fallback() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	printf(
		'<ul class="mahan-menu mahan-menu--empty"><li class="mahan-menu__item"><a class="mahan-menu__link" href="%1$s">%2$s</a></li></ul>',
		esc_url( admin_url( 'nav-menus.php' ) ),
		esc_html__( 'یک منو بسازید', 'mahan' )
	);
}
