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

class BulkMeNow_Externals {

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
		
		register_activation_hook( dirname( dirname( __FILE__ ) ) . "/bootstrap.php" , array( &$this, 'install' ) );
		register_deactivation_hook( dirname( dirname( __FILE__ ) ) . "/bootstrap.php", array( &$this, 'uninstall' ) );
		add_filter( 'cron_schedules', array( &$this, 'cron_job_expand_interval' ) );
		
		add_action( 'init', array( &$this, 'register_files' ) );
		add_action( 'admin_head', array( &$this, 'load_tweak' ) );
		
		if( $bulkmenow_settings->options->bulkmenow_disable_css != '1' )
		{
			add_action( 'wp_print_styles', array( &$this, 'public_css' ) );
		}

		add_action( 'wp_print_scripts', array( &$this, 'public_js' ) );
	}
	
	/**
	 * Registers all external files into one global var.
	 * 
	 * @param non
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function register_files()
	{
		global $bulkmenow_settings;

		wp_register_style( 'bulkmenow-main', plugins_url( '/assets/css/main.css', dirname( __FILE__ ) ), NULL, $bulkmenow_settings->version );
		wp_register_style( 'bulkmenow-tweak', plugins_url( '/assets/css/tweak.css', dirname( __FILE__ ) ), NULL, $bulkmenow_settings->version );
		wp_register_style( 'bulkmenow-public', plugins_url( '/assets/css/public.css', dirname( __FILE__ ) ), NULL, $bulkmenow_settings->version );
		
		$bottom = ( $bulkmenow_settings->options->bulkmenow_scripts_bottom == "1" ) ? TRUE : FALSE;

		wp_register_script( 'bulkmenow-main', plugins_url( '/assets/js/main.js', dirname( __FILE__ ) ), array( 'jquery' ), $bulkmenow_settings->version, TRUE );
		wp_register_script( 'bulkmenow-public', plugins_url( '/assets/js/public.js', dirname( __FILE__ ) ), NULL, $bulkmenow_settings->version, $bottom );
		wp_register_script( 'bulkmenow-ajax', plugins_url( '/assets/js/ajax.js', dirname( __FILE__ ) ), array( 'bulkmenow-public' ), $bulkmenow_settings->version, $bottom );
	}
	
	/**
	 * Loads tweak css file, for the menu
	 * 
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function load_tweak()
	{
		wp_enqueue_style( 'bulkmenow-tweak' );
	}
	
	/**
	 * Loads all styles, on call
	 * 
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function load_styles()
	{
		wp_enqueue_style( 'bulkmenow-main' );
	}
	
	/**
	 * Loads all scripts, on call
	 * 
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function load_scripts()
	{
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		if( function_exists( 'add_thickbox' ) ) add_thickbox();
		wp_enqueue_script( 'bulkmenow-main' );		
	}

	/**
	 * Loads all public styles, on call
	 * 
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function public_css()
	{
		if( empty( $bulkmenow_settings->options->bulkmenow_disable_css ) )
		{
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'bulkmenow-public' );
		}
	}

	/**
	 * Loads all public styles, on call
	 * 
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function public_js()
	{
		global $bulkmenow_settings;
		
		if( empty( $bulkmenow_settings->options->bulkmenow_avoid_javascript ) )
		{
			if( empty( $bulkmenow_settings->options->bulkmenow_avoid_jquery ) )
			{
				wp_enqueue_script( 'jquery' );
			}
			
			wp_enqueue_script( 'bulkmenow-public' );

			if( ! empty( $bulkmenow_settings->options->bulkmenow_ajax_setup ) )
			{
				wp_enqueue_script( 'bulkmenow-ajax' );
			}
		}
	}
		
	/**
	 * Set the messages to display, according the type.
	 * Available types:
	 * primary, warning, info, success, danger
	 * 
	 * @param string $message The message to display
	 * @param string $type The type of message
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function set_advice_message( $message, $type = "info" )
	{
		$available_types = array( 'primary', 'warning', 'info', 'success', 'danger' );
		if( ! empty( $message ) AND in_array( $type, $available_types ) )
		{
			$this->advice_message[$type][] = "<p>" . $message . "</p>";
		}
	}

	/**
	 * Display all the stored messages.
	 * 
	 * @param none
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function display_advice_messages()
	{
		if( ! empty( $this->advice_message ) )
		{
			foreach( $this->advice_message AS $type => $values )
			{
				echo '<div class="advice ' . $type . '">';
				foreach( $values AS $m ) echo $m;
				echo '</div>';
			}
		}
	}
	
	/**
	 * Check if the current user can see the current screen or option
	 *
	 * @param string $screen The option suffix
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public static function current_user_can_see( $screen )
	{
		global $wp_roles, $bulkmenow_settings, $current_user; get_currentuserinfo();
		
		$rol = $wp_roles->roles;
		$opt = $bulkmenow_settings->options;
		
		$current_option = $opt->{ "bulkmenow_roles_" . $screen };
		$current_roles 	= $current_user->roles;
		
		$allow = FALSE;
		
		if( ! empty( $current_option ) AND ! empty( $current_roles ) )
		{
			foreach( $current_roles AS $r )
			{
				if( in_array( $r, $current_option ) ) $allow = TRUE;
			}
		}

		return $allow;
	}
	
	/**
	 * Installs the plugin, the database tables, the cron jobs, the first set of options
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function install()
	{
		global $bulkmenow_settings, $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table_messages = sprintf(
			'CREATE TABLE IF NOT EXISTS %1$s (
				`message_id` bigint(32) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(255) DEFAULT NULL,
				`city` varchar(255) DEFAULT NULL,
				`country` varchar(255) DEFAULT NULL,
				`email` varchar(255) DEFAULT NULL,
				`message` text,
				`date_sent` datetime DEFAULT NULL,
				`ip_address` varchar(20) DEFAULT NULL,
				`page_from` varchar(255) DEFAULT NULL,
				`status` tinyint(1) DEFAULT \'1\',
				PRIMARY KEY (`message_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
			$bulkmenow_settings->tables->messages,
			$bulkmenow_settings->tables->replies
		);

		$table_replies = sprintf(
			'CREATE TABLE IF NOT EXISTS %2$s (
				`reply_id` bigint(32) unsigned NOT NULL AUTO_INCREMENT,
				`message_id` bigint(32) unsigned NOT NULL,
				`subject` varchar(255) DEFAULT NULL,
				`message` text,
				`user_creator` bigint(20) unsigned DEFAULT NULL,
				`date_created` datetime DEFAULT NULL,
				`user_modifier` bigint(20) unsigned DEFAULT NULL,
				`date_modified` datetime DEFAULT NULL,
				`user_sender` bigint(20) unsigned DEFAULT NULL,
				`date_sent` datetime DEFAULT NULL,
				`user_last_sender` bigint(20) unsigned DEFAULT NULL,
				`date_last_sent` datetime DEFAULT NULL,
				PRIMARY KEY (`reply_id`),
				KEY `message_id` (`message_id`),
				CONSTRAINT %2$s_ibfk_1 FOREIGN KEY (`message_id`) REFERENCES %1$s (`message_id`) ON DELETE CASCADE ON UPDATE NO ACTION
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
			$bulkmenow_settings->tables->messages,
			$bulkmenow_settings->tables->replies
		);
		
		dbDelta( $table_messages );
		dbDelta( $table_replies );
		
		if( $bulkmenow_settings->version != get_option( 'bulkmenow_version' ) ) add_option( 'bulkmenow_version', $bulkmenow_settings->version );

		if( get_option( 'bulk_me_now_version' ) )
		{
			$this->migrate();
			delete_option( 'bulk_me_now_version' );
			add_option( 'bulkmenow_version', $bulkmenow_settings->version );
		}
		
		// Let's create the first set of options
		foreach( $bulkmenow_settings->options_defaults AS $k => $v )
		{
			if( ! get_option( $k ) ) add_option( $k, maybe_serialize( $v ) );
		}
		
		// Add notification cron jobs
		if( ! wp_next_scheduled( 'bmn_daily_notifications' ) ) wp_schedule_event( current_time( 'timestamp' ), 'daily', 'bmn_daily_notifications' );
		if( ! wp_next_scheduled( 'bmn_weekly_notifications' ) ) wp_schedule_event( current_time( 'timestamp' ), 'weekly', 'bmn_weekly_notifications' );
		if( ! wp_next_scheduled( 'bmn_monthly_notifications' ) ) wp_schedule_event( current_time( 'timestamp' ), 'monthly', 'bmn_monthly_notifications' );
		
		add_action( 'bmn_daily_notifications', array( 'BulkMeNow_Notification', 'notify_daily' ) );
		add_action( 'bmn_weekly_notifications', array( 'BulkMeNow_Notification', 'notify_weekly' ) );
		add_action( 'bmn_monthly_notifications', array( 'BulkMeNow_Notification', 'notify_monthly' ) );
		
		register_deactivation_hook( dirname( dirname( __FILE__ ) ) . "/bootstrap.php", array( &$this, 'uninstall' ) );
	}
	
	/**
	 * Moves all the data from previous bulk me now table and destroy the subsequent table
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function migrate()
	{
		global $wpdb, $bulkmenow_settings;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table = $wpdb->prefix . "bulk_me_now";
		
		$options_delete_query = sprintf( 'DELETE FROM %1$s WHERE %2$s LIKE %3$s', $wpdb->prefix . "options", "option_name", "'%bulk_me_now%'" );
		$wpdb->query( $options_delete_query );
				
		if( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE \'%1$s\'', $table ) ) == $table )
		{
			global $current_user;
			get_currentuserinfo();
			$user = $current_user;
			
			$query = $wpdb->prepare( 'SELECT * FROM %1$s ORDER BY %2$s ASC', $table, "id" );
			
			foreach( $wpdb->get_results( $query, 'ARRAY_A' ) AS $m )
			{
				$message = array();				
				$message['name'] 		= ( ! empty( $m['name'] ) ) 	? $m['name'] 							: NULL;
				$message['city'] 		= ( ! empty( $m['city'] ) ) 	? $m['city'] 							: NULL;
				$message['country'] 	= ( ! empty( $m['country'] ) ) 	? $m['country'] 						: NULL;
				$message['email'] 		= ( ! empty( $m['email'] ) ) 	? $m['email'] 							: NULL;
				$message['message'] 	= ( ! empty( $m['message'] ) ) 	? $m['message'] 						: NULL;
				$message['date_sent'] 	= ( ! empty( $m['date'] ) ) 	? date( "Y-m-d H:i:s", $m['date'] ) 	: NULL;
				$message['ip_address'] 	= ( ! empty( $m['ip'] ) ) 		? $m['ip']								: NULL;
				$message['page_from'] 	= ( ! empty( $m['page'] ) ) 	? $m['page']							: NULL;
				$message['status'] 		= ( ! empty( $m['status'] ) ) 	? $m['status']							: NULL;
				                                                                                           
				if( ! empty( $message ) ) $insert_id = $wpdb->insert( $bulkmenow_settings->tables->messages, $message );
				
				if( ! empty( $m['reply'] ) )
				{
					$reply = array();
					$reply['message_id'] 		= $insert_id;
					$reply['subject'] 			= ( ! empty( $m['reply_subject'] ) ) ? $m['reply_subject'] : NULL;
					$reply['message'] 			= $m['reply'];
					$reply['user_creator']		= $user->ID;
					$reply['date_created']		= current_time( 'mysql' );

					if( $m['reply_status'] == 1 )
					{
						$reply['user_sender']	= $user->ID;
						$reply['date_sent']		= current_time( 'mysql' );
					}

					$wpdb->insert( $bulkmenow_settings->tables->replies, $reply );
				}
			}
			
			$wpdb->query( sprintf( 'DROP TABLE IF EXISTS %1$s', $table ) );
		}		
	}

	/**
	 * Uninstall the plugin, erase everything, on demand
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function uninstall()
	{
		global $wpdb, $bulkmenow_settings;
		
		if( $bulkmenow_settings->options->bulkmenow_uninstall_remove_all )
		{
			foreach( $bulkmenow_settings->options AS $k => $v )
			{
				delete_option( $k );
			}
			
			$wpdb->query( sprintf( 'DROP TABLE IF EXISTS %s', $bulkmenow_settings->tables->messages ) );
			$wpdb->query( sprintf( 'DROP TABLE IF EXISTS %s', $bulkmenow_settings->tables->replies ) );
		}

		wp_clear_scheduled_hook( 'bmn_daily_notifications' );
		wp_clear_scheduled_hook( 'bmn_weekly_notifications' );
		wp_clear_scheduled_hook( 'bmn_monthly_notifications' );

		delete_option( 'bulkmenow_version' );
	}
		
	/**
	 * Adds new intervals for use with the mail notifications
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function cron_job_expand_interval()
	{
		return array(
			'weekly' => array(
				'interval' => 604800,
				'display' => 'Weekly'
			),
			'monthly' => array(
				'interval' => 2592000,
				'display' => 'Monthly'
			)
		);
	}
	
}

$bulkmenow_externals = new BulkMeNow_Externals;

?>