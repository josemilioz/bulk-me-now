=== Bulk Me Now! ===
Contributors: metamorpher
Donate Link: unavailable
Tags: contact form, messages, database, admin, settings
Requires at least: 3.8.1
Tested up to: 3.8.1
Stable tag: 2.0

A simple contact form plugin that stores the messages right in the database, instead of sending mails.

== Description ==

*Bulk Me Now!* is a plugin that allows you to put a simple contact form in your website or blog inside a widget area, or by the shortcode available.

You will be able to customize which fields you want, and which fields you do not want.

**Components (i.e., features):**

* Simple Contact Form: Not so much to handle, just the basics. No complications.
* reCAPTCHA Protection: No Spam in your Bulk Me Now! inbox. You can disable if you do not want it.
* Languages files bundled, ready for translation (English, Spanish and Portuguese already added).
* Reply Function: Respond messages with rich-edited text right in the view screen. (Your server must have mail functions).
* E-mails to note new messages: Can't get rid of ye olde postman? No worries, we let you know.

** Required Specs**

* PHP 5.3+ with Mail functions enabled.
* WordPress 3.8+
* 5 MB Free Disk Space

== Installation ==

1. Upload `bulk-me-now` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the form into a page or using the widget, or the shortcode. If you want to add it into your theme, you can use `<?php do_shortcode( '[bmn fields="all"]' ); ?>`.

== Frequently Asked Questions ==

= Why was this plugin created? =

I was not satisfied with the contact form plugins I found over the web, so I decided to build one that could fit my expectations.

= How do I use it? =

Visit http://metamorpher.net/bulk-me-now for detailed instructions.

= I enabled the reCAPTCHA feature, but when I added multiple forms into one page the captcha only loads in the first form. Why is that? =

The reCAPTCHA JS file can be loaded just once per page. The reCAPTCHA challenge will be loaded then into the first calling form. This issue will be held into later versions to try a fix.

EDIT:

Now is possible to see more than one reCAPTCHA into forms. But this feature only works with the JavaScript enabled and jQuery.

= I deactivated the jQuery library, now the form is broken. What can I do? =

jQuery handles few asynchronous functions and reCAPTCHA features. So if you have enabled this two options, then you *MUST* have jQuery enabled, at least until the 1.10.2 version to make Bulk Me Now work. The option of no loading jQuery is there in case you already loaded it. If your theme or another plugin did not load it already, disable the option to make posible the jQuery loading.

= I translated the POT file to my language. How can I add it to the repository? =

Well, thank you for your interest. You can mail me both translation files (The POT and the MO) to `metamorpher.py@gmail.com` with the name or nick you want to be called for. I will add that name or nick to the credits in the footer of the plugin.

= How do I change the Look & Feel of the notification e-mail =

Now you can create your own template for the notification e-mail. Simply create a file called `bmn-email-notification.php` into your current theme folder. Bulk Me Now will first look for that file before sending the e-mail. In case it doesn't find that file, it will use `views/notification.template.php`. When you're creating your own template, follow that file as an example and fill it with the proper variables.

== Screenshots ==

1. The Option Page, with all the editable fields.
2. Widget displayed in a widget area, within Twenty-Fourteen theme.
3. Admin interface, with the main menu button, the admin bar button, and the Unread view section.
4. Viewing a message, with all the gathered information about the sender.
5. Widget administrator.
6. Shortcode insertion, with the code (green) and the button (red).

== Changelog ==

**Version 2.0**

* Complete change of the logics. Changed the programming paradigm to Object Oriented Programming.
* Added the option to build own e-mail notification templates.
* Removed the annoying footer. All credits are in a modal placed on the options page.
* Updated the whole look and feel to match the new Wordpress admin area look.
* New structure for the database table. Now it uses two tables. One to manage messages, and the other to manage the replies.
* Added a migrate function, so you don't lose the previous stored messages. Anyway, some data loss may occur in large tables.
* Updated the Admin Menu Bar new messages counter. Now it is only a button, and not a drop-down menu.
* Now it doesn't use jQuery for validation. Every validation is made in the server. When in asynchronous mode, it does every check in the server also.
* More options added, like Rows per Page, Default subject for mails, for notifications.
* Added a verification for permissions according to roles. You can pick which group of user have access for certain features.
* Pick the mandatories fields. You can choose which fields to make mandatories, and which not.
* Implemented a little MVC philosophy, in case advanced users want to perform quick edits to the forms. View files are all stored in the `views` folder. Anyway, the editing of those files are not encouraged.
* Implemented WP_Table Class to define displaying orders according to the user will.
* Added an option to delete all the stored messages when uninstalling the plugin.
* Added portuguese to the language domain.
* Administration views are fully responsive. Adapts to any device with a browser.
* Added gravatar support.
* Widget with more options, and a textbox with HTML support.

**Version 1.5**

* Modified the widget to adjust the output and make it compatible with the new HTML 5 default theme.
* Added the Reply feature allowing to send reply mails to the senders.
* Updated the Admin Menu Bar new messages counter, adjusted to the new admin look and feel interface.
* Updated the database to store replies and checks replies statuses.
* Added some validation functions to check the table status in the database.

**Version 1.2**

* Added the WP Nonce Field feature to increase security and reduce spam attempts.
* Added the WP Admin Bar feature to provide shortcuts to the admin area.
* The WP Admin Bar feature comes with a bundled counter to highlight the number of new messages received.

**Version 1.1**

* Re-arranged the Settings panel with jQuery animations
* Added new email notification feature for new messages received
* Corrected some mistakes from public js library validator

**Version 1.0**

* Launched the first version of Bulk Me Now! Plugin