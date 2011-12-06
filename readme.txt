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

= I get always the same images =
Check if you have a cache-plugin running. If the page is cached, there won't be any random images on reload!


== Changelog ==

= 1.1 =
* faster algorithm to find a random image of a certain category
* if algorithm times out, make sure that an almost random image of the category is shown
* check if there are any images associated with a certain post-category (on the admin-page)

= 1.0 =
* FIX: some themes don't run shortcode in the text-widget
* allow comma-separated categories as shortcode-option
* new shortcode-attributes 'window_image' and 'window_post' to control whether links should open a new window
* new corresponding Mustache-tags: {{window_image}} and {{window_post}}

= 0.9 =
* FIX: detect shortcode in text-widget and load css (if template uses css)
* new Mustache-tag: 'post_author'
* new shortcode-attributes 'date_format' and 'exclude'
* {{post_date}} formatted automatically if 'date_format'-option missing
* minor changes to template 'caption'

= 0.8 =
* FIX: "Missing argument 1 for plugin_dir_url()"

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

= 0.9 =
If you used the template 'caption': be shure to check it, there were some modifications made

== Coming soon ==

Here's what's planned for future releases:

* random-image-widget: better ways to control css-loading than if you insert the shortcode into a text-widget
* exif-extension: inlcude date/time, coordinates, shutter-speed, ...
* ... 