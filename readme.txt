=== Garee's Random Image ===
Contributors: garee
Donate link: https://flattr.com/donation/give/to/garee/
Tags: image, random, picture
Requires at least: 3.0.0
Tested up to: 3.2.1
Stable tag: trunk

a highly customizable WordPress-plugin that shows a random image anywhere on your blog.

== Description ==
*Garee's Random Image* shows a random image anywhere on your blog. The output is generated from template-files (html and css) or your own templates, which you can write with Mustache.

Check out the [live-demo](http://www.garee.ch/wordpress/garees-random-image/live-demo/) on the official [plugin-site](http://www.garee.ch/wordpress/garees-random-image/)

Main-advantages:

* CSS only included if shortcode found on page
* highly customizable with Mustache-templates
* only show pictures associated with a certain post-category
* only show jpg, png or gif
* choose from 6 different themes
* easily write your own theme using html, css and images

Just insert the shortcode anywhere on your blog: `[random_image]`

Use shortcode-attributes to further customize the image:
`[random_image template='polaroid' category='30' polaroid_font_size='14px' polaroid_width='360px']`

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

= How do I write Mustache-Code =
Have a look at the enclosed html-templates in the template-folder. For advanced syntax (e.g. if-else) there's the mustache(5)-manual on the [Mustache-Site](http://mustache.github.com/)

= My random images look different from the ones on the demo-page =
This might be because your wordpress-theme's css has an influence on the output of the plugin. You might have to adjust the css-template (set margins to zero, stuff like this)

= I would like to add the random image as a widget on the sidebar =
Just add a text-widget to the sidebar and insert the shortcode there

== Changelog ==

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