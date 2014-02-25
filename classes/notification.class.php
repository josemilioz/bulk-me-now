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

class BulkMeNow_Notification {

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
		add_action( 'admin_footer', array( &$this, 'show_unread_on_menu' ) );
		add_action( 'admin_bar_menu', array( &$this, 'admin_bar' ), 70 );
		
		//Declarations
		add_action( 'bmn_daily_notifications', array( &$this, 'notify_daily' ) );
		add_action( 'bmn_weekly_notifications', array( &$this, 'notify_weekly' ) );
		add_action( 'bmn_monthly_notifications', array( &$this, 'notify_monthly' ) );
		
	}
	
	/**
	 * Draws the little counter for new messages on the main menu link
	 * 
	 * @param none
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public static function show_unread_on_menu()
	{
		global $bulkmenow_model;
		if( get_option( 'bulkmenow_version' ) ) :
		$messages = $bulkmenow_model->count_according_status( 1 ); ?>
		<script type="text/javascript" charset="utf-8">
			jQuery(function($){
				<?php if( $messages > 0 ) : ?>
				$( "a.toplevel_page_bulkmenow-list .wp-menu-name" ).append( ' <span class="awaiting-mod"><span class="pending-count"><?php echo $messages; ?></span></span>' );
				<?php endif; ?>
				$( "li#wp-admin-bar-bulkmenow > a.ab-item span.ab-label" ).text( '<?php echo $messages; ?>' );
				$( ".toplevel_page_bulkmenow-list .wp-submenu li:nth-child(3) a" ).removeAttr( "href" );
				
				/* ACTIVATE WHEN WE SOLVE THIS IN CSS FIRST */
				$( ".toplevel_page_bulkmenow-list" ).removeClass( 'menu-icon-generic' );
				$( ".toplevel_page_bulkmenow-list > *" ).removeClass( 'menu-icon-generic' );				
				$( ".toplevel_page_bulkmenow-list > .wp-menu-image" ).addClass( 'dashicons dashicons-email' );
				$( ".toplevel_page_bulkmenow-list > .wp-submenu li:nth-child(3)" ).css( "display", "none" );
			});
		</script>
		<?php
		endif;
	}

	/**
	 * Adds buttons for the plugin to the admin bar
	 *
	 * @param string $screen The option suffix
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function admin_bar()
	{
		global $wp_admin_bar, $bulkmenow_model, $bulkmenow_externals;
	
	 	if( ! $wp_admin_bar ) return;
	    if( ! is_admin_bar_showing() ) return;

		$count = $bulkmenow_model->count_according_status( 1 );
	
		if( $bulkmenow_externals->current_user_can_see( 'messages' ) AND get_option( 'bulkmenow_version' ) )
		{
			$wp_admin_bar->add_menu(
				array(
					'id' => 'bulkmenow',
					'title' => '<span class="ab-icon dashicons dashicons-email" style="margin-top: 2px;"></span><span class="ab-label">' . $count . '</span>',
					'class' => 'ab-item',
					'href' => admin_url( 'admin.php?page=bulkmenow-list' )
				)
			);
		}
	}
	
	/**
	 * Prepares the notification
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function prepare_notification()
	{
		global $bulkmenow_settings, $bulkmenow_model, $bulkmenow_message;
		
		$vars = array(
			'counter' 	=> $bulkmenow_model->count_according_status( 1 ),
			'site_name'	=> get_option( 'blogname' ),
			'site_url'	=> get_option( 'siteurl' ),
			'admin_url'	=> site_url( 'wp-admin' ),
			'bmn_url'	=> "http://metamorpher.net/",
			'wp_url'	=> "http://wordpress.org/",
			'date'		=> date( get_option( 'date_format' ) . " " . get_option( 'time_format' ), current_time( 'timestamp' ) )
		);
		
		extract( $vars );
		
		if( $bulkmenow_settings->options->bulkmenow_activate_notifications AND $counter > 0 )
		{
			$theme_notification_file = get_stylesheet_directory() . "/bmn-email-notification.php";
			
			if( is_file( $theme_notification_file ) )
			{
				ob_start();
				require( $theme_notification_file );
				$message = ob_get_clean();
			}
			else
			{
				ob_start();
				require( dirname( dirname( __FILE__ ) ) . "/views/notification.template.php" );
				$message = ob_get_clean();
			}
			
			// Let's prepare the recipients
			
			$receivers = explode( "\n", trim( $bulkmenow_settings->options->bulkmenow_emails_notify ) );
			
			if( ! empty( $receivers ) AND is_array( $receivers ) )
			{
				foreach( $receivers AS $r )
				{
					$r = trim( $r );
					if( ! empty( $r ) AND is_email( $r ) )
					{
						$bulkmenow_message->send_reply( $r, $bulkmenow_settings->options->bulkmenow_emails_subject, $message );
					}
				}
			}
		}
	}
	
	/**
	 * Daily notify
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function notify_daily()
	{
		global $bulkmenow_settings;
		if( $bulkmenow_settings->options->bulkmenow_emails_frequency == "daily" ) $this->prepare_notification();
	}

	/**
	 * Weekly notify
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function notify_weekly()
	{
		global $bulkmenow_settings;
		if( $bulkmenow_settings->options->bulkmenow_emails_frequency == "weekly" ) $this->prepare_notification();
	}

	/**
	 * Monthly notify
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	public function notify_monthly()
	{
		global $bulkmenow_settings;
		if( $bulkmenow_settings->options->bulkmenow_emails_frequency == "monthly" ) $this->prepare_notification();
	}
	
}

$bulkmenow_notification = new BulkMeNow_Notification;
?>