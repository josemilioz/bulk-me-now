<?php
/**
 * Plugin Name:		Bulk Me Now!
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

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) die( __( "Access Denied." ) );

load_plugin_textdomain( 'bulk', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );

global $wpdb, $bulkmenow_settings, $bulkmenow_model, $bulkmenow_externals;

$bulkmenow_settings = (object) array(
	'version' => '2.0',
	'tables' => (object) array(
		'messages' => $wpdb->prefix . 'bulkmenow_messages',
		'replies' => $wpdb->prefix . 'bulkmenow_replies',
	),
	'options_defaults' => (object) array(
		'bulkmenow_recaptcha_activate' 			=> NULL,
		'bulkmenow_recaptcha_public_key' 		=> NULL,
		'bulkmenow_recaptcha_private_key' 		=> NULL,
		'bulkmenow_recaptcha_image_width' 		=> NULL,
		'bulkmenow_ajax_setup' 					=> 1,
		'bulkmenow_avoid_jquery' 				=> NULL,
		'bulkmenow_scripts_bottom' 				=> 1,
		'bulkmenow_activate_reply' 				=> 1,
		'bulkmenow_default_subject' 			=> sprintf( __( 'Reply from %1$s [%2$s]', "bulk" ), get_bloginfo( 'blogname' ), get_bloginfo( 'url' ) ),
		'bulkmenow_disable_css' 				=> NULL,
		'bulkmenow_activate_mandatories' 		=> array( 'name', 'message' ),
		'bulkmenow_activate_notifications'		=> NULL,
		'bulkmenow_emails_notify' 				=> NULL,
		'bulkmenow_emails_frequency' 			=> NULL,
		'bulkmenow_roles_messages'				=> array( 'administrator', 'editor', 'author' ),
		'bulkmenow_roles_reply'					=> array( 'administrator', 'editor', 'author' ),
		'bulkmenow_roles_options'				=> array( 'administrator' ),
		'bulkmenow_success_answer'				=> __( "Message has been successfully sent.", "bulk" ),
		'bulkmenow_avoid_javascript'			=> NULL,
		'bulkmenow_uninstall_remove_all'		=> NULL,
		'bulkmenow_emails_subject' 				=> sprintf( __( 'New messages received in %1$s [%2$s]', "bulk" ), get_bloginfo( 'blogname' ), get_bloginfo( 'url' ) ),
		'bulkmenow_list_rows' 					=> 10,
	),
	'options' => (object) array(
		'bulkmenow_recaptcha_activate' 	   		=> get_option( 'bulkmenow_recaptcha_activate' ),
		'bulkmenow_recaptcha_public_key'   		=> get_option( 'bulkmenow_recaptcha_public_key' ),
		'bulkmenow_recaptcha_private_key'  		=> get_option( 'bulkmenow_recaptcha_private_key' ),
		'bulkmenow_recaptcha_image_width'  		=> get_option( 'bulkmenow_recaptcha_image_width' ),
		'bulkmenow_ajax_setup' 			   		=> get_option( 'bulkmenow_ajax_setup' ),
		'bulkmenow_avoid_jquery' 		   		=> get_option( 'bulkmenow_avoid_jquery' ),
		'bulkmenow_scripts_bottom' 		   		=> get_option( 'bulkmenow_scripts_bottom' ),
		'bulkmenow_activate_reply' 		   		=> get_option( 'bulkmenow_activate_reply' ),
		'bulkmenow_default_subject' 	   		=> get_option( 'bulkmenow_default_subject' ),
		'bulkmenow_disable_css' 		   		=> get_option( 'bulkmenow_disable_css' ),
		'bulkmenow_activate_mandatories'   		=> maybe_unserialize( get_option( 'bulkmenow_activate_mandatories' ) ),
		'bulkmenow_activate_notifications' 		=> get_option( 'bulkmenow_activate_notifications' ),
		'bulkmenow_emails_notify' 		   		=> get_option( 'bulkmenow_emails_notify' ),
		'bulkmenow_emails_frequency' 	   		=> get_option( 'bulkmenow_emails_frequency' ),
		'bulkmenow_roles_messages'		   		=> maybe_unserialize( get_option( 'bulkmenow_roles_messages' ) ),
		'bulkmenow_roles_reply'			   		=> maybe_unserialize( get_option( 'bulkmenow_roles_reply' ) ),
		'bulkmenow_roles_options'		   		=> maybe_unserialize( get_option( 'bulkmenow_roles_options' ) ),
		'bulkmenow_success_answer'		   		=> get_option( 'bulkmenow_success_answer' ),
		'bulkmenow_avoid_javascript'	   		=> get_option( 'bulkmenow_avoid_javascript' ),
		'bulkmenow_uninstall_remove_all'   		=> get_option( 'bulkmenow_uninstall_remove_all' ),
		'bulkmenow_emails_subject' 				=> get_option( 'bulkmenow_emails_subject' ),
		'bulkmenow_list_rows' 					=> get_option( 'bulkmenow_list_rows' ),
	)
);

require_once( dirname( __FILE__ ) . '/classes/externals.class.php' );
require_once( dirname( __FILE__ ) . '/classes/model.class.php' );
require_once( dirname( __FILE__ ) . '/classes/list.class.php' );
require_once( dirname( __FILE__ ) . '/classes/message.class.php' );
require_once( dirname( __FILE__ ) . '/classes/options.class.php' );
require_once( dirname( __FILE__ ) . '/classes/widget.class.php' );
require_once( dirname( __FILE__ ) . '/classes/shortcode.class.php' );
require_once( dirname( __FILE__ ) . '/classes/notification.class.php' );

?>