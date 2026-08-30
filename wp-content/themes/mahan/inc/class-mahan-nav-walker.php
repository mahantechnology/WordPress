<?php
/**
 * Navigation walker: adds the markup the menu CSS and the mega menu need.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Depth of the item currently being written, so `end_lvl` can match it.
	 *
	 * @var int
	 */
	protected $current_item_mega = false;

	/**
	 * Opens a submenu, switching to the mega layout when the parent asks for it.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( 0 === $depth && $this->current_item_mega ) {
			$output .= "\n$indent<div class=\"mahan-menu__mega\"><div class=\"mahan-menu__mega-inner\"><ul class=\"mahan-menu__mega-list\">\n";
			return;
		}

		$output .= "\n$indent<ul class=\"mahan-menu__sub sub-menu depth-{$depth}\">\n";
	}

	/**
	 * Closes a submenu.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( 0 === $depth && $this->current_item_mega ) {
			$output .= "$indent</ul></div></div>\n";
			return;
		}

		$output .= "$indent</ul>\n";
	}

	/**
	 * Writes one menu item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu args.
	 * @param int      $id     Menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'mahan-menu__item';
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$is_mega      = false;

		if ( 0 === $depth && $has_children ) {
			$is_mega = 'yes' === get_post_meta( $item->ID, '_mahan_mega_menu', true );
		}

		$this->current_item_mega = $is_mega;

		if ( $has_children ) {
			$classes[] = 'mahan-menu__item--has-children';
		}

		if ( $is_mega ) {
			$classes[] = 'mahan-menu__item--mega';
			$columns   = (int) get_post_meta( $item->ID, '_mahan_mega_columns', true );
			$classes[] = 'mahan-menu__item--mega-cols-' . ( $columns ? $columns : 4 );
		}

		$badge = get_post_meta( $item->ID, '_mahan_menu_badge', true );

		if ( $badge ) {
			$classes[] = 'mahan-menu__item--badged';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$id_attr     = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );

		$output .= sprintf(
			'<li id="%1$s" class="%2$s">',
			esc_attr( $id_attr ),
			esc_attr( $class_names )
		);

		$atts = array(
			'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
			'target' => ! empty( $item->target ) ? $item->target : '',
			'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
			'href'   => ! empty( $item->url ) ? $item->url : '',
			'class'  => 'mahan-menu__link',
		);

		if ( '_blank' === $atts['target'] && empty( $atts['rel'] ) ) {
			$atts['rel'] = 'noopener';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( '' === $value || false === $value ) {
				continue;
			}

			$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
			$attributes .= ' ' . $attr . '="' . $value . '"';
		}

		$icon  = get_post_meta( $item->ID, '_mahan_menu_icon', true );
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$inner = '';

		if ( $icon ) {
			$inner .= mahan_icon( $icon, 18, 'mahan-menu__icon' );
		}

		$inner .= '<span class="mahan-menu__text">' . esc_html( $title ) . '</span>';

		if ( $badge ) {
			$inner .= '<span class="mahan-menu__badge">' . esc_html( $badge ) . '</span>';
		}

		if ( ! empty( $item->description ) && 0 < $depth ) {
			$inner .= '<span class="mahan-menu__desc">' . esc_html( $item->description ) . '</span>';
		}

		if ( $has_children ) {
			$inner .= mahan_icon( 'chevron-down', 16, 'mahan-menu__arrow' );
		}

		$output .= '<a' . $attributes . '>' . $inner . '</a>';

		if ( $has_children ) {
			$output .= sprintf(
				'<button type="button" class="mahan-menu__toggle" aria-expanded="false" aria-label="%s">%s</button>',
				esc_attr__( 'باز کردن زیرمنو', 'mahan' ),
				mahan_icon( 'chevron-down', 16 )
			);
		}
	}

	/**
	 * Closes one menu item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
