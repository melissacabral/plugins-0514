<?php 
/*
Plugin Name: Corner Ribbon
Description: Adds a 'Sale' Banner to promote the shop
Author: Melissa Cabral
Plugin URI: http://wordpress.melissacabral.com
Author URI: http://melissacabral.com
Version: 0.1
License: GPLv3
*/

/**
 * HTML Output
 * @since 0.1
 */
add_action( 'wp_footer', 'rad_ribbon_html' );
function rad_ribbon_html(){
	//only display on front or blog pages
	if( is_front_page() OR is_home() ){
	?>
	<!-- Corner Ribbon Plugin by Melissa C -->
	<a href="#" id="rad-corner-ribbon">
		<img src="<?php echo plugins_url( 'images/corner-ribbon.png', __FILE__ ); ?>" alt="View items on sale">
	</a>
	<?php
	} //end if is front page or home
}

/**
 * Attach the style sheet to the user-facing side
 */
add_action( 'wp_enqueue_scripts', 'rad_ribbon_css' );
function rad_ribbon_css(){
	if( is_front_page() OR is_home() ){
		//css filepath
		$css_file = plugins_url( 'css/corner-ribbon.css', __FILE__ );
		//tell WP that this stylesheet exists
		wp_register_style( 'rad-ribbon-style', $css_file );
		//put it on the page
		wp_enqueue_style( 'rad-ribbon-style' );	
	}
}

