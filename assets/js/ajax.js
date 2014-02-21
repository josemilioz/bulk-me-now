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