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
