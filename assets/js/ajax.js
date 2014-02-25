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

jQuery(function($){
	
	$( ".shortcode_bulkmenow form, .widget_bulkmenow form" ).submit(function(){
		
		parent = $( this ).parent();
		var post_index = ( parent.hasClass( 'shortcode_bulkmenow' ) ) ? 'bulkmenow_shortcode' : 'widget-bulkmenow_widget';		
		var post_number = $( this ).find( 'input[name$="[ajax_identification]"]' ).attr( 'name' ).match(/\d+/);
		
		post_data = {
			'_wpnonce' : $( this ).find( 'input[name="_wpnonce"]' ).val(),
			'_wp_http_referer' : $( this ).find( 'input[name="_wp_http_referer"]' ).val(),
/*
			'recaptcha_response_field' : $( this ).find( 'input[name="recaptcha_response_field"]' ).val(),
			'recaptcha_challenge_field' : $( this ).find( 'input[name="recaptcha_challenge_field"]' ).val(),
*/
		};
		
		if( $( ".input-recaptcha" ).length > 0 )
		{
			post_data['recaptcha_response_field'] = $( the_form ).find( 'input[name="recaptcha_response_field"]' ).val();
			post_data['recaptcha_challenge_field'] = $( the_form ).find( 'input[name="recaptcha_challenge_field"]' ).val();
		}
		
		post_data[post_index] = {};
		post_data[post_index][post_number] = {
			'name' : $( this ).find( 'input[name$="[name]"]' ).val(),
			'city' : $( this ).find( 'input[name$="[city]"]' ).val(),
			'country' : $( this ).find( 'input[name$="[country]"]' ).val(),
			'email' : $( this ).find( 'input[name$="[email]"]' ).val(),
			'message' : $( this ).find( 'textarea[name$="[message]"]' ).val(),
		};

		bulkmenow_response = $.ajax({
			type: "POST",
			async: false,
			data: post_data
		}).responseText;
				
		parent.find( ".advice" ).remove();
		
		$( this ).before( bulkmenow_response );
		
		if( bulkmenow_response.indexOf( 'success' ) > 0 )
		{
			this.reset(); // Reseting forms do not like the $() notation... weird
		}
		
		if( $( ".input-recaptcha" ).length > 0 )
		{
			Recaptcha.reload();
			setTimeout( function(){
				bulkmenow_recaptcha_clone();
			}, 3000 );
		}

		return false;
	});
	
});