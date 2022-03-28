=== BVI Related Items ===
Contributors: geekmenina
Donate link: https://www.bigvoodoo.com
Tags: links, related links
Requires at least: 5.5.1
Tested up to: 5.7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gives a shortcode options to include in templates to indicate manually configured related pages or posts.

== Description ==

This is a plugin for WordPress 5+ that provides a way to connect pages/posts that otherwise would not have a relationship:

* adds a Related Items section to each page and post admin interface to indicate relationships that would not exist naturally in the WordPress environment
* adds shortcode to display manually configured relationships from the admin interface - `[related-items]`

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `bvi-related-items.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Setup the custom relationships under Related Items section on the page/post's admin interface.
1. Use the `[related-items]` shortcode to display the related items.

== Changelog ==

= 4.0.3 =
* Resolved syntax issue that resulted in only last related item being output

= 4.0.2 =
* WordPress 5.7.2 has JQuery UI Sortable require Draggable and Droppable as well

= 4.0.1 =
* Adds reference to WordPress version of JQuery UI Sortable

= 4.0.0 =
* Complete rewrite of the entire plugin
* Fixes old references to jQuery UI to the latest WordPress core version
* Proper prefixing on functions, variables, and elements to prevent clashing

= 3.0.1 =
* Existing, hanging-on-by-a-thread, related items functionality