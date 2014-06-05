<?php  
/*
Plugin Name: Custom Post Type for Products
Plugin URI: http://wordpress.melissacabral.com
Description: Adds the products section to the site
Author: Melissa Cabral
Author URI: http://melissacabral.com
Version: 0.1
License: GPLv3
*/


/**
 * Register the 'product' post type
 */
add_action( 'init', 'rad_setup_products' );
function rad_setup_products(){
	register_post_type( 'product', array(
		'public' 		=> 	true,
		'has_archive' 	=> 	true, 
		'menu_position' => 	5,
		'menu_icon'		=>	'dashicons-cart',
		'supports' 		=>	array( 'title', 'editor', 'thumbnail', 'excerpt', 
									'custom-fields' ),
		'labels' 		=> 	array(
			'name' 			=> 'Products',
			'singular_name' => 'Product',
			'not_found' 	=> 'No Products Found',
			'add_new_item' 	=> 'Add New Product',
			),
	) );

	// Add taxonomies to sort by brand and feature
	register_taxonomy( 'brand', 'product', array(
		'hierarchical' 	=> true,    //parent/child relationships, like categories
		'labels' 		=>	array(
			'name' 			=> 'Brands',
			'singular_name' => 'Brand',
			'add_new_item' 	=> 'Add New Brand',
			'search_items'  => 'Search Brands',
			'parent_item' 	=> 'Parent Brand',
		),
	) );
	register_taxonomy( 'feature', 'product', array(
		'hierarchical' 	=> false,    //flat list, like tags
		'labels' 		=> array(
			'name' 			=> 'Features',
			'singular_name' => 'Feature',
			'add_new_item' 	=> 'Add New Feature',
			'search_items'  => 'Search Features',
		),
	) );
}//end  function rad_setup_products

/**
 * Flush Permalinks when this plugin activates
 */
function rad_rewrite_flush(){
	rad_setup_products();  //the function above that set up the CPT
	flush_rewrite_rules();  //re-builds the .htaccess rules
}
register_activation_hook( __FILE__ , 'rad_rewrite_flush'  );

