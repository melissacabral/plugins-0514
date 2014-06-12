<?php
/*
Plugin Name: Company Info Options
Description:  Adds a settings page for collecting company contact information
Author: Melissa Cabral
Author URI: http://melissacabral.com
Version: 0.1
License: GPLv3
*/

/**
 * Add a page under admin > settings
 */
add_action( 'admin_menu', 'rad_settings_page' );
function rad_settings_page(){
	// title, menu label, capability, slug, callback for content
	add_options_page( 'Company Information Settings', 'Company Info', 
		'manage_options', 'rad-company-info', 'rad_options_content' );
}

/**
 * Callback for content
 */
function rad_options_content(){
	//capability check for security
	if( !current_user_can( 'manage_options' ) ){
		wp_die('You do not have permission to view this page');
	}else{
		require( plugin_dir_path( __FILE__ ) . 'rad-options-form.php'  );
	}
}

/**
 * Create a group of settings
 */
add_action('admin_init', 'rad_create_settings');
function rad_create_settings(){
	//group name, DB row name, sanitizing callback
	register_setting( 'rad_options_group', 'rad_options', 
		'rad_options_sanitize' );
}

/**
 * Sanitizing callback
 * @param $input = array of dirty data
 */
function rad_options_sanitize($input){
	//clean each item
	$input['phone'] = wp_filter_nohtml_kses( $input['phone'] );
	$input['email'] = wp_filter_nohtml_kses( $input['email'] );

	//allow <p> and <br> in address field
	$allowed_tags = array(  
			'br' => array(),
			'p' => array(),
	);
	$input['address'] = wp_kses( $input['address'], $allowed_tags );

	//all clean!  return the data for DB storage
	return $input;
}

/**
 * BONUS ROUND!  Shortcodes
 * allows you to drop the address, email or phone number into a post or page
 */
// [phone] shortcode
add_shortcode( 'phone', 'rad_phone_shortcode' );
function rad_phone_shortcode(){
	$values = get_option('rad_options');
	return $values['phone'];
}

//[email] shortcode
add_shortcode( 'email' , 'rad_email_shortcode' );
function rad_email_shortcode(){
	$values = get_option('rad_options');
	return '<a href="mailto:' . $values['email'] . '">Email Us</a>';
}

//[address] shortcode
add_shortcode('address', 'rad_address_shortcode');
function rad_address_shortcode(){
	$values = get_option('rad_options');
	return $values['address'];
}

//make widgets 'do shortcodes'
add_filter('widget_text', 'do_shortcode');
