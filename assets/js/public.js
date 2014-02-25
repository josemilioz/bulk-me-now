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
	
	bulkmenow_recaptcha_initial_assignment = function()
	{
		$( ".input-recaptcha" ).each(function(){
			if( $( this ).find( ".recaptcha_image" ).html() != "" )
				$( this ).addClass( 'bmn-this-is-original' );
			else
				$( this ).addClass( 'bmn-this-is-copy' );
		});
	};
	
	bulkmenow_recaptcha_clone = function()
	{
		$( '.bmn-this-is-copy' ).each(function(){
			$( this ).before( $( '.bmn-this-is-original' ).clone().removeClass( 'bmn-this-is-original' ).addClass( 'bmn-this-is-copy' ) );
			$( this ).remove();
		});

		bulkmenow_recaptcha_tools_trigger();
	};
	
	bulkmenow_recaptcha_tools_trigger = function()
	{
		$( ".recaptcha_reload, .recaptcha_only_if_image, .recaptcha_only_if_audio" ).click(function(){
			setTimeout( function(){
				bulkmenow_recaptcha_clone();
			}, 3000 );
		});
	};
	
	// Let's crawl the page for the cpatcha and clone it in every instance we can
	
	if( $( ".input-recaptcha" ).length > 0 )
	{
		setTimeout( function(){ 
			bulkmenow_recaptcha_initial_assignment(); 
			bulkmenow_recaptcha_clone();
		}, 2000 );
	} 
		
});
