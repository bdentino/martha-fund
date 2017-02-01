<?php
/**
 * Martha functions and definitions
 *
 * @package Martha
 */


if ( ! function_exists( 'martha_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function martha_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Martha, use a find and replace
	 * to change 'martha' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'martha', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Content width
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1170; /* pixels */
	}

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('martha-large-thumb', 830);
	add_image_size('martha-medium-thumb', 550, 400, true);
	add_image_size('martha-small-thumb', 230);
	add_image_size('martha-service-thumb', 350);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'martha' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'martha_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // martha_setup
add_action( 'after_setup_theme', 'martha_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function martha_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'martha' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="section-title">',
		'after_title'   => '</h3>',
	) );

	//Footer widget areas
	$widget_areas = get_theme_mod('footer_widget_areas', '3');
	for ($i=1; $i<=$widget_areas; $i++) {
		register_sidebar( array(
			'name'          => __( 'Footer ', 'martha' ) . $i,
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="section-title">',
			'after_title'   => '</h3>',
		) );
	}

	//Register the front page widgets
	if ( function_exists('siteorigin_panels_activate') ) {
		register_widget( 'Martha_List' );
		register_widget( 'Martha_Services_Type_A' );
		register_widget( 'Martha_Services_Type_B' );
		register_widget( 'Martha_Facts' );
		register_widget( 'Martha_Fund_Facts' );
		register_widget( 'Martha_Clients' );
		register_widget( 'Martha_Testimonials' );
		register_widget( 'Martha_Skills' );
		register_widget( 'Martha_Action' );
		register_widget( 'Martha_Video_Widget' );
		register_widget( 'Martha_Social_Profile' );
		register_widget( 'Martha_Employees' );
		register_widget( 'Martha_Latest_News' );
		register_widget( 'Martha_Contact_Info' );
	}

}
add_action( 'widgets_init', 'martha_widgets_init' );

/**
 * Load the front page widgets.
 */
if ( function_exists('siteorigin_panels_activate') ) {
	require get_template_directory() . "/widgets/fp-list.php";
	require get_template_directory() . "/widgets/fp-services-type-a.php";
	require get_template_directory() . "/widgets/fp-services-type-b.php";
	require get_template_directory() . "/widgets/fp-facts.php";
	require get_template_directory() . "/widgets/fp-fund-facts.php";
	require get_template_directory() . "/widgets/fp-clients.php";
	require get_template_directory() . "/widgets/fp-testimonials.php";
	require get_template_directory() . "/widgets/fp-skills.php";
	require get_template_directory() . "/widgets/fp-call-to-action.php";
	require get_template_directory() . "/widgets/video-widget.php";
	require get_template_directory() . "/widgets/fp-social.php";
	require get_template_directory() . "/widgets/fp-employees.php";
	require get_template_directory() . "/widgets/fp-latest-news.php";
	require get_template_directory() . "/widgets/contact-info.php";
}

/**
 * Enqueue scripts and styles.
 */
