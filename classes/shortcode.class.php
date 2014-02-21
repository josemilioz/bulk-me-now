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

class BulkMeNow_Shortcode {
	
	/**
	 * The settings object
	 *
	 * @access private
	 */
	var $set;
	
	/**
	 * The current shortcode id in the rendered page
	 *
	 * @access private
	 */
	var $sc_id = 1;
	
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
		$this->set = $bulkmenow_settings;
		
		add_action( "init", array( &$this, 'add_button' ) );
		
		add_shortcode( 'bmn', array( &$this, 'handler' ) );
		add_shortcode( 'bulk-me-now', array( &$this, 'handler' ) );
		add_shortcode( 'bulk_me_now', array( &$this, 'handler' ) );
		add_shortcode( 'bulkmenow', array( &$this, 'handler' ) );
	}
	
	/**
	 * The handler and form insertion
	 *
	 * @param array $attributes The options loaded in the shortcode
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function handler( $attributes )
	{
		global $bulkmenow_externals;
		
		// Defining defaults in case we don't have attributes
		extract( shortcode_atts( array(
			'fields' => NULL,
			'class' => NULL,
			'button' => NULL,
		), $attributes ) );
		
		$current_array = ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id] ) ) ? TRUE : FALSE;

		$fields = ( isset( $fields ) ) ? explode( ",", str_replace( " ", "", trim( $fields ) ) ) : FALSE;
		$all_fields = ( is_array( $fields ) AND in_array( "all", $fields ) ) ? "all" : FALSE;
		
		$values = array(
			'name' 		=> ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id]['name'] ) ) ? 		$_POST["bulkmenow_shortcode"][$this->sc_id]['name'] 	: NULL,
			'city' 		=> ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id]['city'] ) ) ? 		$_POST["bulkmenow_shortcode"][$this->sc_id]['city'] 	: NULL,
			'country' 	=> ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id]['country'] ) ) ? 	$_POST["bulkmenow_shortcode"][$this->sc_id]['country'] 	: NULL,
			'email' 	=> ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id]['email'] ) ) ? 		$_POST["bulkmenow_shortcode"][$this->sc_id]['email'] 	: NULL,
			'message' 	=> ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id]['message'] ) ) ? 	$_POST["bulkmenow_shortcode"][$this->sc_id]['message'] 	: NULL,
			'error' 	=> ( isset( $_POST["bulkmenow_shortcode"][$this->sc_id]['error'] ) ) ? 		$_POST["bulkmenow_shortcode"][$this->sc_id]['error'] 	: FALSE,
		);
		
		$recaptcha_width_adjust = ( $this->set->options->bulkmenow_recaptcha_image_width ) ? '<style type="text/css"> .recaptcha_image { width: ' . $this->set->options->bulkmenow_recaptcha_image_width . 'px !important; height: auto !important; } .recaptcha_image > img { width: ' . $this->set->options->bulkmenow_recaptcha_image_width . 'px; height: auto !important; } </style>' : '';
		
		ob_start();
		require( dirname( dirname( __FILE__ ) ) . "/views/shortcode.template.php" );
		$this->sc_id++; //Add numbers for the next shortcode id.
		return ob_get_clean();
		//ob_end_clean();
	}
	
	/**
	 * The function to display the dynamically generated names
	 *
	 * @param string $name The input name
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function get_field_name( $name )
	{
		return "bulkmenow_shortcode[" . $this->sc_id . "][" . $name . "]";
	}

	/**
	 * The function to display the dynamically generated ids
	 *
	 * @param string $id The input id
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function get_field_id( $id )
	{
		return "bulkmenow_shortcode_" . $this->sc_id . "_" . $id;
	}
	
	/**
	 * The function to display the dynamically generated ids
	 *
	 * @param none
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function add_button()
	{
		if( current_user_can( 'edit_posts' ) AND current_user_can( 'edit_pages' ) )
		{
			add_filter( 'mce_buttons', array( &$this, 'register_button' ) );
			add_filter( 'mce_external_plugins', array( &$this, 'register_tinymce_plugin' ) );
		}
	}
	
	/**
	 * The function to display the dynamically generated ids
	 *
	 * @param none
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function register_button( $buttons )
	{
		array_push( $buttons, "bulkmenow" );
		return $buttons;
	}
	
	/**
	 * The function to display the dynamically generated ids
	 *
	 * @param none
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function register_tinymce_plugin( $plugin_array )
	{
		$plugin_array['bulkmenow'] = plugins_url( "/assets/js/shortcode.js", dirname( __FILE__ ) );
		return $plugin_array;
	}
}

$bulkmenow_shortcode = new BulkMeNow_Shortcode;

?>