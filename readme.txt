=== Filepicker ===
Contributors: shanaver, jasonfilepicker
Tags: filepicker.io media uploads facebook dropbox google-drive box skydrive instagram picasa instagram flickr github evernote alfresco
Requires at least: 3.0.1
Tested up to: 3.7.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Uses the filepicker.io service to upload media for your wordpress site

== Description ==

Uses the filepicker.io service to upload media for your wordpress site

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add you filepicker.io API Key
1. drop the shortcode [filepicker] in a post or page - or upload via the media section of the admin

== Frequently Asked Questions ==

= Do I need a filepicker.io account? =

Yes, although currently a starter account is free.

= Does it work with the wordpress media uploader? =

Yes.

= Is it easily added to pages or posts for signed-in & non signed-in users to upload files? =

Yes.

== Screenshots ==

1. Admin Settings
1. Media Upload Integration

== Changelog ==

= 1.0.0 =
* Initial Wordpress.com version

= 2.0.0 =
* Add all the options to be accessible from the Admin settings page
* Begin language support
* Added help text & examples to the settings page
* Added iframe support to the shortcode
* Lots of cleanup & refactoring

= 2.0.1 =
* update the readme

= 2.0.2 =
* added the window.inkblob object for accessing the current uploaded media via javascript
* cleanup & refactoring

= 2.0.3 =
* Added customizable mimetypes
* Added modal window option
* Added automatic Media Library refresh after upload

= 2.0.4 =
* Fixed Media Collection not refreshing in Media Upload View.
* Fixed double event listener bug when opening FP dialog in Media Upload View.

== Upgrade Notice ==

= 1.0.0 =
Initial release

= 2.0.0 =
Please refresh/save the admin settings to reset the new option array

