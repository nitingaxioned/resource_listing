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

//used to display list of filterd resource in ajax call
function showPostByRef($obj) {
    $obj->the_post(); 
    $title = get_the_title();
    $excerpt = get_the_excerpt();
    if (has_post_thumbnail()) {
        $img_url = get_the_post_thumbnail_url();
        $alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    }
    $link = get_permalink();
    ?>
        <li><?php
            if ( has_post_thumbnail() ) {?>
				<img src='<?php echo $img_url; ?>' alt='<?php echo $alt; ?>'>
			<?php } 
            if ( $title ) {?>
				<h3><?php echo $title; ?></h3>
			<?php } 
			if ( $excerpt ) {?>
				<p><?php echo $excerpt; ?></p>
			<?php } ?>
            <a title="Read More" href="<?php echo $link; ?>"><button class='btn'>Read More</button></a>
        </li>
    <?php
}


###################################
# // Ajax Functions
###################################

// Scripts for ajax
add_action( 'wp_enqueue_scripts', 'ajax_script' );
function ajax_script() {
    // for ajax
    wp_enqueue_script( 'ajax-script', get_theme_file_uri('/js/script.js'), array('jquery') );
	wp_localize_script(
        'ajax-script',
        'ajax_object',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'hook' => 'filter',
        )
    );
}

// hooks for filter ajax
add_action( 'wp_ajax_filter', 'filter_ajax' );
add_action( 'wp_ajax_nopriv_filter', 'filter_ajax' );

// callback for filter_ajax
function filter_ajax(){
	$id_iam = $_POST['id_iam'];
	$id_looking = $_POST['id_looking'];
    $paged = $_POST['paged'];
    $tax_query = array('relation' => 'AND');
    if ( $id_iam ) {
        $tax_query[] =  array(
                'taxonomy' => 'resource-cat-iam',
                'field' => 'term_id',
                'terms' => $id_iam
            );
    }
    if ($id_looking) {
        $tax_query[] =  array(
                'taxonomy' => 'resource-cat-looking',
                'field' => 'term_id',
                'terms' => $id_looking
            );
    }
    $queryArr = array(
		'posts_per_page' => 9,
		'post_type' => 'resource',
        'post_status' => array('publish'),
        'tax_query' => $tax_query,
	);
    if ($paged > 1) {
        $queryArr['paged'] = $paged+2;
        $queryArr['posts_per_page'] = 3;
    }
    $res = new wp_Query($queryArr);
    if ($res->found_posts < 1 && $paged == 1) {
        ?>
        <li><h5>Nothing Found :(</h5></li>
        <?php
        die();
    } else {
        while ( $res->have_posts() ) { 
            showPostByRef($res);
        }
    }
    $queryArr['posts_per_page'] = -1;
    $res = new wp_Query($queryArr);
    $paged+=2;
    $found_posts = $res->found_posts;
    $max_paged = ceil($found_posts/3);
    if($max_paged <= $paged){
        ?>
        <li class='hide-me'><p>Last Element</p></li>
        <?php
    }
	die();
}