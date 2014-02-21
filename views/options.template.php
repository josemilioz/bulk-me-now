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
		
		<h2><div class="dashicons dashicons-admin-generic header-icon"></div><?php _e( 'Options for Bulk Me Now!', "bulk" ); ?></h2>
		
		<?php $this->ext->display_advice_messages(); ?>
		
		<form method="post" accept-charset="utf-8">
			
			<?php wp_nonce_field( 'bulkmenow_update_options' ); ?>

			<div id="options-board">
			
				<h3><?php _e( "reCAPTCHA Configuration", "bulk" ); ?></h3>
			
				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_recaptcha_activate"><?php _e( "reCAPTCHA Activation", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_recaptcha_activate">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_recaptcha_activate" id="bulkmenow_recaptcha_activate" value="1" <?php checked( '1', get_option( 'bulkmenow_recaptcha_activate' ) ); ?> />
							<span><?php _e( 'Use reCAPTCHA to prevent spam', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php printf( __( 'Check this to use reCAPTCHA. You can get a free ID from <a href="%1$s" target="_blank">here</a>.', "bulk" ), 'http://google.com/recaptcha' ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_recaptcha_public_key"><?php _e( "reCAPTCHA Public Key", "bulk" ); ?></label></span></h4>
					<label for="bulkmenow_recaptcha_public_key" class="column option">
						<div class="padding">
							<input type="text" name="bulkmenow_recaptcha_public_key" id="bulkmenow_recaptcha_public_key" size="37" value="<?php echo get_option( 'bulkmenow_recaptcha_public_key' ); ?>" />
						</div>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_recaptcha_private_key"><?php _e( "reCAPTCHA Private Key", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_recaptcha_private_key">
						<div class="padding">
							<input type="text" name="bulkmenow_recaptcha_private_key" id="bulkmenow_recaptcha_private_key" size="37" value="<?php echo get_option( 'bulkmenow_recaptcha_private_key' ); ?>" />
						</div>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_recaptcha_image_width"><?php _e( "reCAPTCHA Image Width", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_recaptcha_image_width">
						<div class="padding">
							<input type="text" name="bulkmenow_recaptcha_image_width" id="bulkmenow_recaptcha_image_width" size="10" value="<?php echo get_option( 'bulkmenow_recaptcha_image_width' ); ?>" /><em style="display: inline-block; margin-top: 5px">px</em>
						</div>
						<p class="desc no-left"><?php _e( 'It may distort the captcha image, but if you need to fit it to your div, then here is the option. To disable, just leave blank.', "bulk" ); ?></p>
					</label>
				</div>
			
				<h3><?php _e( "Ajax & JavaScript", "bulk" ); ?></h3>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_ajax_setup"><?php _e( "Ajax Setup", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_ajax_setup">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_ajax_setup" id="bulkmenow_ajax_setup" value="1" <?php checked( '1', get_option( 'bulkmenow_ajax_setup' ) ); ?> />
							<span><?php _e( 'Make the widget work asynchronously', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php _e( 'It will allow to send the form data without refreshing the page.', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><?php _e( "JavaScript Setup", "bulk" ); ?></span></h4>
					<label class="column option" for="bulkmenow_avoid_jquery">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_avoid_jquery" id="bulkmenow_avoid_jquery" value="1" <?php checked( '1', get_option( 'bulkmenow_avoid_jquery' ) ); ?> />
							<span><?php _e( 'Avoid the loading of jQuery', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php _e( 'Check this option in case your theme is already calling a jQuery instance prior to version 1.10+', "bulk" ); ?></p>
					</label>
					<div class="empty_name hide-mobile"></div>
					<label class="column option" for="bulkmenow_scripts_bottom">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_scripts_bottom" id="bulkmenow_scripts_bottom" value="1" <?php checked( '1', get_option( 'bulkmenow_scripts_bottom' ) ); ?> />
							<span><?php _e( 'Load scripts at the bottom of the page (Recommended)', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php _e( 'It will load plugin JS files at the bottom of the pages, so Look & Feel files load first.', "bulk" ); ?></p>
					</label>
					<div class="empty_name hide-mobile"></div>
					<label class="column option" for="bulkmenow_avoid_javascript">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_avoid_javascript" id="bulkmenow_avoid_javascript" value="1" <?php checked( '1', get_option( 'bulkmenow_avoid_javascript' ) ); ?> />
							<span><?php _e( 'Disable JavaScript (Advanced Users)', "bulk" ); ?></span>
						</div>
						<p class="desc">
							<?php _e( 'You will have to use your own JS file in order to validate and maybe store the message in the database.', "bulk" ); ?><br />
							<strong><?php _e( 'REMEMBER:', "bulk" ); ?></strong>
							<?php _e( 'If you turn this option on, then AJAX will be disabled as well. You will have to write your own script.', "bulk" ); ?>
						</p>
					</label>
				</div>
			
				<h3><?php _e( "General Settings", "bulk" ); ?></h3>
				
				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_list_rows"><?php _e( "Rows per Page", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_list_rows">
						<div class="padding">
							<input type="number" name="bulkmenow_list_rows" id="bulkmenow_list_rows" size="8" value="<?php echo get_option( 'bulkmenow_list_rows' ); ?>" />
						</div>
						<p class="desc no-left"><?php _e( 'Quantity of rows per page in the lists views (Unread, Read, Trash)', "bulk" ); ?></p>
					</label>
				</div>
			
				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_activate_reply"><?php _e( "Activate Reply", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_activate_reply">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_activate_reply" id="bulkmenow_activate_reply" value="1" <?php checked( '1', get_option( 'bulkmenow_activate_reply' ) ); ?> />
							<span><?php _e( 'Activate the Reply function', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php _e( 'Answer each message from the plugin itself.', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_default_subject"><?php _e( "Default Subject", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_default_subject">
						<div class="padding">
							<input type="text" name="bulkmenow_default_subject" id="bulkmenow_default_subject" style="width: 96%; font-size: 1.3em !important;" value="<?php echo get_option( 'bulkmenow_default_subject' ); ?>" />
						</div>
						<p class="desc no-left"><?php _e( 'This is the default subject for the reply mail (You can change this individually, on each message).', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_default_subject"><?php _e( "After Receiving", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_success_answer">
						<div class="padding">
							<input type="text" name="bulkmenow_success_answer" id="bulkmenow_success_answer" style="width: 96%; font-size: 1.3em !important;" value="<?php echo get_option( 'bulkmenow_success_answer' ); ?>" />
						</div>
						<p class="desc no-left"><?php _e( 'The displaying message to the user noticing the successful sent.', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_disable_css"><?php _e( "Form Styling", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_disable_css">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_disable_css" id="bulkmenow_disable_css" value="1" <?php checked( '1', get_option( 'bulkmenow_disable_css' ) ); ?> />
							<span><?php _e( 'Disable CSS form styling (Advanced Users)', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php _e( 'You will have to use your own CSS file in order to stylish the form output.', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_activate_mandatories"><?php _e( "Activate Mandatories", "bulk" ); ?></label></span></h4>
					<div class="column option">
						<div class="padding">
							<?php foreach( array( 
								'name' 		=> __( "Name", "bulk" ), 
								'city' 		=> __( "City", "bulk" ), 
								'country' 	=> __( "Country", "bulk" ), 
								'email' 	=> __( "E-mail", "bulk" ), 
								'message' 	=> __( "Message", "bulk" ),
							) AS $k => $v ) : $options = maybe_unserialize( get_option( 'bulkmenow_activate_mandatories' ) ); ?>
							<label for="bulkmenow_activate_mandatories_<?php echo $k; ?>">
								<input type="checkbox" name="bulkmenow_activate_mandatories[]" id="bulkmenow_activate_mandatories_<?php echo $k; ?>" value="<?php echo $k; ?>" <?php if( $options ) foreach( $options AS $o ) checked( $k, $o ); ?> />
								<span><?php echo $v; ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
							<?php endforeach; ?>
						</div>
						<p class="desc"><?php _e( 'This will force audience to complete those specific fields, otherwise message is discarded.', "bulk" ); ?></p>
					</div>
				</div>
			
				<h3><?php _e( "Mailing Settings", "bulk" ); ?></h3>
			
				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_activate_notifications"><?php _e( "Activation", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_activate_notifications">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_activate_notifications" id="bulkmenow_activate_notifications" value="1" <?php checked( '1', get_option( 'bulkmenow_activate_notifications' ) ); ?> />
							<span><?php _e( 'Activate e-mail notifications', "bulk" ); ?></span>
						</div>
						<p class="desc"><?php _e( 'Now with switch button! In case you want to keep settings, but turn the notifications off.', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_emails_notify"><?php _e( "E-mails to Notify", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_emails_notify">
						<div class="padding">
							<textarea name="bulkmenow_emails_notify" id="bulkmenow_emails_notify" rows="5"><?php echo get_option( 'bulkmenow_emails_notify' ); ?></textarea>
						</div>
						<p class="desc no-left"><?php _e( 'Add one e-mail address per row. Neither space nor comma included.', "bulk" ); ?></p>
					</label>
				</div>

				<div class="rows">
					<h4 class="column name"><span><?php _e( "Frequency", "bulk" ); ?></span></h4>
					<div class="column option">
						<div class="padding">
							<label for="bulkmenow_emails_frequency_daily">
								<input type="radio" name="bulkmenow_emails_frequency" id="bulkmenow_emails_frequency_daily" value="daily" <?php checked( 'daily', get_option( 'bulkmenow_emails_frequency' ) ); ?> />
								<span><?php _e( 'Once Daily', "bulk" ); ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
							<label for="bulkmenow_emails_frequency_weekly">
								<input type="radio" name="bulkmenow_emails_frequency" id="bulkmenow_emails_frequency_weekly" value="weekly" <?php checked( 'weekly', get_option( 'bulkmenow_emails_frequency' ) ); ?> />
								<span><?php _e( 'Once Weekly', "bulk" ); ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
							<label for="bulkmenow_emails_frequency_monthly">
								<input type="radio" name="bulkmenow_emails_frequency" id="bulkmenow_emails_frequency_monthly" value="monthly" <?php checked( 'monthly', get_option( 'bulkmenow_emails_frequency' ) ); ?> />
								<span><?php _e( 'Once Monthly', "bulk" ); ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
						</div>
						<p class="desc">
							<?php _e( 'In case you do not have new messages, you will not be disturbed.', "bulk" ); ?><br />
							<strong><?php _e( 'REMEMBER:', "bulk" ); ?></strong>
							<?php _e( 'The real frequency of notifications relies on your website traffic, since WordPress does not use real cron-jobs.', "bulk" ); ?>
						</p>
					</div>
				</div>

				<div class="rows">
					<h4 class="column name"><span><label for="bulkmenow_emails_subject"><?php _e( "Notification Subject", "bulk" ); ?></label></span></h4>
					<label class="column option" for="bulkmenow_emails_subject">
						<div class="padding">
							<input type="text" name="bulkmenow_emails_subject" id="bulkmenow_emails_subject" style="width: 96%; font-size: 1.3em !important;" value="<?php echo get_option( 'bulkmenow_emails_subject' ); ?>" />
						</div>
						<p class="desc no-left"><?php _e( 'This is the subject for the notification mail. Go easy on this or the message may be classified as spam.', "bulk" ); ?></p>
					</label>
				</div>
				
				<h3><?php _e( "Permissions", "bulk" ); ?></h3><?php global $wp_roles; ?>
				
				<div class="rows">
					<h4 class="column name"><span><?php _e( "Allow to see messages", "bulk" ); ?></span></h4>
					<div class="column option">
						<div class="padding">
							<?php foreach( $wp_roles->roles AS $k => $v ) : $options = maybe_unserialize( get_option( 'bulkmenow_roles_messages' ) ); ?>
							<label for="bulkmenow_roles_messages_<?php echo $k; ?>">
								<input type="checkbox" name="bulkmenow_roles_messages[]" id="bulkmenow_roles_messages_<?php echo $k; ?>" value="<?php echo $k; ?>" <?php if( $options ) foreach( $options AS $o ) checked( $k, $o ); ?> />
								<span><?php _e( $v['name'] ); ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="rows">
					<h4 class="column name"><span><?php _e( "Allow to reply", "bulk" ); ?></span></h4>
					<div class="column option">
						<div class="padding">
							<?php foreach( $wp_roles->roles AS $k => $v ) : $options = maybe_unserialize( get_option( 'bulkmenow_roles_reply' ) ); ?>
							<label for="bulkmenow_roles_reply_<?php echo $k; ?>">
								<input type="checkbox" name="bulkmenow_roles_reply[]" id="bulkmenow_roles_reply_<?php echo $k; ?>" value="<?php echo $k; ?>" <?php if( $options ) foreach( $options AS $o ) checked( $k, $o ); ?> />
								<span><?php _e( $v['name'] ); ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
							<?php endforeach; ?>
						</div>
						<p class="desc"><?php printf( __( 'Obviously, if you disabled a role in \'%1$s\', then reply is disabled as well.', "bulk" ), __( "Allow to see messages", "bulk" ) ); ?></p>
					</div>
				</div>

				<div class="rows">
					<h4 class="column name"><span><?php _e( "Allow to manage options", "bulk" ); ?></span></h4>
					<div class="column option">
						<div class="padding">
							<?php foreach( $wp_roles->roles AS $k => $v ) : $options = maybe_unserialize( get_option( 'bulkmenow_roles_options' ) ); ?>
							<label for="bulkmenow_roles_options_<?php echo $k; ?>">
								<input type="checkbox" name="bulkmenow_roles_options[]" id="bulkmenow_roles_options_<?php echo $k; ?>" value="<?php echo $k; ?>" <?php if( $options ) foreach( $options AS $o ) checked( $k, $o ); ?> />
								<span><?php _e( $v['name'] ); ?></span>
							</label>
							<br class="clear" style="line-height: 25px;" />
							<?php endforeach; ?>
						</div>
						<p class="desc"><?php printf( __( 'For security reasons, you CANNOT disable \'%1$s\' role here.', "bulk" ), __( 'Administrator' ) ); ?></p>
					</div>
				</div>

				<div class="rows">
					<h4 class="column name"><span><?php _e( "Uninstall", "bulk" ); ?></span></h4>
					<label class="column option" for="bulkmenow_uninstall_remove_all">
						<div class="padding">
							<input type="checkbox" name="bulkmenow_uninstall_remove_all" id="bulkmenow_uninstall_remove_all" value="1" <?php checked( '1', get_option( 'bulkmenow_uninstall_remove_all' ) ); ?> />
							<span><?php _e( 'Remove all the messages and options stored when uninstalling', "bulk" ); ?></span>
						</div>
						<p class="desc">
							<strong><?php _e( 'REMEMBER:', "bulk" ); ?></strong>
							<?php _e( 'Data erased is unrecoverable.', "bulk" ); ?>
						</p>
					</label>
				</div>
							
			</div>
		
			<div id="submit-form">
				<button type="submit" class="button button-primary button-large" name="submit" value="save"><?php _e( "Save Settings", "bulk" ); ?></button>
				<button type="submit" class="button button-default button-large" name="submit" value="restore"><?php _e( "Restore Defaults", "bulk" ); ?></button>
				<span class="hide-about-button hide-mobile"><a class="credits button button-large"><?php _e( 'About', "bulk" ); ?></a></span>
			</div>
				
		</div>
	
	</form>

	<div id="about-board">
		<div class="about-wrapper">
			<div class="close button button-"><div class="dashicons dashicons-no"></div> <?php _e( "Close", "bulk" ); ?></div>
			<div class="logo"><img src="<?php echo plugins_url( '/assets/img/logo.gif', dirname( __FILE__ ) ); ?>" /></div>
			<div class="col col-left">
				<div class="rows">
					<h5><?php _e( "Made By", "bulk" ); ?></h5>
					<p>mEtAmorPher</p>
				</div>
				<div class="rows">
					<h5><?php _e( "Currently Working With", "bulk" ); ?></h5>
					<p><a href="http://ombu.co/" target="_blank">Omb&uacute; Internet</a></p>
				</div>
				<div class="rows">
					<h5><?php _e( "Contact", "bulk" ); ?></h5>
					<p><a href="mailto:metamorpher.py@gmail.com">metamorpher.py@gmail.com</a></p>
				</div>
				<div class="rows">
					<h5><?php _e( "Translating Bulk Me Now!", "bulk" ); ?></h5>
					<p><?php _e( "If you've translated Bulk Me Now! into another language, please contribute the PO and MO files to this free project.", "bulk" ); ?></p>
					<p><?php _e( "Write an e-mail to the address above (English, Spanish, Portuguese written)", "bulk" ); ?></p>
					<p><?php _e( "You'll receive full credit for your help, and you name will appear in the column of the right.", "bulk" ); ?></p>
				</div>
			</div>
			<div class="col col-right">
				<div class="rows">
					<h5><?php _e( "System Requirements", "bulk" ); ?></h5>
					<ul>
						<li><?php printf( __( 'PHP %1$s', 'bulk' ), '5.3+' ); ?></li>
						<li><?php printf( __( 'WordPress %1$s', 'bulk' ), '3.8+' ); ?></li>
						<li><?php printf( __( 'Disk Space: %1$s', 'bulk' ), '121 kB' ); ?></li>
					</ul>
				</div>
				<div class="rows">
					<h5><?php _e( "Current Plugin Version", "bulk" ); ?></h5>
					<p><?php echo $this->set->version; ?></p>
				</div>
				<div class="rows">
					<h5><?php _e( "Form Displaying", "bulk" ); ?></h5>
					<p><?php _e( "Widgets and Shortcode", "bulk" ); ?></p>
				</div>
				<div class="rows">
					<h5><?php _e( "Languages available", "bulk" ); ?></h5>
					<ul>
						<li><span class="lang"><?php _e( "English", "bulk" ); ?></span><span class="auth"><a href="mailto:metamorpher.py@gmail.com">mEtAmorpher</a></span></li>
						<li><span class="lang"><?php _e( "Spanish", "bulk" ); ?></span><span class="auth"><a href="mailto:metamorpher.py@gmail.com">mEtAmorpher</a></span></li>
						<li><span class="lang"><?php _e( "Portuguese (BR)", "bulk" ); ?></span><span class="auth"><a href="mailto:metamorpher.py@gmail.com">mEtAmorpher</a></span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
