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


class BulkMeNow_Options {
	
	/**
	 * The model object
	 *
	 * @access private
	 */
	var $db;

	/**
	 * The external object
	 *
	 * @access private
	 */
	var $ext;

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
		global $bulkmenow_model, $bulkmenow_externals, $bulkmenow_settings;
		
		$this->db = $bulkmenow_model;
		$this->ext = $bulkmenow_externals;
		$this->set = $bulkmenow_settings;
		
		add_action( 'admin_menu', array( &$this, 'register' ) );
	}
	
	/**
	 * Registers the page
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function register()
	{
		if( BulkMeNow_Externals::current_user_can_see( 'options' ) )
		{
			if( BulkMeNow_Externals::current_user_can_see( 'messages' ) )
			{
				$page = add_submenu_page(
					'bulkmenow-list',
					__( "Options", "bulk" ), 
					__( "Options", "bulk" ),
					'read',
					'bulkmenow-options', 
					array( &$this, 'render' ) 
				);
			}
			else
			{
				$page = add_menu_page(
					__( "Bulk Me Now! Options", "bulk" ), 
					__( "Messages", "bulk" ),
					'read',
					'bulkmenow-list', 
					array( &$this, 'render' ) 
				);
			}
			
			add_action( 'admin_print_scripts-' . $page, array( 'BulkMeNow_Externals', 'load_scripts' ) );
			add_action( 'admin_print_styles-' . $page, array( 'BulkMeNow_Externals', 'load_styles' ) );
		}
	}
	
	/**
	 * Renders the page
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function render()
	{
		__( "Bulk Actions" );
		
		if( $_POST AND check_admin_referer( 'bulkmenow_update_options' ) )
		{
			$this->restore_options( TRUE );
			
			foreach( $_POST as $k => $v )
			{
				$qk = "/" . $k . "/";
				if( strpos( $qk , 'bulkmenow' ) )
				{
					// Always put the admin in the array, no matter what.
					if( strpos( $qk, '_roles_options' ) )
					{
						if( empty( $v ) OR is_null( $v ) OR ! isset( $v ) OR ! is_array( $v ) )
						{
							$v = array();
							$v[] = "administrator";
						}
						else
						{
							if( ! in_array( 'administrator', $v ) )
							{
								$v[] = "administrator";
							}
						}
					}

					update_option( $k, maybe_serialize( $v ) );
				}
			}

			if( $_POST['submit'] == "restore" )
			{
				$this->restore_options();
				$this->ext->set_advice_message( __( "Settings restored to factory defaults.", "bulk" ), "success" );
			}
			elseif( $_POST['submit'] == "save" )
			{
				$this->ext->set_advice_message( __( "Settings saved correctly.", "bulk" ), "success" );
			}
		}
		
		require( dirname( dirname( __FILE__ ) ) . '/views/options.template.php' );
	}
	
	/**
	 * Restores all the options saved in the DB
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function restore_options( $set_nulls = FALSE )
	{
		foreach( $this->set->options_defaults AS $k => $v )
		{
			if( $set_nulls == TRUE AND ! strpos( $k, "_roles_" ) ) $v = NULL;
			update_option( $k, maybe_serialize( $v ) );
		}
	}

}

$bulkmenow_options = new BulkMeNow_Options;
//$bulkmenow_options->restore_options();