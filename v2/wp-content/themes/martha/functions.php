<?php
/**
 * Martha theme functions.
 *
 * @package Martha
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MARTHA_VERSION', '2.0.0' );

require_once get_template_directory() . '/inc/customizer.php';

add_action(
	'after_setup_theme',
	static function () {
		load_theme_textdomain( 'martha', get_template_directory() . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 100,
				'width'       => 400,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'martha' ),
				'footer'  => __( 'Footer Menu', 'martha' ),
			)
		);

		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );
	}
);

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_style(
			'martha-style',
			get_stylesheet_uri(),
			array(),
			MARTHA_VERSION
		);

		wp_enqueue_script(
			'martha-header',
			get_template_directory_uri() . '/assets/js/header.js',
			array(),
			MARTHA_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_localize_script(
			'martha-header',
			'marthaHeader',
			array(
				'scrollThreshold' => 160,
			)
		);

		wp_enqueue_script(
			'martha-timeline-animate',
			get_template_directory_uri() . '/assets/js/timeline-animate.js',
			array(),
			MARTHA_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'martha-counter-animate',
			get_template_directory_uri() . '/assets/js/counter-animate.js',
			array(),
			MARTHA_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}
);

/**
 * Fallback primary menu used when no menu is assigned to the `primary` location.
 *
 * Renders a small set of in-page section anchors so the header always has
 * navigation during early development.
 */
function martha_default_primary_menu() {
	$links = array(
		'#about'   => __( 'About', 'martha' ),
		'#events'  => __( 'Events', 'martha' ),
		'#runners' => __( 'Runners', 'martha' ),
		'#donate'  => __( 'Donate', 'martha' ),
		'#contact' => __( 'Contact', 'martha' ),
	);

	echo '<ul id="primary-menu" class="site-header__menu">';
	foreach ( $links as $href => $label ) {
		printf(
			'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
			esc_url( $href ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
