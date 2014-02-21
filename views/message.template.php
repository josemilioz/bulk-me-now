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
?>

	<div class="wrap">
		
		<h2>
			<div class="dashicons dashicons-email header-icon"></div><?php _e( "View Message", "bulk" ); ?>
			<a class="add-new-h2" href="<?php echo '?page=bulkmenow-list&status=' . $_REQUEST['status']; ?>"><?php _e( "Back to List", "bulk" ); ?></a>
			<?php if( $message ) : ?>
			<a class="add-new-h2 hide-mobile" href="<?php echo '?page=bulkmenow-list&status=1&button_action=mark_unread&message_id=' . $message['message_id']; ?>"><?php _e( "Mark as Unread", "bulk" ); ?></a>
			<a class="add-new-h2 hide-mobile" href="<?php echo '?page=bulkmenow-list&status=3&button_action=send_trash&message_id=' . $message['message_id']; ?>"><?php _e( "Send to Trash", "bulk" ); ?></a>
			<?php endif; ?>
		</h2>
		
		<?php if( $message ) : ?>
		
		<?php $list->views(); ?>
		<br class="clear" />

		<div id="message-board">

			<div id="info-column" class="column">
				
				<div class="rows name">
					<?php echo get_avatar( $message['email'], $avatar_size ); ?>
					<h5><?php _e( "From", "bulk" ); ?></h5>
					<p><?php echo ( ! empty( $message['name'] ) ) ? ucwords( $message['name'] ) : '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; ?></p>
				</div>
				
				<div class="rows city">
					<h5><?php _e( "City", "bulk" ); ?></h5>
					<p><?php echo ( ! empty( $message['city'] ) ) ? ucwords( $message['city'] ) : '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; ?></p>
				</div>
				
				<div class="rows country">
					<h5><?php _e( "Country", "bulk" ); ?></h5>
					<p><?php echo ( ! empty( $message['country'] ) ) ? ucwords( $message['country'] ) : '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; ?></p>
					<?php if( ! empty( $message['city'] ) OR ! empty( $message['country'] ) ) : 
							$search = ucwords( $message['city'] );
							$search.= ( ! empty( $search ) AND ! empty( $message['country'] ) ) ? ", " . ucwords( $message['country'] ) : ucwords( $message['country'] ); ?>
					<small><a href="http://www.google.com/maps/preview/search/<?php echo urlencode( $search ); ?>" target="_blank"><?php _e( "See where it is" ); ?></a></small>
					<?php endif; ?>
				</div>
				
				<div class="rows email">
					<h5><?php _e( "E-mail", "bulk" ); ?></h5>
					<p><?php echo ( ! empty( $message['email'] ) ) ? $message['email'] : '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; ?></p>
				</div>
				
				<div class="rows date">
					<h5><?php _e( "Date Sent", "bulk" ); ?></h5>
					<p><?php echo ( ! empty( $message['date_sent'] ) ) ? date( get_option( 'date_format' ) . " " . get_option( 'time_format' ), strtotime( $message['date_sent'] ) ) : '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; ?></p>
				</div>
				
				<div class="rows ip">
					<h5><?php _e( "IP Address", "bulk" ); ?></h5>
					<p><?php echo ( ! empty( $message['ip_address'] ) ) ? $message['ip_address'] : '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; ?></p>
					<?php if( ! empty( $message['ip_address'] ) ) : ?>
					<small><a href="http://www.ip-adress.com/ip_tracer/<?php echo $message['ip_address']; ?>" target="_blank"><?php _e( "See where it comes from" ); ?></a></small>
					<?php endif; ?>
				</div>
				
				<div class="rows page">
					<h5><?php _e( "Sent from", "bulk" ); ?></h5>
					<p>
						<?php if( ! empty( $message['page_from'] ) ) : ?>
						<a href="<?php echo site_url( $message['page_from'] ); ?>" target="_blank"><?php echo $message['page_from']; ?></a>
						<?php else: echo '<span class="empty">' . __( "Undefined", 'bulk' ) . '</span>'; endif;?>
					</p>
				</div>

			</div>

			<div id="message-column" class="column">
				<div class="rows message">
					<h5><?php _e( "Message", "bulk" ); ?></h5>
					<?php echo ( ! empty( $message['message'] ) ) ? wpautop( trim( $message['message'] ) ) : '<p><span class="empty">' . __( "Undefined", 'bulk' ) . '</span></p>'; ?>
				</div>
			</div>

		</div>

		<?php if( ! empty( $message['email'] ) AND $this->set->options->bulkmenow_activate_reply == 1 AND BulkMeNow_Externals::current_user_can_see( 'reply' ) ) : ?>
		
		<div id="reply-board">

			<h2><?php printf( __( "Reply to %s" ), $message['name'] ); ?></h2>
			
			<?php $this->ext->display_advice_messages(); ?>

			<form id="reply-form" method="post" class="column">

				<input type="hidden" name="email" id="email" value="<?php echo $message['email']; ?>" />
				<input type="hidden" name="message_id" id="message_id" value="<?php echo $message['message_id']; ?>" />
				<?php if( ! empty( $reply['reply_id'] ) ) : ?><input type="hidden" name="reply_id" id="reply_id" value="<?php echo $reply['reply_id']; ?>" /><?php endif; ?>

				<div class="rows subject">
					<h5><?php _e( "Subject" ); ?></h5>
					<p><input type="text" name="subject" id="subject" maxlength="255" value="<?php echo ( ! empty( $reply['subject'] ) ) ? $reply['subject'] : $this->set->options->bulkmenow_default_subject; ?>" /></p>
				</div>

				<div class="rows message">
					<h5 style="margin-bottom: 10px;"><?php _e( "Message" ); ?></h5>
					<?php wp_editor( $reply['message'], 'message', array( 'editor_css' => '<style type="text/css">
					.mceContentBody { max-width: 100% !important; width: 100% !important; min-width: 100% !important; }
					</style>' ) ); ?>
				</div>

				<p>
					<button type="submit" id="send" name="send" value="1" class="button button-primary button-large"><?php _e( "Send", "bulk" ); ?></button>
					<button type="submit" id="save" name="save" value="1" class="button button-large"><?php _e( "Save it for later", "bulk" ); ?></button>
					<button type="reset" class="button button-large"><?php _e( "Reset", "bulk" ); ?></button>
				</p>

			</form>

			<div id="reply-info" class="column">

				<?php if( ! empty( $reply['user_creator'] ) ) : $replier = get_user_by( 'id', $reply['user_creator'] ); ?>
			
				<div class="rows">
					<?php echo get_avatar( $replier->ID, $avatar_size ); ?>
					<h5><?php _e( "Created by", "bulk" ); ?></h5>
					<p><?php echo ( $replier->first_name ) ? $replier->first_name . " " . $replier->last_name : $replier->display_name; ?></p>
					<small><?php echo date( get_option( "date_format" ) . " / " . get_option( "time_format" ), strtotime( $reply['date_created'] ) );?></small>
				</div>
			
				<?php endif; if( ! empty( $reply['user_modifier'] ) ) : $modifier = get_user_by( 'id', $reply['user_modifier'] ); ?>
			
				<div class="rows">
					<?php echo get_avatar( $modifier->ID, $avatar_size ); ?>
					<h5><?php _e( "Last modification by", "bulk" ); ?></h5>
					<p><?php echo ( $modifier->first_name ) ? $modifier->first_name . " " . $modifier->last_name : $modifier->display_name; ?></p>
					<small><?php echo date( get_option( "date_format" ) . " / " . get_option( "time_format" ), strtotime( $reply['date_modified'] ) );?></small>
				</div>
				
				<?php endif; if( ! empty( $reply['user_sender'] ) ) : $sender = get_user_by( 'id', $reply['user_sender'] ); ?>
				
				<div class="rows">
					<?php echo get_avatar( $sender->ID, $avatar_size ); ?>
					<h5><?php _e( "Sent by", "bulk" ); ?></h5>
					<p><?php echo ( $sender->first_name ) ? $sender->first_name . " " . $sender->last_name : $sender->display_name; ?></p>
					<small><?php echo date( get_option( "date_format" ) . " / " . get_option( "time_format" ), strtotime( $reply['date_sent'] ) );?></small>
				</div>
				
				<?php endif; if( ! empty( $reply['user_last_sender'] ) ) : $last_sender = get_user_by( 'id', $reply['user_last_sender'] ); ?>
		
				<div class="rows">
					<?php echo get_avatar( $last_sender->ID, $avatar_size ); ?>
					<h5><?php _e( "Last sent by", "bulk" ); ?></h5>
					<p><?php echo ( $last_sender->first_name ) ? $last_sender->first_name . " " . $last_sender->last_name : $last_sender->display_name; ?></p>
					<small><?php echo date( get_option( "date_format" ) . " / " . get_option( "time_format" ), strtotime( $reply['date_last_sent'] ) );?></small>
				</div>
		
				<?php endif; ?>
				
				<div class="advice primary" style="margin-top: 0;">
					<p><?php printf( __( 'The address appearing in the e-mail about to be sent will be <strong>%1$s</strong> <a href="%2$s" target="_blank">[Change it]</a>', "bulk" ), get_bloginfo( 'admin_email' ), admin_url( 'options-general.php' ) ); ?></p>
				</div>
				
			</div>

		</div>
		
		<?php endif; else : ?>
			
		<div class="advice danger">
			<p><?php echo ( BulkMeNow_Externals::current_user_can_see( 'messages' ) ) ? __( 'The message you are trying to see does NOT exist.', "bulk" ) : __( 'You do NOT have enough permissions to see the messages.', "bulk" ); ?></p>
			<p><?php printf( __( '<a href="%1$s">Click here</a> to return to the list.', "bulk" ), 'admin.php?page=bulkmenow-list' . ( ( isset( $_REQUEST['status'] ) ) ? '&status=' . $_REQUEST['status'] : NULL ) ); ?></p>
		</div>
		<script type="text/javascript" charset="utf-8"> window.location = "admin.php?page=bulkmenow-list<?php echo ( isset( $_REQUEST['status'] ) ) ? '&status=' . $_REQUEST['status'] : NULL; ?>"; </script>
		
		<?php endif; ?>
		
	</div>
	