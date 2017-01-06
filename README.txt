=== Dummy Data Generator ===
Contributors: hito01
Tags: comments, spam
Requires at least: 4.7
Tested up to: 4.7
Stable tag: 4.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use Faker PHP library to generate dummy data. Support ACF plugin.

== Description ==

Use [Faker](https://github.com/fzaninotto/Faker) PHP library to generate dummy data. Support [ACF](https://www.advancedcustomfields.com) plugin.

At the moment, the plugin supports the following ACF fields :

* text
* textarea
* number
* email
* url
* password
* image

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `dummy-data-generator` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure your user has the `ddg_use` capability

== Changelog ==

= 1.0 =
* First release. Support following fields : text, textarea, number, email, url, password and image.
