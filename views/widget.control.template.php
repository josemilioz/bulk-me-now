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

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">
			<span style="text-transform: uppercase; font-weight: bold;"><?php _e( 'Title', "bulk" ); ?></span>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</label>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'salutation' ); ?>">
			<span style="text-transform: uppercase; font-weight: bold;"><?php _e( "Salutation", "bulk" ); ?></span>
			<small style="color: #AAA;"><?php _e( "Accepts HTML. Leave blank to disable.", "bulk" ); ?></small>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'salutation' ); ?>" name="<?php echo $this->get_field_name( 'salutation' ); ?>" rows="4"><?php echo esc_attr( $instance['salutation'] ); ?></textarea>
		</label>

		<label for="<?php echo $this->get_field_id( 'autop' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'autop' ); ?>" name="<?php echo $this->get_field_name( 'autop' ); ?>" value="1" <?php checked( isset( $instance['autop'] ) ? $instance['autop'] : 0 ); ?>/>
			<?php _e( 'Automatically add paragraphs', "bulk" ); ?>
		</label>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'submit_title' ); ?>">
			<span style="text-transform: uppercase; font-weight: bold;"><?php _e( "Title for submit button", "bulk" ); ?></span>
			<input class="widefat" id="<?php echo $this->get_field_id( 'submit_title' ); ?>" name="<?php echo $this->get_field_name( 'submit_title' ); ?>" type="text" value="<?php echo esc_attr( $instance['submit_title'] ); ?>" />
		</label>
	</p>
	
	<p>
		<span style="text-transform: uppercase; font-weight: bold;"><?php _e( "Fields", 'bulk' ); ?></span>
		<small style="color: #AAA;"><?php _e( "Mandatory fields will be automatically selected, despite your choices.", "bulk" ); ?></small>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'name' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="1" <?php checked( isset( $instance['name'] ) ? $instance['name'] : 0 ); ?>/>
			<?php _e( 'Name', "bulk" ); ?>
		</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'city' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" value="1" <?php checked( isset( $instance['city'] ) ? $instance['city'] : 0 ); ?>/>
			<?php _e( 'City', "bulk" ); ?>
		</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'country' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'country' ); ?>" name="<?php echo $this->get_field_name( 'country' ); ?>" value="1" <?php checked( isset( $instance['country'] ) ? $instance['country'] : 0 ); ?>/>
			<?php _e( 'Country', "bulk" ); ?>
		</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'email' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="1" <?php checked( isset( $instance['email'] ) ? $instance['email'] : 0 ); ?>/>
			<?php _e( 'E-mail', "bulk" ); ?>
		</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'message' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>" value="1" <?php checked( isset( $instance['message'] ) ? $instance['message'] : 0 ); ?>/>
			<?php _e( 'Message', "bulk" ); ?>
		</label>
	</p>
	