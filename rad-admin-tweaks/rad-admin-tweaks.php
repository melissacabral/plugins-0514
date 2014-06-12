<?php 
/*
Plugin Name: Admin and Login Customization
Description: changes some stuff in the admin panel
Author: Melissa Cabral
Author URI: http://melissacabral.com
Version: 0.1
License: GPLv3
*/

/**
 * Embedded CSS for login and register screen
 */
add_action( 'login_head', 'rad_login_css' );
function rad_login_css(){
?>
	<style type="text/css">
		body{
			background-color: #696763;
		}
		.login h1 a{
			background-image:url(<?php echo plugins_url( 'images/logo.png', __FILE__ ); ?>);
			background-size: auto auto;
			width: auto;
		}
	</style>
<?php
}

/**
 * Change the login title link behavior so it goes to our home page instead of wordpress.org
 */
add_filter( 'login_headerurl', 'rad_login_title_url' );
function rad_login_title_url(){
	return home_url('/');
}

add_filter( 'login_headertitle', 'rad_login_tooltip' );
function rad_login_tooltip(){
	return 'Return to Awesome Co Home Page';
}

/**
 * Customize the logo icon on the admin bar
 */
add_action( 'wp_head', 'rad_ab_icon' );  /*user-side*/
add_action( 'admin_head', 'rad_ab_icon' ); /*admin-side*/

function rad_ab_icon(){
?>
	<style type="text/css">
		#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before{
			content: "\f155";  /*star*/
		}
	</style>
<?php 
}

/**
 * Remove some unwanted dashboard widget panels
 */
add_action( 'wp_dashboard_setup', 'rad_remove_dashboard_widgets' );

function rad_remove_dashboard_widgets(){
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}

/**
 * custom dashboard widget content
 */
add_action( 'wp_dashboard_setup' , 'rad_activate_dash_widget' );

function rad_activate_dash_widget(){
	//adds a custom widget (id, title, callback for content)
	wp_add_dashboard_widget( 'dashboard_rad_widget', 'Melissa\'s Developer Blog' , 
		'rad_dashboard_widget' );
}

function rad_dashboard_widget(){
	wp_widget_rss_output( array(
		'url' => 'http://wordpress.melissacabral.com/feed/',
		'items' => 5,
		'show_summary' => 1,
		'show_date' => 1,
		'show_author' => 0,
	) );
}
