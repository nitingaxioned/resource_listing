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
# // Coustom post types
###################################

add_action('init', 'custom_post_types');

function custom_post_types() {
	register_post_type('gallery', array(
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
          'name' => 'Gallery',
          'add_new_item' => 'Add New Gallery',
          'edit_item' => 'Edit Gallery',
          'all_items' => 'All Gallerys',
          'singular_name' => 'Gallery',
        ),
        'has_archive' => true,
        'publicly_queryable' => true,
        'menu_position' => 5,
        'rewrite' => array('slug'=>'Gallery'),
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array('title','author','excerpt','thumbnail')
    ));

    //for gallery
	register_taxonomy('gallery-cat', 'gallery', array(
	  'hierarchical' => true,
	  'labels' => array(
		'name' => _x( 'Categorys', 'taxonomy general name' ),
		'singular_name' => _x( 'Category', 'taxonomy singular name' ),
		'menu_name' => 'Gallery Categorys'
	  ),
	  'rewrite'       => true, 
	  'query_var'     => true 
	));
}


###################################
# // Custom Functions
###################################

function showpostByRef($obj) {
    $obj->the_post(); 
    $title = get_the_title();
    $link = get_permalink();
    if (has_post_thumbnail()) {
        $img_url = get_the_post_thumbnail_url();
        $alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    }
    ?>
        <li>
            <a title='<?php echo $title; ?>' href="<?php echo $link; ?>">
                <img src='<?php echo $img_url; ?>' alt='<?php echo $alt; ?>'>
                <div class="hover-box">
                    <h3><?php echo $title; ?></h3>
                </div>
            </a>
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
            'filter' => 'filter',
        )
    );
}

// hooks for filter ajax
add_action( 'wp_ajax_filter', 'filter_ajax' );
add_action( 'wp_ajax_nopriv_filter', 'filter_ajax' );

// callback for filter_ajax
function filter_ajax(){
	$cat_id = $_POST['id'];
    $paged = $_POST['paged'];
    $queryArr = array(
		'posts_per_page' => 9,
		'post_type' => 'gallery',
        'post_status' => array('publish'),
		);
    if ($cat_id != 'all') {
        $queryArr['tax_query'] = array(
            array(
                'taxonomy' => 'gallery-cat',
                'field' => 'term_id',
                'terms' => $cat_id
            ),
        );
    }
    if($paged > 1) {
        $queryArr['paged'] = $paged+2;
        $queryArr['posts_per_page'] = 3;

    }
    $res = new wp_Query($queryArr);
    if ($res->found_posts < 1) {
        ?>
        <li><h5>Nothing Found :(</h5></li>
        <?php
        die();
    } else {
        while ( $res->have_posts() ) { 
            showpostByRef($res);
        }
    }
	die();
}