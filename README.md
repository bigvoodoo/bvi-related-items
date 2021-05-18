# BVI Related Items
[@bigvoodoo](https://github.com/bigvoodoo), [@praggmatica](https://github.com/praggmatica)

Gives a shortcode options to include in templates to indicate manually configured related pages or posts.

## Description

This is a plugin for WordPress 5+ that provides a way to connect pages/posts that otherwise would not have a relationship:

* adds a Related Items section to each page and post admin interface to indicate relationships that would not exist naturally in the WordPress environment
* adds shortcode to display manually configured relationships from the admin interface - `[related-items]`

License: [GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)

### Requirements

* WordPress 5+
* PHP 7+

## Installation

1. Install the plugin in WordPress & activate it.
2. Go to a page or post you would like to add relationships to.
3. Setup the custom relationships under Related Items section on the page/post's admin interface.
4. Use the `[related-items]` shortcode to display the related items.

## Changelog

### 4.0.1

* Adds reference to WordPress version of JQuery UI Sortable

### 4.0.0

* Complete rewrite of the entire plugin
* Fixes old references to jQuery UI to the latest WordPress core version
* Proper prefixing on functions, variables, and elements to prevent clashing

### 3.0.1

* Existing, hanging-on-by-a-thread, related items functionality
