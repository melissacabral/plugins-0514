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
}

/**
 * Flush Permalinks when this plugin activates
 */
function rad_rewrite_flush(){
	rad_setup_products();  //the function above that set up the CPT
	flush_rewrite_rules();  //re-builds the .htaccess rules
}
register_activation_hook( __FILE__ , 'rad_rewrite_flush'  );

