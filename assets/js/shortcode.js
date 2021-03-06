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