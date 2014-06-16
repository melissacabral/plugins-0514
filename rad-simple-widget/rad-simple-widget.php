<?php 
/*
Plugin Name: Simple Widget
Description: The most basic widget example
Author: Melissa Cabral
Author URI: http://melissacabral.com
Version: 0.1
License: GPLv3
*/

/**
 * Register our widget so WP knows it exists
 */
add_action( 'widgets_init', 'rad_register_simple_widget' );
function rad_register_simple_widget(){
	register_widget( 'Rad_Simple_Widget' );
}
/**
 * Define our Widget Class
 */
class Rad_Simple_Widget extends WP_Widget{
	//basic options 
	function Rad_Simple_Widget(){
		$widget_settings = array(
			'classname' => 'simple-widget',
			'description' => 'A basic widget just for an example',
		);
		$control_settings = array(
			'id-base' => 'simple-widget',
			//'width' => 400, //width of admin panel form
		);
		//apply the settings to our widget class
		//(id-base, title, widget settings, control settings)
		$this->WP_Widget('simple-widget', 'Simple Widget', $widget_settings, 
			$control_settings);
	}

	//front-end display 
	// $args = array of settings from register_sidebar in the theme
	//$instance = array of settings from one particular instance of our widget
	function widget( $args, $instance ){
		extract($args);
		//get field values
		$title = $instance['title'];
		//more fields here

		//make the title filterable
		$title = apply_filters( 'widget_title' , $title );

		//begin output
		echo $before_widget;  	//opens a div or section or whatever
		echo $before_title;		//opens an h2, h3, or whatever
		echo $title;
		echo $after_title;		//closes h2, h3 or wharever
		?>
		<p>whatever you put here shows up on the front-end</p>
		<?php
		echo $after_widget; 	//closes a div or section or whatever

	}

	// sanitize and save settings
	//$new_instance = array of "dirty" data fields that needs sanitizing for one instance
	//$old_instance = array of previous field values for one instance
	function update( $new_instance, $old_instance ){
		//empty array to hold the cleaned data
		$instance = array();

		//go through each form field and sanitize
		$instance['title'] = wp_filter_nohtml_kses( $new_instance['title'] );
		//more fields here

		return $instance;
	}

	//admin panel form fields
	//$instance = array of current settings for one instance
	function form( $instance ){
		$defaults = array(
			'title' => 'Default Title!!!',
			//add more fields here
		);
		//apply the defaults
		$instance = wp_parse_args( (array) $instance, $defaults );

		//HTML for the form
		?>
		<p>
			<label>Title:</label>
			<input type="text" class="widefat" 
			name="<?php echo $this->get_field_name('title'); ?>" 
			id="<?php echo $this->get_field_id('title'); ?>" 
			value="<?php echo $instance['title']; ?>">
		</p>
		<?php
	}
}