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

class BulkMeNow_Model {
		
	/**
	 * Constant with the tables we're using.
	 *
	 * @access private
	 */
	
	var $tables;
	
	/**
	 * Constant with options
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

		$this->tables = $bulkmenow_settings->tables;
		$this->set = $bulkmenow_settings;
		
		add_action( 'wp', array( &$this, 'waiter_and_savior' ) );
		add_action( 'wp', array( &$this, 'ajax_messages_output' ) );
	}
	
	/**
	 * Checks whether a message has a reply attached or not
	 *
	 * @param int $message_id The id of the message
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function message_replied( $message_id )
	{
		global $wpdb;
		$query = $wpdb->query( $wpdb->prepare( 
			"SELECT * FROM " . $this->tables->replies . " WHERE message_id = %d AND date_sent != %s",
			$message_id,
			NULL
		) );
		if( $query > 0 ) return TRUE;
		return FALSE;
	}

	/**
	 * Gets the message that belongs to the given ID
	 *
	 * @param int $message_id The id of the message
	 * @return mixed boolean/array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function get_message( $message_id )
	{
		global $wpdb;
		$query_string = $wpdb->prepare( 
			"SELECT * FROM " . $this->tables->messages . " WHERE message_id = %d",
			$message_id
		);
		if( $wpdb->query( $query_string ) > 0 ) return $wpdb->get_row( $query_string, 'ARRAY_A' );
		return FALSE;
	}

	/**
	 * Gets the reply that belongs to the given message ID
	 *
	 * @param int $message_id The id of the message
	 * @return mixed boolean/array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function get_reply( $message_id )
	{
		global $wpdb;
		$query_string = $wpdb->prepare( 
			"SELECT * FROM " . $this->tables->replies . " WHERE message_id = %d",
			$message_id
		);
		if( $wpdb->query( $query_string ) > 0 ) return $wpdb->get_row( $query_string, 'ARRAY_A' );
		return FALSE;
	}
	
	/**
	 * Counts how many messages are stored with the given status
	 *
	 * @param int $status One of the three available statuses (1 = new, 2 = read, 3 = on trash)
	 * @return int
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function count_according_status( $status )
	{
		global $wpdb;
		$query_string = $wpdb->prepare(
			"SELECT message_id FROM " . $this->tables->messages . " WHERE status = %d",
			$status
		);
		return $wpdb->query( $query_string );
	}
	
	/**
	 * Moves the status of a message, according to the given action
	 *
	 * @param int $message_id The id or ids of the messages
	 * @param int $status The status to move the message or messages
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function move_message( $message_id, $status = 2 )
	{
		global $wpdb;
		
		$query_string = "UPDATE " . $this->tables->messages . " SET status = %d WHERE message_id ";
		
		if( is_array( $message_id ) )
		{
			$message_id = implode( ",", $message_id );
			$query_string .= "IN($message_id)";
			$query_string = sprintf( $query_string, $status );
		}
		else
		{
			$query_string .= "= %s";
			$query_string = $wpdb->prepare(
				$query_string,
				$status,
				$message_id
			);
		}
				
		if( $wpdb->query( $query_string ) ) return TRUE;
		return FALSE;
	}

	/**
	 * Deletes a message, and the associated reply
	 *
	 * @param int $message_id The id or ids of the messages
	 * @param int $status The statuses from the messages to delete
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function delete_message( $message_id = NULL, $status = NULL )
	{
		global $wpdb;
		
		if( ! empty( $message_id ) )
		{
			$query_string = "DELETE FROM " . $this->tables->messages . " WHERE message_id ";

			if( is_array( $message_id ) )
			{
				$message_id = implode( ",", $message_id );
				$query_string .= "IN($message_id)";
				$query_string = sprintf( $query_string, $status );
			}
			else
			{
				$query_string .= "= %s";
				$query_string = $wpdb->prepare(
					$query_string,
					$message_id
				);
			}
		}
		
		if( ! empty( $status ) )
		{
			$query_string = "DELETE FROM " . $this->tables->messages . " WHERE status = %d";
			$query_string = $wpdb->prepare(
				$query_string,
				$status
			);
		}
		
		if( $wpdb->query( $query_string ) ) return TRUE;
		return FALSE;
	}
	
	/**
	 * Stores message on the database
	 *
	 * @param array $data An associative array of elements to store. Be sure to use column names as keys.
	 * @return int/boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
/*
	public function save_message( array $data )
	{
		global $wpdb;
		
		if( empty( $data['message_id'] ) )
		{
			if( $wpdb->insert( $this->tables->messages, $data ) )
			{
				return $wpdb->insert_id;
			}
		}
		return FALSE;
	}
*/

