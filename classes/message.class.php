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


class BulkMeNow_Message {
	
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
	 * The function to register the message viewing page
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function register()
	{
		if( BulkMeNow_Externals::current_user_can_see( 'messages' ) )
		{
			$page = add_submenu_page(
				'bulkmenow-list',
				__( "View Message", "bulk" ), 
				__( "View Message", "bulk" ),
				'read',
				'bulkmenow-message',
				array( &$this, 'render' )
			);

			add_action( 'admin_print_scripts-' . $page, array( 'BulkMeNow_Externals', 'load_scripts' ) );
			add_action( 'admin_print_styles-' . $page, array( 'BulkMeNow_Externals', 'load_styles' ) );
		}
	}
	
	/**
	 * The function that renders the viewing message page.
	 * This function also saves the reply associated to that message.
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function render()
	{
		if( $_POST )
		{
			$sending = ( ! empty( $_POST['send'] ) ) ? TRUE : FALSE;

			$email = $_POST['email']; unset( $_POST['save'], $_POST['send'], $_POST['email'] );

			if( $this->db->save_reply( $_POST['message_id'], $_POST, $sending ) )
			{
				$this->ext->set_advice_message( __( 'The reply has been saved to the database.', "bulk" ), 'success' );

				if( $sending AND $this->send_reply( $email, $_POST['subject'], $_POST['message'] ) )
				{
					$this->ext->set_advice_message( __( 'The reply has been successfully sent.', "bulk" ), 'success' );
				}
			}
			else
			{
				if( empty( $_POST['message'] ) OR empty( $_POST['subject'] ) )
				{
					$this->ext->set_advice_message( __( 'You should complete the subject and message in order to send or store the reply.', "bulk" ), 'danger' );
				}
				else
				{
					$this->ext->set_advice_message( __( 'The reply could not been sent or saved. Try again.', "bulk" ), 'danger' );
				}
			}
		}

		$list = new BulkMeNow_List;
		
		$message = FALSE;

		if( BulkMeNow_Externals::current_user_can_see( 'messages' ) ) $message = ( isset( $_GET['message_id'] ) ) ? $this->db->get_message( $_GET['message_id'] ) : FALSE;

		if( ! empty( $message ) )
		{
			if( $message['status'] == 1 ) $this->db->move_message( $message['message_id'], 2 );

			$reply = $this->db->get_reply( $_GET['message_id'] );

			$avatar_size = 44;
		}

		require( dirname( dirname( __FILE__ ) ) . '/views/message.template.php' );
	}
	
	/**
	 * Sends the reply e-mail
	 *
	 * @param string $to The recipient address
	 * @param string $subject The mail subject
	 * @param string $message The message body
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function send_reply( $to, $subject, $message )
	{
		$header = array(
			'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) . '>',
			'Reply-To: ' . get_bloginfo( 'admin_email' ),
			'Content-type: text/html; charset=' . strtolower( get_option( 'blog_charset' ) ),
			'MIME-Version: 1.0',
			'X-Mailer: PHP/' . phpversion()
		);
		
		$header = implode( "\n", $header );
		
		return mail( 
			$to,
			$subject,
			$message,
			$header
		);
	}
	
}

$bulkmenow_message = new BulkMeNow_Message;

?>