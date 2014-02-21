(function() {

	tinymce.create('tinymce.plugins.bulkmenow', {
		init : function( ed, url ){
			ed.addButton( 'bulkmenow', {
				title : 'Bulk Me Now!',
				width : 30,
				image : url + '/../img/shortcode-icon.png',
				onclick : function(){
					ed.selection.setContent( '[bmn fields="all" button="Submit!"]' );
					//ed.selection.setContent('[quote]' + ed.selection.getContent() + '[/quote]');
				}
			});
		},
		createControl : function( n, cm ){
			return null;
		},
	});
	
	tinymce.PluginManager.add( 'bulkmenow', tinymce.plugins.bulkmenow );

})();