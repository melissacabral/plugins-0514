<?php 
/*
Plugin Name: Products Widget
Description: Shows a customizable number of products in a widget
Author: Melissa Cabral
Author URI: http://melissacabral.com
Version: 0.1
License: GPLv3
*/

/**
 * Attach stylesheet
 */
add_action( 'wp_enqueue_scripts', 'rad_products_style' );
function rad_products_style(){
	$style_path = plugins_url( 'rad-products.css', __FILE__ );
	wp_enqueue_style( 'rad-products-style', $style_path );
}


/**
 * Register our widget so WP knows it exists
 */
add_action( 'widgets_init', 'rad_register_products_widget' );
function rad_register_products_widget(){
	register_widget( 'Rad_Products_Widget' );
}
/**
 * Define our Widget Class
 */
class Rad_Products_Widget extends WP_Widget{
	//basic options 
	function Rad_Products_Widget(){
		$widget_settings = array(
			'classname' => 'products-widget',
			'description' => 'A customizable number of products',
		);
		$control_settings = array(
			'id-base' => 'products-widget',
			//'width' => 400, //width of admin panel form
		);
		//apply the settings to our widget class
		//(id-base, title, widget settings, control settings)
		$this->WP_Widget('products-widget', 'Products Widget', $widget_settings, 
			$control_settings);
	}

	//front-end display 
	// $args = array of settings from register_sidebar in the theme
	//$instance = array of settings from one particular instance of our widget
	function widget( $args, $instance ){
		extract($args);
		//get field values
		$title = $instance['title'];
		$number = $instance['number'];
		$show_excerpt = $instance['show_excerpt'];
		//more fields here

		//make the title filterable
		$title = apply_filters( 'widget_title' , $title );

		//create custom query to get X number of new products
		$products_query = new WP_Query( array(
			'post_type' => 'product',  //we registered this in a different plugin
			'showposts' => $number,
			'ignore_sticky_posts' => 1, //sticky posts add to the count, let's hide them.
		) );
		//loop through the custom query
		if( $products_query->have_posts() ){
			
			echo $before_widget;  	//opens a div or section or whatever
			echo $before_title;		//opens an h2, h3, or whatever
			echo $title;
			echo $after_title;		//closes h2, h3 or wharever
			echo '<ul>';
			while( $products_query->have_posts() ){
				$products_query->the_post();
				?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					
						<div class="product-info">
							<h3><?php the_title(); ?></h3>
							<?php if( $show_excerpt ){ ?>
								<p><?php the_excerpt(); ?></p>
							<?php } ?>
						</div>
					</a>
				</li>
				<?php
			} //end while
			echo '</ul>';
			echo $after_widget; 	//closes a div or section or whatever
		}//end if
		//clean up!
		wp_reset_postdata();

	} //end widget() function

	// sanitize and save settings
	//$new_instance = array of "dirty" data fields that needs sanitizing for one instance
	//$old_instance = array of previous field values for one instance
	function update( $new_instance, $old_instance ){
		//empty array to hold the cleaned data
		$instance = array();

		//go through each form field and sanitize
		$instance['title'] = wp_filter_nohtml_kses( $new_instance['title'] );
		$instance['number'] = wp_filter_nohtml_kses( $new_instance['number'] );
		$instance['show_excerpt'] = wp_filter_nohtml_kses( $new_instance['show_excerpt'] );
		//more fields here

		return $instance;
	}

	//admin panel form fields
	//$instance = array of current settings for one instance
	function form( $instance ){
		$defaults = array(
			'title' => 'New Products',
			'number' => '4',
			'show_excerpt' => 1,
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

		<p>
			<label>Number of Products:</label>
			<input type="number" class="widefat" step="1"
			name="<?php echo $this->get_field_name('number'); ?>" 
			id="<?php echo $this->get_field_id('number'); ?>" 
			value="<?php echo $instance['number']; ?>">
		</p>

		<p>			
			<input type="checkbox"
			name="<?php echo $this->get_field_name('show_excerpt'); ?>" 
			id="<?php echo $this->get_field_id('show_excerpt'); ?>" 
			value="1"
			<?php checked( $instance['show_excerpt'], 1 ); ?>>

			<label>Show a short description of product</label>
		</p>
		<?php
	}
}