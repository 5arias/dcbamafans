<?php
/**
 * Resume functions and definitions.
 *
 * @package DC_Bama_Fans
 * @since dcbamafans 1.0.0
 *
 * Thanks to the following sites for some great WP clean up functions.
 * @Matteo Spinelli - http://cubiq.org/clean-up-and-optimize-wordpress-for-your-next-theme
 *
 */
 
 if ( ! function_exists( 'dcbamafans_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dcbamafans_setup() {
	
	// launching operation cleanup
    add_action('init', 'dcbamafans_head_cleanup');
    
    // Remove generator name from RSS feeds (just for good measure even though we removed the feed links above)
	add_filter('the_generator', '__return_false');
	
	// Disable Admin Bar on Front End (it bothers me during development)
	add_filter('show_admin_bar','__return_false'); 
	
	// Add Title Tag support
	add_theme_support( 'title-tag' );
	
	// Makes theme available for translation.
	load_theme_textdomain( 'dcbamafans', get_template_directory() . '/languages' );


	// Enable support for Post Thumbnails on posts and pages. (shouldn't this be standard already? )
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'dcbamafans' ),
	) );

	//Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	//Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	
	// Adds Support for Custom Logo. Yay no more hardcoding or hacks!
	add_theme_support( 'custom-logo', array(
		'flex-height' => true,
		'flex-width'  => true
	));	
	
	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'dcbamafans_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'dcbamafans_setup' );




/*=====================================================================================
  The default wordpress head is a mess. Let's clean it up by removing all the junk we don't need.
  @since dcbamafans 1.0.0
======================================================================================*/
function dcbamafans_head_cleanup() {
	
	// Remove post, comment, and category feeds
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	
	// Remove Really Simple Discovery (aka EditURI) link
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove Windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove Page Shortlink
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	
	// Remove index link
	remove_action( 'wp_head', 'index_rel_link' );
	
	// Remove previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	
	// Remove start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	
	// Remove links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0);
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	
	// Remove WP version
	remove_action( 'wp_head', 'wp_generator' );
	
	//Remove Emoji Support (totally not gonna use that anytime soon)
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	
	// Disable REST API since it's not being used for this project
	//remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	//remove_action( 'rest_api_init', 'wp_oembed_register_route');
	//remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10);
	
}


/*=====================================================================================
  Remove Query Strings from Static Resources to prevent issues with caching and CDNs
  @link https://www.keycdn.com/blog/speed-up-wordpress/#caching
  @since dcbamafans 1.0.0
======================================================================================*/
function _remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );






/*=====================================================================================
  Remove hAtom filters from content container
  @since dcbamafans 1.0.0
======================================================================================*/
function remove_add_mod_hatom_data() {
    remove_filter( 'the_content', 'add_mod_hatom_data' );
}
add_action( 'wp_loaded', 'remove_add_mod_hatom_data' );






/*=====================================================================================
  Enqueue our fonts  
  @since dcbamafans 1.0.0
======================================================================================*/
function dcbamafans_fonts() {
	//Fonts
	//wp_enqueue_style('font-unicaone', '//fonts.googleapis.com/css?family=Unica+One' );
	//wp_enqueue_style('font-vollkorn', '//fonts.googleapis.com/css?family=Vollkorn:700,400italic,400' );
	wp_enqueue_style('font-fontawesome', get_template_directory_uri() . '/inc/vendors/font-awesome/font-awesome.min.css' );

}
add_action( 'wp_enqueue_scripts', 'dcbamafans_fonts' );





/*=====================================================================================
  Enqueue our scripts and styles
  @since dcbamafans 1.0.0
======================================================================================*/
function dcbamafans_scripts() {
	
	//Google Analytics
	//wp_enqueue_script('google-analytics', get_template_directory_uri() . '/inc/js/google_analytics.js', '', '', true);
	
	
	//Foundation for Sites 6.2.3
	wp_enqueue_style( 'dcbamafans-foundation-css', get_template_directory_uri() . '/inc/vendors/foundation/foundation.min.css' );
	wp_enqueue_script( 'dcbamafans-foundation-js', get_template_directory_uri() . '/inc/vendors/foundation/foundation.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'dcbamafans-what-input', get_template_directory_uri() . '/inc/vendors/foundation/what-input.js', array('jquery'), '', true );

	
	//CSS
	wp_enqueue_style( 'dcbamafans-style', get_stylesheet_uri() );
	
	//JS
	wp_enqueue_script( 'dcbamafans-navigation', get_template_directory_uri() . '/inc/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'dcbamafans-skip-link-focus-fix', get_template_directory_uri() . '/inc/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'dcbamafans-app', get_template_directory_uri() . '/inc/js/app.min.js', array('jquery'), '', true );

}
add_action( 'wp_enqueue_scripts', 'dcbamafans_scripts' );



/*=====================================================================================
  Advanced Custom Fields
  @version 4.4.7
  @since dcbamafans 1.0.0
======================================================================================*/
 
function acf_settings_uri( $path ) {
 
    // update path
    $path = get_stylesheet_directory() . '/inc/vendors/acf/';
    
    // return
    return $path;
    
}
 
// 1. customize ACF path
add_filter('acf/settings/path', 'acf_settings_uri');

// 2. customize ACF directory
add_filter('acf/settings/dir', 'acf_settings_uri');

// 3. Hide ACF field group menu item
//add_filter('acf/settings/show_admin', '__return_false');

// 4. Include ACF
include_once( get_stylesheet_directory() . '/inc/vendors/acf/acf.php' );





/*=====================================================================================
  Custom Classes
  @since dcbamafans 1.0.0
======================================================================================*/
/*
 * Singleton style self instantiated classes 
 */
 
//Abstract

//User Profile Mods
//require('classes/profilemods.php');


/*
 * Autoloads non-singleton classes such as CPT
 *
 * NOTE: the singleton classes need to stay above this function 
 * otherwise it will try to include classes like WP_List_Table too
 */
spl_autoload_register(function ($class_name) {
	$class_name = strtolower($class_name);
    include 'classes/' . $class_name . '.php';
});




/*=====================================================================================
  Additional Theme Requirements and Functions 
  @since dcbamafans 1.0.0
======================================================================================*/

// Implement the Custom Header feature.
require get_template_directory() . '/inc/mods/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/mods/template-tags.php';

// Custom functions that act independently of the theme templates.
require get_template_directory() . '/inc/mods/extras.php';

// Customizer additions.
require get_template_directory() . '/inc/mods/customizer.php';

// Load Jetpack compatibility file.
require get_template_directory() . '/inc/mods/jetpack.php';

 
 
 
 
 
 
 
 
 
 
 
 
 
 ?>