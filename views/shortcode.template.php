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

	<div class="shortcode_bulkmenow<?php echo ( isset( $class ) ) ? " " . $class : NULL; ?>">

		<?php if( $current_array ) $bulkmenow_externals->display_advice_messages(); ?>

		<?php if( $fields != FALSE ) : ?>

		<form method="POST" accept-charset="utf-8">
		
			<?php wp_nonce_field( 'bulkmenow_do_send' ); ?>
		
			<?php if( $this->set->options->bulkmenow_ajax_setup ) : ?><input type="hidden" name="<?php echo $this->get_field_name( 'ajax_identification' ); ?>" /><?php endif; ?>
		
			<?php if( in_array( "name", $this->set->options->bulkmenow_activate_mandatories ) OR in_array( 'name', $fields ) OR $all_fields ) : ?>

			<div class="input input-name">
				<label for="<?php echo $this->get_field_id( 'name' ); ?>">
					<span class="screen-reader-text"><?php _e( "Name", "bulk" ); ?></span>
					<input type="text" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" placeholder="<?php _e( "Name", "bulk" ); ?>" value="<?php echo ( ! empty( $values['name'] ) AND $values['error'] ) ? $values['name'] : NULL; ?>" <?php if( in_array( "name", $this->set->options->bulkmenow_activate_mandatories ) ) echo 'aria-required="true"'; ?> />
					<?php if( in_array( "name", $this->set->options->bulkmenow_activate_mandatories ) ) : ?><span class="required">*</span><?php endif; ?>
				</label>
			</div>

			<?php endif; ?>

			<?php if( in_array( "city", $this->set->options->bulkmenow_activate_mandatories ) OR in_array( 'city', $fields ) OR $all_fields ) : ?>

			<div class="input input-city">
				<label for="<?php echo $this->get_field_id( 'city' ); ?>">
					<span class="screen-reader-text"><?php _e( "City", "bulk" ); ?></span>
					<input type="text" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" placeholder="<?php _e( "City", "bulk" ); ?>" value="<?php echo ( ! empty( $values['city'] ) AND $values['error'] ) ? $values['city'] : NULL; ?>" <?php if( in_array( "city", $this->set->options->bulkmenow_activate_mandatories ) ) echo 'aria-required="true"'; ?> />
				</label>
				<?php if( in_array( "city", $this->set->options->bulkmenow_activate_mandatories ) ) : ?><span class="required">*</span><?php endif; ?>
			</div>

			<?php endif; ?>

			<?php if( in_array( "country", $this->set->options->bulkmenow_activate_mandatories ) OR in_array( 'country', $fields ) OR $all_fields ) : ?>

			<div class="input input-country">
				<label for="<?php echo $this->get_field_id( 'country' ); ?>">
					<span class="screen-reader-text"><?php _e( "Country", "bulk" ); ?></span>
					<input type="text" id="<?php echo $this->get_field_id( 'country' ); ?>" name="<?php echo $this->get_field_name( 'country' ); ?>" placeholder="<?php _e( "Country", "bulk" ); ?>" value="<?php echo ( ! empty( $values['country'] ) AND $values['error'] ) ? $values['country'] : NULL; ?>" <?php if( in_array( "country", $this->set->options->bulkmenow_activate_mandatories ) ) echo 'aria-required="true"'; ?> />
				</label>
				<?php if( in_array( "country", $this->set->options->bulkmenow_activate_mandatories ) ) : ?><span class="required">*</span><?php endif; ?>
			</div>

			<?php endif; ?>

			<?php if( in_array( "email", $this->set->options->bulkmenow_activate_mandatories ) OR in_array( 'email', $fields ) OR $all_fields ) : ?>

			<div class="input input-email">
				<label for="<?php echo $this->get_field_id( 'email' ); ?>">
					<span class="screen-reader-text"><?php _e( "E-mail", "bulk" ); ?></span>
					<input type="text" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" placeholder="<?php _e( "E-mail", "bulk" ); ?>" value="<?php echo ( ! empty( $values['email'] ) AND $values['error'] ) ? $values['email'] : NULL; ?>" <?php if( in_array( "email", $this->set->options->bulkmenow_activate_mandatories ) ) echo 'aria-required="true"'; ?> />
				</label>
				<?php if( in_array( "email", $this->set->options->bulkmenow_activate_mandatories ) ) : ?><span class="required">*</span><?php endif; ?>
			</div>

			<?php endif; ?>

			<?php if( in_array( "message", $this->set->options->bulkmenow_activate_mandatories ) OR in_array( 'message', $fields ) OR $all_fields ) : ?>

			<div class="input input-message">
				<label for="<?php echo $this->get_field_id( 'message' ); ?>">
					<span class="screen-reader-text"><?php _e( "Message", "bulk" ); ?></span>
					<textarea id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>" placeholder="<?php _e( "Message", "bulk" ); ?>" <?php if( in_array( "message", $this->set->options->bulkmenow_activate_mandatories ) ) echo 'aria-required="true"'; ?>><?php echo ( ! empty( $values['message'] ) AND $values['error'] ) ? $values['message'] : NULL; ?></textarea>
					<?php if( in_array( "message", $this->set->options->bulkmenow_activate_mandatories ) ) : ?><span class="required">*</span><?php endif; ?>
				</label>
			</div>

			<?php endif; ?>
			
			<?php if( $this->set->options->bulkmenow_recaptcha_activate AND $this->set->options->bulkmenow_recaptcha_public_key ) : ?>
				
			<div class="input input-recaptcha">

				<label class="recaptcha_field_style">
					<span class="recaptcha_only_if_image screen-reader-text"><?php _e( "Enter the words above", "bulk" ); ?></span>
					<span class="recaptcha_only_if_audio screen-reader-text"><?php _e( "Enter the words you hear", "bulk" ); ?></span>

					<div id="recaptcha_image" class="recaptcha_image"></div>
				
					<input type="text" class="recaptcha_response_field" id="recaptcha_response_field" name="recaptcha_response_field" placeholder="<?php _e( 'Enter the words above', "bulk" ); ?>" autocomplete="off" autocorrect="off" />
					<span class="required">*</span>
				
					<div class="recaptcha_tools">
						<div class="recaptcha_reload button"><a href="javascript:Recaptcha.reload()" title="<?php _e( "Get another CAPTCHA", "bulk" ); ?>"><?php _e( "Get another CAPTCHA", "bulk" ); ?></a></div>
						<div class="recaptcha_only_if_image button"><a href="javascript:Recaptcha.switch_type( 'audio' )" title="<?php _e( "Get an audio CAPTCHA", "bulk" ); ?>"><?php _e( "Get an audio CAPTCHA", "bulk" ); ?></a></div>
						<div class="recaptcha_only_if_audio button"><a href="javascript:Recaptcha.switch_type( 'image' )" title="<?php _e( "Get an image CAPTCHA", "bulk" ); ?>"><?php _e( "Get an image CAPTCHA", "bulk" ); ?></a></div>
						<div class="recaptcha_help button"><a href="javascript:Recaptcha.showhelp()" title="<?php _e( "Help", "bulk" ); ?>"><?php _e( "Help", "bulk" ); ?></a></div>
					</div>
				
				</label>
			
			</div>

			<?php echo $recaptcha_width_adjust; ?>

			<script type="text/javascript">
				/////////////////////////////// CHANGE THIS THING DOWN v //////////////////
				// We should add the validation on the sending. For not calling unnecesary unsafe urls.
				//var bulkmenow_recaptcha_url = '<?php echo plugins_url( "bootstrap.php", dirname( __FILE__ ) ); ?>';
				var RecaptchaOptions = { theme : 'custom', custom_theme_widget: 'recaptcha_widget' };
			</script>

			<script type="text/javascript" src="//www.google.com/recaptcha/api/challenge?k=<?php echo $this->set->options->bulkmenow_recaptcha_public_key; ?>"></script>

			<noscript>
				<iframe src="//www.google.com/recaptcha/api/noscript?k=<?php echo $this->set->options->bulkmenow_recaptcha_public_key; ?>" height="300" width="500" frameborder="0"></iframe><br />
				<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
				<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
			</noscript>

			<?php endif; ?>
		
			<div class="input input-submit">
				<button id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>"><?php echo ( isset( $button ) ) ? $button : __( "Submit", "bulk" ); ?></button>
			</div>

		</form>
		
		<?php else : ?>
		
		<div class="advice danger"><p><?php _e( 'You should select at least one field for this form.', "bulk" ); ?></p></div>
		
		<?php endif; ?>
	
	</div>
	
	