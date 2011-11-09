=== Garee's Random Image ===
Contributors: garee
Donate link: https://flattr.com/donation/give/to/garee/
Tags: image, random, picture
Requires at least: 3.0.0
Tested up to: 3.2.1
Stable tag: trunk

Garee's Random Image is a highly customizable WordPress-plugin that shows a random image anywhere on your blog.

== Description ==
*Garee's Random Image* is a highly customizable WordPress-plugin that shows a random image anywhere on your blog. The output is generated from template-files or your own templates, which you can write with Mustache. 

Just insert the shortcode anywhere on your blog: `[random_image]`

Use shortcode-attributes to further customize the image: `[random_image template='polaroid' category='30' polaroid_font_size='14px' polaroid_width='360px']`

Main-advantages:

* CSS only included if shortcode found on page
* highly customizable with Mustache-templates

== Installation ==

1. Download the plugin and unzip it
1. Upload the entire folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Goto the new plugin-page 'Garee's Random Image' to get your shortcode
1. Place the shortcode anywhere in your blog

== Screenshots ==

1. The default-template shows the random image as a clickable thumnail with the post-title below
2. The scrapbook-template, design by [daveJay](http://davejay.exit42design.com/entry/CSS/32/)
3. The polaroid-template, design by [ZURB](http://www.zurb.com/playground/css3-polaroids/)
4. Configure the plugin with shortcode
5. Write your own templates with [Mustache](http://mustache.github.com/)

== Frequently Asked Questions ==

== Changelog ==

= 0.7.1 =
* bugfixes (only adminpage)

= 0.7 =
* first official release

= 0.6 =
* code cleanup, small fixes

= 0.5 =
* new attribute 'size': sets {{image}}, 'full', 'large', 'medium', 'thumbnail' or something like '120,60' to get a custom size
* updated plugins to make use of size-attribute
* new attribute 'file_type': allows search to be narrowed to a png, jpeg or gif
* better search-algorithm to speed up search and avoid endless loop if category hasn't images published

= 0.4 =
* Allow loading of more than one CSS file, if there are different random-image-styles detected

= 0.3 =
* Load CSS only on pages with shortcode
* added new templates

== Upgrade Notice ==

== Coming soon ==

Here's what's planned for future releases:

* exif-extension: inlcude date/time, coordinates, shutter-speed, ...
* ... 