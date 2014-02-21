<?php
/**
 * Plugin:			Bulk Me Now!
 * Plugin URI:		http://metamorpher.net/bulk-me-now/
 * Description:		Adds a Contact Form Module for your blog, so you don't get your contact form into your e-mail but your WP admin area instead.
 * Author:			mEtAmorPher
 * Author URI:		http://metamorpher.net/
 * Version:			2.0
 * Text Domain:		bulk
 * Domain Path:		/lang
 *
 * Copyright 2014  mEtAmorPher  (email : metamorpher.py@gmail.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package bulk-me-now
 */

class BulkMeNow_Widget extends WP_Widget {
	
	/**
	 * The settings object
	 *
	 * @access private
	 */
	var $set;
	
	
	/**
	 * Class constructor
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function __construct()
	{
		global $bulkmenow_settings;
		
		$widget_ops = array( 
			'classname' 	=> 'widget_bulkmenow',
			'description' 	=> __( "A beautiful and simple contact form with the basic to go.", "bulk" )
		);
		
		parent::__construct( 'bulkmenow_widget', __( 'Bulk Me Now!', "bulk" ), $widget_ops );
		
		$this->set = $bulkmenow_settings;
	}
	
	/**
	 * The widget output function
	 *
	 * @param $args
	 * @param $instance
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function widget( $args, $instance )
	{
		global $bulkmenow_externals;
		extract( $args );
		
		$widget_index = explode( '-', $widget_id );

		$values = array(
			'name' 		=> ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]]['name'] ) ) ? 	$_POST["widget-" . $widget_index[0]][$widget_index[1]]['name'] 		: NULL,
			'city' 		=> ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]]['city'] ) ) ? 	$_POST["widget-" . $widget_index[0]][$widget_index[1]]['city'] 		: NULL,
			'country' 	=> ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]]['country'] ) ) ? $_POST["widget-" . $widget_index[0]][$widget_index[1]]['country'] 	: NULL,
			'email' 	=> ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]]['email'] ) ) ? 	$_POST["widget-" . $widget_index[0]][$widget_index[1]]['email'] 	: NULL,
			'message' 	=> ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]]['message'] ) ) ? $_POST["widget-" . $widget_index[0]][$widget_index[1]]['message'] 	: NULL,
			'error' 	=> ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]]['error'] ) ) ? 	$_POST["widget-" . $widget_index[0]][$widget_index[1]]['error'] 	: FALSE,
		);
		
		$current_array = ( isset( $_POST["widget-" . $widget_index[0]][$widget_index[1]] ) ) ? TRUE : FALSE;
		
		$recaptcha_width_adjust = ( $this->set->options->bulkmenow_recaptcha_image_width ) ? '<style type="text/css"> .recaptcha_image { width: ' . $this->set->options->bulkmenow_recaptcha_image_width . 'px !important; height: auto !important; } .recaptcha_image > img { width: ' . $this->set->options->bulkmenow_recaptcha_image_width . 'px; height: auto !important; } </style>' : '';
		
		echo $before_widget;
		require( dirname( dirname( __FILE__ ) ) . "/views/widget.template.php" );
		echo $after_widget;
	}

	/**
	 * The controller for the widget
	 *
	 * @param $instance
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function form( $instance )
	{
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> NULL, 
			'salutation'	=> NULL,
			'autop' 		=> NULL,
			'name' 			=> NULL,
			'city' 			=> NULL,
			'country' 		=> NULL,
			'email' 		=> NULL,
			'message' 		=> NULL,
			'submit_title'	=> NULL,
			'tags'			=> NULL,
		) );
		require( dirname( dirname( __FILE__ ) ) . "/views/widget.control.template.php" );
	}

	/**
	 * The controller updater
	 *
	 * @param $new_instance
	 * @param $old_instance
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		
		// What to do with default items.
		$new_instance = wp_parse_args( (array) $new_instance, array(
			'title' 		=> NULL, 
			'salutation'	=> NULL,
			'autop' 		=> NULL,
			'name' 			=> NULL,
			'city' 			=> NULL,
			'country' 		=> NULL,
			'email' 		=> NULL,
			'message' 		=> NULL,
			'submit_title'	=> NULL,
			'tags'			=> NULL,
		) );
		
		if( current_user_can( 'unfiltered_html' ) )
		{
			$instance['salutation'] = $new_instance['salutation'];
		}
		else
		{
			$instance['salutation'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['salutation'] ) ) ); // wp_filter_post_kses() expects slashed
		}

		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['autop'] 			= strip_tags( $new_instance['autop'] );
		$instance['name'] 			= strip_tags( $new_instance['name'] );
		$instance['city'] 			= strip_tags( $new_instance['city'] );
		$instance['country'] 		= strip_tags( $new_instance['country'] );
		$instance['email'] 			= strip_tags( $new_instance['email'] );
		$instance['message'] 		= strip_tags( $new_instance['message'] );
		$instance['submit_title'] 	= strip_tags( $new_instance['submit_title'] );
		$instance['tags'] 			= strip_tags( $new_instance['tags'] );

		// If mandatories are on, then let's put it on.
		foreach( $instance AS $k => $v )
		{
			if( in_array( $k, $this->set->options->bulkmenow_activate_mandatories ) ) $instance[$k] = 1;
		}

		return $instance;
	}
	
}

/**
 * The widget register
 *
 * @param $new_instance
 * @param $old_instance
 * @return void
 * @since 2.0
 * @author mEtAmorPher
 */

function bulkmenow_register_widget()
{
	register_widget( 'BulkMeNow_Widget' );
}
add_action( 'widgets_init', 'bulkmenow_register_widget' );

//$bulkmenow_widget = new BulkMeNow_Widget;

?>