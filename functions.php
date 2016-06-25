<?php
/**
 * DC Bama Fans functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package DC_Bama_Fans
 */
 
 
/*=====================================================================================
  Initialize Theme Setup
  @since dcbamafans 1.0.0
======================================================================================*/
require('inc/theme_setup.php');



/*=====================================================================================
 * 
 * START ADDITIONAL CUSTOMIZATIONS BELOW THIS SECTION
 * 
======================================================================================*/

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dcbamafans_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dcbamafans' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'dcbamafans' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'dcbamafans_widgets_init' );
