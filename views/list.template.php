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

		<h2><div class="dashicons dashicons-email header-icon"></div><?php
			switch( $messages->status )
			{
				default:
				case "1": _e( "Unread Messages", "bulk" ); break;
				case "2": _e( "Read Messages", "bulk" ); break;
				case "3": _e( "Trashed Messages", "bulk" ); break;
			}
		?></h2>

		<?php $messages->ext->display_advice_messages(); ?>
						
		<?php if( BulkMeNow_Externals::current_user_can_see( 'messages' ) ) : ?>

		<?php $messages->views(); ?>
		<br class="clear" />
		
		<form method="get" id="messages-form">

			<?php $messages->search_box( _( "Search" ), "messages-search-box" ); ?>

			<input type="hidden" name="delete_all" value="<?php _e( "Are you sure you want to delete all the selected messages? This procedure CAN NOT BE UNDONE.", "bulk" ); ?>" />
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="status" value="<?php echo $messages->status; ?>" />

			<br class="clear" />

			<?php $messages->display(); ?>

		</form>
		
		<?php endif; ?>

	</div>
	