function martha_scripts() {

	if ( get_theme_mod('body_font_name') !='' ) {
	    wp_enqueue_style( 'martha-body-fonts', '//fonts.googleapis.com/css?family=' . esc_attr(get_theme_mod('body_font_name')) );
	} else {
	    wp_enqueue_style( 'martha-body-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400italic,600');
	}

	if ( get_theme_mod('headings_font_name') !='' ) {
	    wp_enqueue_style( 'martha-headings-fonts', '//fonts.googleapis.com/css?family=' . esc_attr(get_theme_mod('headings_font_name')) );
	} else {
	    wp_enqueue_style( 'martha-headings-fonts', '//fonts.googleapis.com/css?family=Raleway:400,500,600');
	}

	wp_enqueue_style( 'martha-style', get_stylesheet_uri() );

	wp_enqueue_style( 'martha-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );

	wp_enqueue_style( 'martha-ie9', get_template_directory_uri() . '/css/ie9.css', array( 'martha-style' ) );
	wp_style_add_data( 'martha-ie9', 'conditional', 'lte IE 9' );

	wp_enqueue_script( 'martha-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'),'', true );

	wp_enqueue_script( 'martha-main', get_template_directory_uri() . '/js/main.js', array('jquery'),'', true );

	wp_enqueue_script( 'martha-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	wp_enqueue_script( 'martha-modernizr', get_template_directory_uri() . '/js/modernizr.js', array(''), '', true );
	wp_enqueue_style( 'martha-timeline-style', get_template_directory_uri() . '/css/timeline/style.css', array(), true );
	// wp_enqueue_style( 'martha-timeline-reset', get_template_directory_uri() . '/css/timeline/reset.css', array(), true );

	if ( get_theme_mod('blog_layout') == 'masonry-layout' && (is_home() || is_archive()) ) {
		wp_enqueue_script( 'martha-masonry-init', get_template_directory_uri() . '/js/masonry-init.js', array('jquery-masonry'),'', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'martha_scripts' );

/**
 * Enqueue Bootstrap
 */
function martha_enqueue_bootstrap() {
	wp_enqueue_style( 'martha-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.min.css', array(), true );
}
function martha_enqueue_hex_grid() {
	wp_enqueue_style( 'martha-hex-grid', get_template_directory_uri() . '/css/hex-grid.css', array(), true );
}
function martha_enqueue_lightslider() {
	wp_enqueue_style( 'martha-lightslider', get_template_directory_uri() . '/css/lightslider.css', array(), true );
}
function martha_enqueue_lemonade() {
	wp_enqueue_style( 'martha-lemonade', get_template_directory_uri() . '/css/lemonade.min.css', array(), true );
}
add_action( 'wp_enqueue_scripts', 'martha_enqueue_bootstrap', 9 );
add_action( 'wp_enqueue_scripts', 'martha_enqueue_hex_grid', 9 );
add_action( 'wp_enqueue_scripts', 'martha_enqueue_lemonade', 9 );
add_action( 'wp_enqueue_scripts', 'martha_enqueue_lightslider', 9 );

/**
 * Change the excerpt length
 */
function martha_excerpt_length( $length ) {

  $excerpt = get_theme_mod('exc_lenght', '55');
  return $excerpt;

}
add_filter( 'excerpt_length', 'martha_excerpt_length', 999 );

/**
 * Blog layout
 */
function martha_blog_layout() {
	$layout = get_theme_mod('blog_layout','classic');
	return $layout;
}

/**
 * Menu fallback
 */
function martha_menu_fallback() {
	echo '<a class="menu-fallback" href="' . admin_url('nav-menus.php') . '">' . __( 'Create your menu here', 'martha' ) . '</a>';
}

/**
 * Header image overlay
 */
function martha_header_overlay() {
	$overlay = get_theme_mod( 'hide_overlay', 0);
	if ( !$overlay ) {
		echo '<div class="overlay"></div>';
	}
}

/**
 * Polylang compatibility
 */
if ( function_exists('pll_register_string') ) :
function martha_polylang() {
	for ( $i=1; $i<=5; $i++) {
		pll_register_string('Slide title ' . $i, get_theme_mod('slider_title_' . $i), 'Martha');
		pll_register_string('Slide subtitle ' . $i, get_theme_mod('slider_subtitle_' . $i), 'Martha');
	}
	pll_register_string('Slider button text', get_theme_mod('slider_button_text'), 'Martha');
	pll_register_string('Slider button URL', get_theme_mod('slider_button_url'), 'Martha');
}
add_action( 'admin_init', 'martha_polylang' );
endif;

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Page builder support
 */
require get_template_directory() . '/inc/page-builder.php';

/**
 * Slider
 */
require get_template_directory() . '/inc/slider.php';

/**
 * Styles
 */
require get_template_directory() . '/inc/styles.php';

/**
 * Theme info
 */
require get_template_directory() . '/inc/theme-info.php';

/**
 * Woocommerce basic integration
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 *TGM Plugin activation.
 */
require_once dirname( __FILE__ ) . '/plugins/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'martha_recommend_plugin' );
function martha_recommend_plugin() {

    $plugins = array(
        array(
            'name'               => 'Page Builder by SiteOrigin',
            'slug'               => 'siteorigin-panels',
            'required'           => false,
        ),
        array(
            'name'               => 'Types - Custom Fields and Custom Post Types Management',
            'slug'               => 'types',
            'required'           => false,
        ),
    );

    tgmpa( $plugins);

}
