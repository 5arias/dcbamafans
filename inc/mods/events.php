<?php
/**
 * DC Bama Fans Events and Venues.
 *
 * @package DC_Bama_Fans
 */


/*=====================================================================================
  Event Custom Post Type
  
  @link http://github.com/jjgrainger/wp-custom-post-type-class/
======================================================================================*/

// Register 'chapter_event' using CPT class
$chapter_events = new CPT( 
	'chapter_event', 
	array(
		'labels'		 => array( 'menu_name' => 'Events' ),
		'menu_position'	 => 5,
		'menu_icon'		 => 'dashicons-calendar-alt',
		'supports' 		 => array('title', 'thumbnail')
	)
);


/*
 * Remove submenu pages to clean up the Event CPT admin menu
 *
 */ 
add_action( 'admin_menu', 'remove_chapter_events_submenu_pages', 999 );

function remove_chapter_events_submenu_pages() {
	remove_submenu_page( 'edit.php?post_type=chapter_event', 'post-new.php?post_type=chapter_event' );
}



//Add Columns and Headers
$chapter_events->columns(array(
    'cb' 	    	=> '<input type="checkbox" />',
    'title' 		=> __('Event'),
    'chapter_event_venue' 	=> __('Location'),
    'chapter_event_start' 	=> __('Start Date & Time'),
    'chapter_event_end'		=> __('End Date & Time'),
    'chapter_event_website' => __('Event Website')
));


//Populate Location column
$chapter_events->populate_column('chapter_event_venue', function($column, $post) {
	
	$venue = get_field('chapter_event_venue');
    echo $venue->post_title;

});

//Populate Start Date & Time column
$chapter_events->populate_column('chapter_event_start', function($column, $post) {
	
    echo get_field('chapter_event_start_date_time');

});

//Populate End Date & Time column
$chapter_events->populate_column('chapter_event_end', function($column, $post) {
	
    echo get_field('chapter_event_end_date_time');

});

//Populate Event Website column
$chapter_events->populate_column('chapter_event_website', function($column, $post) {
	
    echo get_field('chapter_event_website');

});



$chapter_events->sortable(array(
    'chapter_event_venue' => array('chapter_event_venue', false),
    'chapter_event_start' => array('chapter_event_start_date_time', true),
    'chapter_event_end'   => array('chapter_event_end_date_time', true)
));


/*=====================================================================================
  Game Viewings Custom Post Type
  
  @link http://github.com/jjgrainger/wp-custom-post-type-class/
======================================================================================*/

$game_viewing = new CPT(
	'game_viewing',
	array (
		'show_in_menu' 	=> 'edit.php?post_type=chapter_event',		// Positions as submenu item of the Event CPT
		'supports'		=> array('')
	)
);


//Add Columns and Headers
$game_viewing->columns(array(
    'cb' 	    	=> '<input type="checkbox" />',
    'gameday_home' 	=> __(''),
    'title' 		=> __('Opponent'),
    'gameday_date' 	=> __('Game Day'),
    'gameday_time' 	=> __('Game Time'),
    'gameday_type'	=> __('Game Type'),
    'gameday_venue' => __('Venue')
));



//Populate Home or Away column
$game_viewing->populate_column('gameday_home', function($column, $post) {

    echo get_field('gameday_home') === 'yes' ? 'vs':'@';

});


/*
 * Populate Date column
 * 
 * @var string gameday_date
 * 
 */
$game_viewing->populate_column('gameday_date', function($column, $post) {
	
    // get raw date
	$date = get_field('gameday_date', false, false);

	// make date object
	$date = new DateTime($date);
	
	//Return Proper Format
	echo $date->format('F d\, Y');

});


//Populate Game Time column
$game_viewing->populate_column('gameday_time', function($column, $post) {
	
    echo get_field('gameday_time');

});


//Populate Game Type column
$game_viewing->populate_column('gameday_type', function($column, $post) {
	$field = get_field_object('gameday_type');
	$value = get_field('gameday_type');
	$label = $field['choices'][ $value ];
	echo $label;
});


//Populate Venue column
$game_viewing->populate_column('gameday_venue', function($column, $post) {
	
    $venue = get_field('gameday_venue');
    echo $venue->post_title;

});



$game_viewing->sortable(array(
    'gameday_home'  => array('gameday_home', false),
    'gameday_date'  => array('gameday_date', true),
    'gameday_time'  => array('gameday_time', true),
    'gameday_type'  => array('gameday_type', false),
	'gameday_venue' => array('gameday_venue', false)
));



/**
 * Save post title and slug when a Game Viewing post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 */
function save_game_viewing_title( $post_id, $post ) {
	
	//Check for Autosave and Prevent
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	
	//Check for valid post type or Prevent
	if ( $post->post_type !== 'game_viewing') return;
    
    //Get Opponent metadata using ACF field
    $opponent = get_post_meta($post_id, 'gameday_opponent', true);
    
    //Get Home or Away status using ACF field
    $home_away = get_post_meta($post_id, 'gameday_home', true) === 'yes' ? 'vs':'at';
    
    //Create slug for use in permalink
    $slug = sanitize_title('alabama-' . $home_away . '-' . $opponent);



    // - Update the post's title and slug.
    if ( isset($opponent) && isset($home_away)) {
        
		// unhook this function so it doesn't loop infinitely
		remove_action( 'wp_insert_post', 'save_game_viewing_title', 99, 2 );
		
		// update the post_name for slug and permalink, which calls wp_insert_post again
		wp_update_post( array( 'ID' => $post_id, 'post_name' => $slug ) );
		
		// update the post_title using the opponent, which calls wp_insert_post again
		wp_update_post( array( 'ID' => $post_id, 'post_title' => $opponent ) );

		// re-hook this function
		add_action( 'wp_insert_post', 'save_game_viewing_title', 99, 2 );
    }
}
add_action( 'wp_insert_post', 'save_game_viewing_title', 99, 2 );







/*=====================================================================================
  Venue Custom Post Type
  
  @link http://github.com/jjgrainger/wp-custom-post-type-class/
======================================================================================*/

$venue = new CPT(
	'venue',
	array (
		'show_in_menu' 	=> 'edit.php?post_type=chapter_event',		// Positions as submenu item of the Event CPT
		'supports'		=> array('title')
	)
);

//Add Columns and Headers
$venue->columns(array(
    'cb' => '<input type="checkbox" />',
    'title' => __('Venue Name'),
    'location' => __('Location'),
    'phone' => __('Phone'),
    'website' => __('Website'),
    'date' => __('Date')
));



//Populate Columns with ACF field Data
$venue->populate_column('location', function($column, $post) {

    echo get_field('venue_address') . '<br/>';
    echo get_field('venue_city') . ', ' . get_field('venue_state') . ' ' . get_field('venue_postal');

});


//Populate Phone Number column
$venue->populate_column('phone', function($column, $post) {
	
    echo get_field('venue_phone');

});


//Populate Website Column
$venue->populate_column('website', function($column, $post) {
	
    echo get_field('venue_website');

});



$venue->sortable(array(
    'location' => array('venue_city', false),
    'phone' => array('venue_phone', true),
    'website' => array('venue_website', true)
));