	/**
	 * Saves reply message in the database and link it to the messages. If you define the 'reply_id' key, it will be updated with new data.
	 *
	 * @param int $message_id The id of the message where we are storing the reply to
	 * @param int $status The statuses from the messages to delete
	 * @param boolean $sending Let's specify if we're sending a reply, so we don't update the modification fields.
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function save_reply( $message_id, array $data, $sending = FALSE )
	{
		global $wpdb;
		
		if( empty( $data['message'] ) OR empty( $data['subject'] ) ) return FALSE;

		$data['message_id'] = $message_id;
		$user = wp_get_current_user();
		
		if( empty( $data['reply_id'] ) )
		{
			$data['user_creator'] = $user->ID;
			$data['date_created'] = current_time( 'mysql' );
			
			if( $sending )
			{
				$data['user_sender'] = $user->ID;
				$data['date_sent'] = current_time( 'mysql' );
			}
			
			if( $wpdb->insert( $this->tables->replies, $data ) )
			{
				return $wpdb->insert_id;
			}
		}
		else
		{
			if( $sending )
			{
				$reply = $this->get_reply( $message_id );
				
				if( ( ! empty( $data['subject'] ) OR ! empty( $data['message'] ) ) AND 
					( $data['subject'] != $reply['subject'] OR $data['message'] != $reply['message'] ) )
				{
					$data['user_modifier'] = $user->ID;
					$data['date_modified'] = current_time( 'mysql' );
				}
				
				if( empty( $reply['date_sent'] ) )
				{
					$data['user_sender'] = $user->ID;
					$data['date_sent'] = current_time( 'mysql' );
				}
				else
				{
					$data['user_last_sender'] = $user->ID;
					$data['date_last_sent'] = current_time( 'mysql' );
				}	
			}
			else
			{
				$data['user_modifier'] = $user->ID;
				$data['date_modified'] = current_time( 'mysql' );
			}

			if( $wpdb->update( $this->tables->replies, $data, array( 'message_id' => $data['message_id'] ) ) )
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * This is the function that waits for the sent messages and stores them
	 *
	 * @param none
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function waiter_and_savior()
	{
		global $wpdb, $bulkmenow_externals;

		if( $_POST )
		{
			$proceed = TRUE;

			// ReCAPTCHA Verification
			if( $this->set->options->bulkmenow_recaptcha_activate AND $this->set->options->bulkmenow_recaptcha_public_key )
			{
				if( $this->set->options->bulkmenow_recaptcha_private_key AND ! empty( $_POST['recaptcha_challenge_field'] ) AND ! empty( $_POST['recaptcha_response_field'] ) )
				{
					require_once( dirname( dirname( __FILE__ ) ) . '/recaptchalib.php' );

					$response = recaptcha_check_answer( $this->set->options->bulkmenow_recaptcha_private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'] );
					
					if( ! $response->is_valid )
					{
						$proceed = FALSE;
						
						switch( $response->error )
						{
							default:
							case "incorrect-captcha-sol": 		$bulkmenow_externals->set_advice_message( __( 'reCAPTCHA solve was incorrect.', "bulk" ), "danger" ); break;
							case "invalid-site-private-key": 	$bulkmenow_externals->set_advice_message( __( 'reCAPTCHA private key is not setup.', "bulk" ), "danger" ); break;
							case "invalid-request-cookie": 		$bulkmenow_externals->set_advice_message( __( 'reCAPTCHA challenge verifier is incorrect.', "bulk" ), "danger" ); break;
							case "captcha-timeout": 			$bulkmenow_externals->set_advice_message( __( 'reCAPTCHA timed out. Refresh the page.', "bulk" ), "danger" ); break;
							case "recaptcha-not-reachable": 	$bulkmenow_externals->set_advice_message( __( 'reCAPTCHA is unreachable.', "bulk" ), "danger" ); break;
						}
					}
				}
				else
				{
					$proceed = FALSE;
					$bulkmenow_externals->set_advice_message( "Incomplete reCAPTCHA", "danger" );
				}
				
				// Add compatibility with AJAX;
			}
			
			// For widgets
			
			if( isset( $_POST['widget-bulkmenow_widget'] ) AND wp_verify_nonce( $_POST['_wpnonce'], 'bulkmenow_do_send' ) )
			{
				foreach( $_POST['widget-bulkmenow_widget'] AS $k => $e )
				{
					$_POST['widget-bulkmenow_widget'][$k]['error'] = TRUE;
					$form = array();
					$form['name'] 		= ( ! empty( $e['name'] ) ) ?		strip_tags( $e['name'] )	: NULL;
					$form['city'] 		= ( ! empty( $e['city'] ) ) ?		strip_tags( $e['city'] ) 	: NULL;
					$form['country'] 	= ( ! empty( $e['country'] ) ) ?	strip_tags( $e['country'] ) : NULL;
					$form['email'] 		= ( ! empty( $e['email'] ) ) ?		strip_tags( $e['email'] ) 	: NULL;
					$form['message'] 	= ( ! empty( $e['message'] ) ) ?	strip_tags( $e['message'] )	: NULL;

					$form['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$form['date_sent'] 	= current_time( 'mysql' );
					$form['page_from'] 	= $_SERVER['HTTP_REFERER']; //$_POST['_wp_http_referer'];
					
					// Check mandatories
					if( ! empty( $this->set->options->bulkmenow_activate_mandatories ) AND is_array( $this->set->options->bulkmenow_activate_mandatories ) )
					{
						$are_empty = FALSE;
						
						foreach( $form AS $k => $v ) if( in_array( $k, $this->set->options->bulkmenow_activate_mandatories ) AND empty( $form[$k] ) ) $are_empty = TRUE;
						
						if( $are_empty )
						{
							$proceed = FALSE;
							$bulkmenow_externals->set_advice_message( __( "You didn't complete the mandatory fields. Complete them to send the message. Those are marked with a star (*).", "bulk" ), "danger" );
						}
					}
					
					// Check the email is an e-mail
					if( ! empty( $form['email'] ) AND ! is_email( $form['email'] ) )
					{
						$proceed = FALSE;
						$bulkmenow_externals->set_advice_message( __( "The e-mail you provided doesn't seem valid. Try again.", "bulk" ), "danger" );
					}
					
					if( ! empty( $form ) AND $proceed )
					{
						if( $wpdb->insert( $this->tables->messages, $form ) )
						{
							$_POST['widget-bulkmenow_widget'][$k]['error'] = FALSE;
							$bulkmenow_externals->set_advice_message( stripslashes( $this->set->options->bulkmenow_success_answer ), "success" );
							return true;
						}
					}
				}
				
				return false;
			}
			
			// For shortcodes
			
			if( isset( $_POST['bulkmenow_shortcode'] ) AND wp_verify_nonce( $_POST['_wpnonce'], 'bulkmenow_do_send' ) )
			{
				foreach( $_POST['bulkmenow_shortcode'] AS $k => $e )
				{
					$_POST['bulkmenow_shortcode'][$k]['error'] = TRUE;
					$form = array();
					$form['name'] 		= ( ! empty( $e['name'] ) ) ?		strip_tags( $e['name'] )	: NULL;
					$form['city'] 		= ( ! empty( $e['city'] ) ) ?		strip_tags( $e['city'] ) 	: NULL;
					$form['country'] 	= ( ! empty( $e['country'] ) ) ?	strip_tags( $e['country'] ) : NULL;
					$form['email'] 		= ( ! empty( $e['email'] ) ) ?		strip_tags( $e['email'] ) 	: NULL;
					$form['message'] 	= ( ! empty( $e['message'] ) ) ?	strip_tags( $e['message'] )	: NULL;

					$form['ip_address'] = $_SERVER['REMOTE_ADDR'];
					$form['date_sent'] 	= current_time( 'mysql' );
					$form['page_from'] 	= $_SERVER['HTTP_REFERER']; //$_POST['_wp_http_referer'];
					
					// Check mandatories
					if( ! empty( $this->set->options->bulkmenow_activate_mandatories ) AND is_array( $this->set->options->bulkmenow_activate_mandatories ) )
					{
						$are_empty = FALSE;
						
						foreach( $form AS $k => $v ) if( in_array( $k, $this->set->options->bulkmenow_activate_mandatories ) AND empty( $form[$k] ) ) $are_empty = TRUE;
						
						if( $are_empty )
						{
							$proceed = FALSE;
							$bulkmenow_externals->set_advice_message( __( "You didn't complete the mandatory fields. Complete them to send the message. Those are marked with a star (*).", "bulk" ), "danger" );
						}
					}
										
					// Check the email is an e-mail
					if( ! empty( $form['email'] ) AND ! is_email( $form['email'] ) )
					{
						$proceed = FALSE;
						$bulkmenow_externals->set_advice_message( __( "The e-mail you provided doesn't seem valid. Try again.", "bulk" ), "danger" );
					}
					
					if( ! empty( $form ) AND $proceed )
					{
						if( $wpdb->insert( $this->tables->messages, $form ) )
						{
							$_POST['bulkmenow_shortcode'][$k]['error'] = FALSE;
							$bulkmenow_externals->set_advice_message( stripslashes( $this->set->options->bulkmenow_success_answer ), "success" );
							return true;
						}
					}
				}
				
				return false;
			}
			
		}
		
		return false;
	}
		
	/**
	 * Display the messages given by the server, after completion of tasks
	 *
	 * @param none
	 * @return boolean
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function ajax_messages_output()
	{
		global $bulkmenow_externals;
		
		if( $this->set->options->bulkmenow_ajax_setup AND ( isset( $_POST['bulkmenow_shortcode'] ) OR isset( $_POST['widget-bulkmenow_widget'] ) ) )
		{
			$bulkmenow_externals->display_advice_messages();
			exit;
		}
	}
	
}

$bulkmenow_model = new BulkMeNow_Model;

?>