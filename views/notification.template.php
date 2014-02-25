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

<html>
	<head>
		<style type="text/css">
			body		{ font-family: "Helvetica Neue", Helvetica, Sans-Serif; color: #000; padding: 20px; font-weight: 200; }
			h1			{ font-weight: 100; }
			hr			{ border: none; border-top: 1px solid #CCC; }
			h4 span 	{ background: #FDD800; display: inline-block; padding: 1px 4px; font-size: 1.2em; }
			p 			{ line-height: 1.7; }
			p a.here	{ background: #52ACCC; font-weight: bold; color: #FFF; }
		
			.footer 			{ clear: both; overflow: hidden; font-size: 11px; }
			.footer .left 		{ float: left; background: #EEE; padding: 5px; }
			.footer .right 		{ float: right; background: #EEE; padding: 5px; }
			.footer .right a 	{ color: #777; background: none; font-weight: bold; }
		
			.remove small 		{ background: #FDD800; padding: 1px 1px; }
		</style>
	</head>
	<body>
		<h1><?php _e( "Hello!", "bulk" ); ?></h1>
		<h4><?php printf( _n( 'You have <span>%1$d new message</span> in your inbox!', 'You have <span>%1$d new messages</span> in your inbox!', $counter, "bulk" ), $counter ); ?></h4>
		<hr />
		<p><?php printf( __( 'You are receiving this message because you belong to the notification list in <strong>%1$s</strong> [<a href="%2$s">%2$s</a>]', "bulk" ), $site_name, $site_url ); ?></p>
		<p><?php printf( __( 'To read your new messages you will need to log into your WordPress Admin Area. Do so by <a href="%1$s" class="here">clicking here</a>.', "bulk" ), $admin_url ); ?></p>
		<p><?php _e( 'You should get in there and start answering the feedback you got. Do not make your audience wait any longer!', "bulk" ); ?></p>
		<hr />
		<p class="remove"><small><?php _e( "If you DO NOT want to receive more emails like this, get into the WordPress admin area and disable the notifications, under Messages > Options", "bulk" ); ?></small></p>
		<hr />
		<div class="footer">
			<div class="left"><?php echo $date; ?></div>
			<div class="right"><?php printf( __( 'Powered by <a href="%1$s">Bulk Me Now!</a> / <a href="%2$s">WordPress</a>', "bulk" ), $bmn_url, $wp_url ); ?></div>
		</div>
	</body>
</html>