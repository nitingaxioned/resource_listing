<?php

###################################
# //Enqueue Theme scripts & style
###################################

add_action( 'wp_enqueue_scripts', 'load_script_css' );
function load_script_css() {
    // for css
	wp_enqueue_style('main_style',get_theme_file_uri('/css/style.css'));

    // for jQuery
	wp_enqueue_script('jquery_mini_cdn','https://code.jquery.com/jquery-3.6.0.min.js');
}


###################################
# // Theme supports 
###################################

// to display thumbnail img
add_theme_support( 'post-thumbnails' );

// to display custom logo
add_theme_support( 'custom-logo', array(
	'header-text' => array('site-tittle', 'site-description'),
	'height' => 50,
	'width' => 200,
    'flex-height'  => true,
    'flex-width' => true,
	)
);


###################################
# // register menues
###################################

register_nav_menus(
    array(
        'primary-menu' => 'header menu',
    )
);


###################################
# // register sidebar
###################################

// register sidebar for footer
add_action( 'widgets_init', 'register_custom_sidebar' );

function register_custom_sidebar(){
 register_sidebar(array(
 'name' => 'Footer widget',
 'id' => 'footer-widget',
 'description' => 'Custom Sidebar for footer',
 'before_title' => '<h4>',
 'after_title' => '</h4>',
 ));
}


###################################
# // Coustom post types Resources
###################################

add_action('init', 'custom_post_types');

function custom_post_types() {
	register_post_type('resource', array(
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
          'name' => 'Resource',
          'add_new_item' => 'Add New Resource',
          'edit_item' => 'Edit Resource',
          'all_items' => 'All Resources',
          'singular_name' => 'Resource',
        ),
        'has_archive' => true,
        'publicly_queryable' => true,
        'menu_position' => 5,
        'rewrite' => array('slug'=>'Resource'),
        'menu_icon' => 'dashicons-plus-alt',
        'supports' => array('title','author','excerpt','thumbnail')
    ));

    //taxonomy for resource
	register_taxonomy('resource-cat-iam', 'resource', array(
	  'hierarchical' => true,
	  'labels' => array(
		'name' => _x( 'I am a', 'taxonomy general name' ),
		'singular_name' => _x( 'I am', 'taxonomy singular name' ),
		'menu_name' => 'I AM A'
	  ),
	  'rewrite'       => true, 
	  'query_var'     => true 
	));

    register_taxonomy('resource-cat-looking', 'resource', array(
        'hierarchical' => true,
        'labels' => array(
          'name' => _x( 'Looking for', 'taxonomy general name' ),
          'singular_name' => _x( 'Looking for', 'taxonomy singular name' ),
          'menu_name' => 'LOOKING FOR'
        ),
        'rewrite'       => true, 
        'query_var'     => true 
      ));
}


###################################
# // Custom Functions
###################################

// used to display list of LOOKING FOR & I AM A taxonomy names
function show_taxonomy($arr) {
    foreach($arr as $val) {
        if ( $val->name ) {?>
            <span><?php echo $val->name; ?></span>
        <?php } 
    }
}




###################################
# // Ajax Functions
###################################
