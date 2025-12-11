<?php

/**
 * Fossil functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Fossil
 */

$fossil_theme = wp_get_theme();

if (!defined('FOSSIL_THEME_VERSION')) {
	define('FOSSIL_THEME_VERSION', $fossil_theme->get('Version'));
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fossil_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Fossil, use a find and replace
		* to change 'fossil' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('fossil', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'main-menu' => esc_html__('Main Menu', 'fossil'),
			'footer-menu' => esc_html__('Footer Menu', 'fossil'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'fossil_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');
	// Add support for Block Styles.
	add_theme_support('wp-block-styles');

	// Add support for full and wide align images.
	add_theme_support('align-wide');
	add_theme_support("responsive-embeds");

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'fossil_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fossil_content_width()
{
	$GLOBALS['content_width'] = apply_filters('fossil_content_width', 1170);
}
add_action('after_setup_theme', 'fossil_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fossil_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'fossil'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'fossil'),
			'before_widget' => '<section id="%1$s" class="widget fossil-sidebar-item p-3 %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)

	);
	register_sidebar(
		array(
			'name'          => esc_html__('Footer Widget', 'fossil'),
			'id'            => 'footer-widget',
			'description'   => esc_html__('Add widgets here.', 'fossil'),
			'before_widget' => '<section id="%1$s" class="widget mb-5 p-3 %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)

	);
}
add_action('widgets_init', 'fossil_widgets_init');

/**
 * Register custom fonts.
 */
function fossil_fonts_url()
{
	$fossil_theme_fonts = get_theme_mod('fossil_theme_fonts', 'Outfit');
	$fossil_theme_font_head = get_theme_mod('fossil_theme_font_head', 'Outfit');

	$fonts_url = '';

	$font_families = array();

	$font_families[] = $fossil_theme_fonts . ':400,400i,500,500i,600,600i,700,700i';
	$font_families[] = $fossil_theme_font_head . ':400,400i,500,500i,600,600i,700,700i,900,900i';

	$query_args = array(
		'family' => urlencode(implode('|', $font_families)),
		'subset' => urlencode('latin,latin-ext'),
	);

	$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');


	return esc_url_raw($fonts_url);
}

/**
 * Enqueue scripts and styles.
 */
function fossil_scripts()
{
	wp_enqueue_style('fossil-google-font', fossil_fonts_url(), array(), null);
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '5.2.0', 'all');
	wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/all.css', array(), '5.15.3');
	wp_enqueue_style('fossil-default-style', get_template_directory_uri() . '/assets/css/default-style.css', array(), FOSSIL_THEME_VERSION, 'all');
	wp_enqueue_style('fossil-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), FOSSIL_THEME_VERSION, 'all');
	wp_enqueue_style('fossil-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), FOSSIL_THEME_VERSION, 'all');
	wp_enqueue_style('fossil-style', get_stylesheet_uri(), array(), FOSSIL_THEME_VERSION);


	wp_enqueue_script('jquery.sticky', get_template_directory_uri() . '/assets/js/jquery.sticky.js', array('jquery'), FOSSIL_THEME_VERSION, true);
	wp_enqueue_script('fossil-mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', array('jquery'), FOSSIL_THEME_VERSION, true);
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	wp_enqueue_script('fossil-main-scripts', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), FOSSIL_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'fossil_scripts');

/**
 * Implement Header parts
 */
require get_template_directory() . '/inc/header/header-parts.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';



/**
 * Load recommended plugins
 * 
 * @since 1.0.0
 */
require get_template_directory() . '/inc/recommended-plugin.php';

/**
 * Load breadcrumbs
 * 
 * @since 1.0.0
 */
require get_template_directory() . '/inc/fossil-breadcrumbs.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load Demo Notice Handler.
 */
require get_template_directory() . '/inc/class-demo-notice-handler.php';
require get_template_directory() . '/inc/theme-documentation.php';

/**
 * Filter the excerpt length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function fossil_custom_excerpt_length($length)
{
	if (is_admin()) {
		return $length;
	}
	return 20;
}
add_filter('excerpt_length', 'fossil_custom_excerpt_length', 999);

/**
 * Change excerpt more string
 *
 * @param string $more The string shown within the more link.
 * @return string
 */
function fossil_excerpt_more($more)
{
	if (is_admin()) {
		return $more;
	}
	return '...';
}
add_filter('excerpt_more', 'fossil_excerpt_more');
