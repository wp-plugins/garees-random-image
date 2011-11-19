<?php
/*
Plugin Name: Garee's Random Image
Plugin URI: http://www.garee.ch/wordpress/garees-random-image/
Description: Garee's Random Image is a wordpress plugin that displays a random image from a post-castegory of your blog. The plugin uses the template-system Mustache to achieve the best possible customization. Some templates are included. 
Version: 0.9
Author: Sebastian Forster
Author URI: http://www.garee.ch/
License: GPL2
*/

/*  Copyright 2011  Sebastian Forster  (email : garee@gmx.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!defined('GAREE_FLATTRSCRIPT')) {
	define('GAREE_FLATTRSCRIPT', 'http://www.garee.ch/js/flattr/flattr.js');
}
if(!defined('GAREE_MUSTACHEPHP')) {
	include_once('Mustache.php');
	define('GAREE_MUSTACHEPHP', true);
}

/*
 * Main-Function: Get Image and render Template
 */ 
function garees_random_image($atts, $content = "") {
	extract(shortcode_atts(array(
	  'category' => null,
	  'template' => null,
	  'size' => 'full',
	  'filetype' => 'jpeg',
	  'date_format' => get_option('date_format'),
	  'exclude' => "",
	), $atts));
	
	if ($filetype == "jpg")
		$filetype = "jpeg";
		
	if ($filetype == "any")
		$filetype = "";	
	
	// copy shortcode attributes to the data-array
	$data = $atts;
	
	// split size, if in pixels
	if (strstr($size, ",")) {
		$size = explode(",", $size);
	}
		
	// open template_file, submitted template or default
	if (!is_null($template)) {
		$tmpl = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/' . $template. '.html');					
	} elseif ($content != "") {
		$tmpl = $content;	
	} else {
		$tmpl = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/default.html');		
	}
	
	// prepare mustache
	$m = new Mustache;
	
	// prepare arguments for query
	$args = array(
	   'post_type' => 'attachment',
	   'post_mime_type' => 'image/'.$filetype,
	   'numberposts' => 1,
	   'orderby' => 'rand',
	   'exclude' => explode(",", $exclude),
	);
	
	// make sure no endless loop
	$count = 0;
	
	do { 

		// get post
		$images = get_posts($args);
		
		// get image
		$image = $images[0];		

		// make shure this image is excluded next search
		array_push($args['exclude'], $image->ID);
		
		// get parent post
		$parent = get_post($image->post_parent);
		
		// extract image attributes
		$thumbnail_attributes = wp_get_attachment_image_src( $image->ID, 'thumbnail');
		$image_medium_attributes = wp_get_attachment_image_src( $image->ID, 'medium' );
		$image_large_attributes = wp_get_attachment_image_src( $image->ID, 'large' );
		$image_full_attributes = wp_get_attachment_image_src( $image->ID, 'full' );		
		$image_attributes = wp_get_attachment_image_src( $image->ID, $size );		
		
		// prepare data-array
		$data['image_title'] = $image->post_title;
		
		$data['image_url'] = $image_attributes[0];
		$data['image_width'] = $image_attributes[1];
		$data['image_height'] = $image_attributes[2];
		$data['image_size'] = $m->render("width={{image_width}} height={{image_height}}", $data);
		$data['image'] = $m->render("<img src='{{image_url}}' alt='{{image_title}}' title='{{image_title}}' {{image_size}} />" ,$data);	
		
		$data['full_image_url'] = $image_full_attributes[0];
		$data['full_image_width'] = $image_full_attributes[1];
		$data['full_image_height'] = $image_full_attributes[2];
		$data['full_image_size'] = $m->render("width={{full_image_width}} height={{full_image_height}}", $data);
		$data['full_image'] = $m->render("<img src='{{full_image_url}}' alt='{{image_title}}' title='{{image_title}}' {{full_image_size}} />" ,$data);
	
		$data['large_image_url'] = $image_large_attributes[0];
		$data['large_image_width'] = $image_large_attributes[1];
		$data['large_image_height'] = $image_large_attributes[2];
		$data['large_image_size'] = $m->render("width={{large_image_width}} height={{large_image_height}}", $data);
		$data['large_image'] = $m->render("<img src='{{large_image_url}}' alt='{{image_title}}' title='{{image_title}}' {{large_image_size}} />" ,$data);			
	
		$data['medium_image_url'] = $image_medium_attributes[0];
		$data['medium_image_width'] = $image_medium_attributes[1];
		$data['medium_image_height'] = $image_medium_attributes[2];
		$data['medium_image_size'] = $m->render("width={{medium_image_width}} height={{medium_image_height}}", $data);
		$data['medium_image'] = $m->render("<img src='{{medium_image_url}}' alt='{{image_title}}' title='{{image_title}}' {{medium_image_size}} />" ,$data);
			
		$data['thumbnail_url'] = $thumbnail_attributes[0];	
		$data['thumbnail_width'] = $thumbnail_attributes[1];
		$data['thumbnail_height'] = $thumbnail_attributes[2];
		$data['thumbnail_size'] = $m->render("width={{thumbnail_width}} height={{thumbnail_height}}", $data);
		$data['thumbnail'] = $m->render("<img src='{{thumbnail_url}}' alt='{{image_title}}' title='{{image_title}}' {{thumbnail_size}} />" ,$data);			
				
		$data['post_url'] =  get_permalink($parent->ID);
		$data['post_title'] = $parent->post_title;
		$data['post_date'] =  date($date_format, strtotime($parent->post_date));
		$data['post_author'] = get_the_author_meta('display_name',$parent->post_author);
		$data['post'] = $m->render("<a href='{{post_url}}'>{{post_title}}</a>", $data);		
	
		$post_category =  get_the_category($image->post_parent);
		
		$count++;
		if ($count >=  100)
			return "<span style='color:#cc0000'>Timeout!</span>";	
		
	} while ($post_category[0]->cat_ID!=$category && $category!=null);
	
	// render the template						
	return $m->render($tmpl, $data);	
	
}


/*
 * Register the Plugin-Description-Page
 */
function garees_random_image_plugin_menu() {
	add_plugins_page("Garee's Random Image", "Garee's Random Image", 'read', 'garees_random_image', 'garees_random_image_show_menu');
}

/*
 * Include CSS- and JS-File in the header
 */ 
function garees_random_image_head() {
		
	if(is_admin()) {
	
		// load admin css
		if(!defined('GAREE_ADMINCSS_IS_LOADED')) {
			echo '<link rel="stylesheet" id="garees-admin-css"  href="' . plugins_url('garee_admin.css', __FILE__) . '" type="text/css" media="all" />'. "\n";
			define('GAREE_ADMINCSS_IS_LOADED', true);
		}
				
		// Javascript für Flattr einfügen
		if(!defined('GAREE_FLATTRSCRIPT_IS_LOADED')) {
			echo '<script type="text/javascript" src="' . GAREE_FLATTRSCRIPT . '"></script>';
			define('GAREE_FLATTRSCRIPT_IS_LOADED', true);
		}
	}
}

/*
 * Insert a Description (Settings)-Link on the plugin-overview
 */    
function garees_random_image_plugin_actions( $links, $file ){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="plugins.php?page=garees_random_image">' . __('Description') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}

/*
 * Generate Description-Page for the Admin
 */
function gareeBoxRandomImage() {
?>

<div id="gareeBox"> <small>If you like Garee's Random Image plugin, you can buy me a coffee!<br />
  </small><br />
  <a class="FlattrButton" style="display:none;" href="http://www.garee.ch/garees-random-image/"></a>
  <noscript>
  <a href="http://flattr.com/thing/429908/Wordpress-Plugin-Garees-Random-Image" target="_blank"> <img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
  </noscript>
  <br />
  or<br />
  <a href="https://flattr.com/donation/give/to/garee" title="Donate (via Flattr)" id="flattrDonate" target="_blank"></a> </div>
<?php
}

/*
 * Generate Description-Page for the Admin
 */
function garees_random_image_show_menu() {
	gareeBoxRandomImage();
?>
<div id='gareeMain'>
  <h1>Garee's Random Image </h1>
  <p>Just insert the following shortcode anywhere in your blog (for use in a widget: use the text-widget and insert the shortcode there) </p>
  <pre>[random_image] </pre>
  <h2>Attributes</h2>
  <p>Here's a list of all attributes the shortcode accepts. Additional attributes may be available depending on the chosen template</p>
  <dl>
    <dt>template</dt>
    <dd> a predefined template consisting of a html- and optionally a css-file in the subdirectory &quot;templates&quot; of the plugin-folder. If left blank the template 'default' is chosen.</dd>
    <dd>
      <?php
if ($handle = opendir(plugin_dir_path(__FILE__) . "templates")) {
	
    while (false !== ($file = readdir($handle))) {		
        if ($file != "." && $file != ".." ) {
			$file_arr = explode(".", $file);
			if ($file_arr[1]=="html") {
				$template_html[$file_arr[0]] = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/' . $file);	
			} else if ($file_arr[1]=="css") {
				$template_css[$file_arr[0]] = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/' . $file);
			}
			
        }
    }
    closedir($handle);
	
	echo "<table><tr><th>name</th><th>html</th><th>css</th><th>description</th></tr>";
	foreach($template_html as $key => $value)  { 
		echo "<td>$key</td><td>yes (<a href='".plugin_dir_url(__FILE__)."templates/".$key.".html' target='_blank' class='tooltip'>show<span class='classic code'>".htmlspecialchars($value)."</span></a>)</td>";
		if ($template_css[$key])
			echo "<td>yes (<a href='".plugin_dir_url(__FILE__)."templates/".$key.".css' target='_blank' class='tooltip'>show<span class='classic code'>".$template_css[$key]."</span></a>)</td>"; 
		else 
			echo "<td>no</td>";	
		
		if (preg_match("/{{!(.*)}}/", $value, $matches) == 1) {
			echo "<td>".make_clickable(trim($matches[1]))."</td>";
		} else {
			echo "<td>-</td>";		
		}
		echo "</tr>";
	}
	echo "</table>";
}

?>
    </dd>
    <dd> If the template-attribute is missing you have to define a template yourself (*). This can be done using the following enclosing shortcut:</dd>
  </dl>
  <pre>[random_image_template]*[/random_image_template]</pre>
  <dl>
    <dd> Check out the examples below to see how to define your own template. </dd>
    <dt>category</dt>
    <dd> a number indicating the ID auf the category for your random image. The category will be the post-category where your image is published! When left blank a random image is chosen.</dd>
    <dd>Your categories and the corresponding IDs (reload this page to update):</dd>
    <dd>
      <?php
	$categories = get_categories( );
	echo "<table><tr><th>name</th><th>ID</th><th>count</th></tr>";
	foreach($categories as $category) {
		echo "<tr>";
		echo "<td>".$category->name."</td>";
		echo "<td>".$category->cat_ID."</td>";
		echo "<td>".$category->count."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>
    </dd>
    <dt>size</dt>
    <dd> Either 'full', 'large', 'medium', 'thumbnail' or something like '300,200' to get an image that fits in a box  with a width of 300px and a height of 200px. This picture can be used by the template. If left blank, 'full' will be chosen.</dd>
    <dt>filetype</dt>
    <dd> Either 'jpeg', 'gif', 'png' or 'any'. Restricts the search to this filetype. If left blank, 'jpeg' will be chosen.</dd>
     <dt>date_format</dt>
    <dd> Choose the format for the Mustache-tag {{post_date}}. For more information check out the syntax of <a href="http://php.net/manual/en/function.date.php" target="_blank">PHP-Date-function</a>'s format-parameter.<span class="version">(v0.9+)</span></dd>
    <dt>exclude</dt>
    <dd>Enter comma-separated IDs of images that need to be excluded from random selection.<span class="version">(v0.9+)</span></dd>
  </dl>
  <h2>Some Examples</h2>
  <p>The following examples are shown on my plugin-site: <a href="http://www.garee.ch/wordpress/garees-random-image/live-demo/" target="_blank">Live-Demo</a></p>
  <p>Show a random image:</p>
  <pre>[random_image]</pre>
  <p>Show a medium-sized random image from category 7 using the  &quot;scrapbook&quot;-template:</p>
  <pre>[random_image template='scrapbook' category='7' size='medium']</pre>
  <p>Show a random thumbnail from category 12 using the &quot;rounded&quot;-template:</p>
  <pre>[random_image template='rounded' category='12' size='thumbnail']
</pre>
  <p>Use the custom-attribute 'style' of the template to choose the second style for the rounded image and make it smaller:</p>
  <pre>[random_image template='rounded' style='2' category='12' size='60,60']</pre>
  <p>Show a random image from any category in full resolution and add the title of the post with a link to the post itself:</p>
  <pre>[random_image]{{{image}}}&lt;br /&gt;{{{post}}}[/random_image]</pre>
  <p>Show a random image from any category using the &quot;polaroid&quot;-template. Use the template's custom attributes to change the font-size and the width of the image: </p>
  <pre>[random_image template='polaroid' polaroid_font_size='14px' polaroid_width='360px']
</pre>
  <p>Show a random image from any category using the &quot;caption&quot;-template. Use the new shortcode-option &quot;date_format&quot; to define a custom date-format: </p>
  <pre>[random_image template='caption' date_format='D, d M Y' size='large']
  </pre>
<h2>Template-Files</h2>
  <p>A couple of templates come with the plugin. You can write and install additional templates yourself.  A template consists of a html-file with the Mustache-template and (optionally) a css-file with the same filename as the html-file. If your css-code has images, put them in a subfolder of the templates-folder named after your plugin. Then upload all files via FTP and insert the shortcode for your template. If you define your template directly in the shortcode, you cannot link to a additional css-file. Of course you can include css-styling directly into the html-template.</p>
  <h2>Mustache-Templates</h2>
  <p>The following components can be used to build your own html-template. Additional components can be generated by inserting custom attributes in the shortcode. The components are only available in the html-template, not in the css-file!  Check out the template-files in the plugin to see how it's still possible to change css-values with custom attributes from your shortcode. For more infos about Mustache-syntax check out the PHP-implementation and the manuals on <a href="http://mustache.github.com/" target="_blank">http://mustache.github.com/</a></p>
  <dl class='mustache_list'>
    <dt>{{image_title}}</dt>
    <dd>the title of the image as set in the Media Library</dd>
    <dt>{{{image}}}</dt>
    <dd>inserts the image (generated using the following template: &quot;&lt;img src='&lt;{{image_url}}' alt='{{image_title}}' title='{{image_title}}' /&gt;&quot;)</dd>
    <dt>{{image_url}}</dt>
    <dd>url to the image used to show or link to it</dd>
    <dt>{{image_width}}</dt>
    <dd>width of the image in pixel</dd>
    <dt>{{image_height}}</dt>
    <dd>height of the image in pixel</dd>
    <dt>{{image_size}}</dt>
    <dd>image-size for use in an &lt;img&gt;-Tag (generated using the following template: &quot;width={{image_width}} height={{image_height}}&quot;)</dd>
    <dt>{{{full_image}}}</dt>
    <dd>inserts the image (generated using the following template: &quot;&lt;img src='&lt;{{full_image_url}}' alt='{{image_title}}' title='{{image_title}}' /&gt;&quot;)</dd>
    <dt>{{full_image_url}}</dt>
    <dd>url to the image used to show or link to it</dd>
    <dt>{{full_image_width}}</dt>
    <dd>width of the image in pixel</dd>
    <dt>{{full_image_height}}</dt>
    <dd>height of the image in pixel</dd>
    <dt>{{full_image_size}}</dt>
    <dd>image-size for use in an &lt;img&gt;-Tag (generated using the following template: &quot;width={{full_image_width}} height={{full_image_height}}&quot;)</dd>
    <dt>{{{large_image}}}</dt>
    <dd>inserts the image in the format 'large' (generated using the following template: &quot;&lt;img src='&lt;{{large_image_url}}' alt='{{image_title}}' title='{{image_title}}' /&gt;&quot;)</dd>
    <dt>{{large_image_url}}</dt>
    <dd>url to the large image used to show or link to it</dd>
    <dt>{{large_image_width}}</dt>
    <dd>width of the large image in pixel</dd>
    <dt>{{large_image_height}}</dt>
    <dd>height of the large image in pixel</dd>
    <dt>{{large_image_size}}</dt>
    <dd>large-image-size for use in an &lt;img&gt;-Tag (generated using the following template: &quot;width={{large_image_width}} height={{large_image_height}}&quot;)</dd>
    <dt>{{{medium_image}}}</dt>
    <dd>inserts the image in the format 'medium' (generated using the following template: &quot;&lt;img src='&lt;{{medium_image_url}}' alt='{{image_title}}' title='{{image_title}}' /&gt;&quot;)</dd>
    <dt>{{medium_image_url}}</dt>
    <dd>url to the medium image used to show or link to it</dd>
    <dt>{{medium_image_width}}</dt>
    <dd>width of the medium image in pixel</dd>
    <dt>{{medium_image_height}}</dt>
    <dd>height of the medium image in pixel</dd>
    <dt>{{medium_image_size}}</dt>
    <dd>medium-image-size for use in an &lt;img&gt;-Tag (generated using the following template: &quot;width={{medium_image_width}} height={{medium_image_height}}&quot;</dd>
    <dt>{{{thumbnail}}}</dt>
    <dd>inserts the thumbnail (generated using the following template: &quot;&lt;img src='&lt;{{thumbnail_url}}' alt='{{image_title}}' title='{{image_title}}' /&gt;&quot;)</dd>
    <dt>{{thumbnail_url}}</dt>
    <dd>url to the thumbnail used to show it</dd>
    <dt>{{thumbnail_width}}</dt>
    <dd>height of the thumbnail in pixel</dd>
    <dt>{{thumbnail_height}}</dt>
    <dd>height of the thumbnail in pixel</dd>
    <dt>{{thumbnail_size}}</dt>
    <dd>thumbnail-size for use in an &lt;img&gt;-Tag (generated using the following template: &quot;width={{thumbnail_width}} height={{thumbnail_height}}&quot;)</dd>
    <dt>{{{post}}}</dt>
    <dd>link to the post the image is attached to (generated using the following template: &quot;&lt;a href='{{post_url}}'&gt;{{post_title}}&lt;/a&gt;&quot;)</dd>
    <dt>{{post_url}}</dt>
    <dd>url of the post the image is attached to</dd>
    <dt>{{post_title}}</dt>
    <dd>title of the post the image is attached to</dd>
    <dt>{{post_date}}</dt>
    <dd>date of the post the image is attached to (formatted with the date_format-option or automatically if option missing)</dd>
    <dt>{{post_author}}</dt>
    <dd>name of the author who wrote the post which the image is attached to <span class="version">(v0.9+)</span></dd>
  </dl>
</div>
<?php
}

add_filter('the_posts', 'garees_random_image_scripts_and_styles'); // the_posts gets triggered before wp_head
add_filter('widget_text', 'garees_random_image_scripts_and_styles_widget'); // try to load css if shortcode in text-widget


/*
 * Find shortcode and extract css-filename to enqueue the correct stylesheet
 */    
function garees_random_image_scripts_and_styles($posts){
	if (empty($posts)) return $posts;
 
	$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
	$css_file = null;
	foreach ($posts as $post) {
		if (preg_match_all("/\[random_image[0-9a-z =']* template=['\"]{0,1}([0-9a-z _]*)['\" \]]/", $post->post_content, $matches, PREG_PATTERN_ORDER) > 0) {	
			$shortcode_found = true; // bingo!   !!! Findet nur in der ersten Post (var wird überschrieben, man müsste push machen) !!!!!
			$css_files = $matches[1]; 
			//break;
		}
	}
 
	if ($shortcode_found) {
		// enqueue here
		foreach($css_files as $css_file) {
			if (file_exists(plugin_dir_path(__FILE__)."templates/" . $css_file.".css")) {
				wp_enqueue_style('garees-random-image-'.$css_file, plugin_dir_url(__FILE__).'templates/'.$css_file.".css");
			}
		}
	}
 
	return $posts;
}

/*
 * Find shortcode and extract css-filename to enqueue the correct stylesheet if in text-widget!
 */    
function garees_random_image_scripts_and_styles_widget($text) {
 
	$css_file = null;
	if (preg_match_all("/\[random_image[0-9a-z =']* template=['\"]{0,1}([0-9a-z _]*)['\" \]]/", $text, $matches, PREG_PATTERN_ORDER) > 0) {	
		$shortcode_found = true; // bingo!   !!! Findet nur in der ersten Post (var wird überschrieben, man müsste push machen) !!!!!
		$css_files = $matches[1]; 
	}
	if ($shortcode_found) {
		// enqueue here
		foreach($css_files as $css_file) {
			if (file_exists(plugin_dir_path(__FILE__)."templates/" . $css_file.".css")) {
				$text .= '<link rel="stylesheet" id="garees-random-image-'.$css_file.'"  href="' . plugin_dir_url(__FILE__) . 'templates/'.$css_file . '.css" type="text/css" media="all" />'. "\n";
				//wp_enqueue_style('garees-random-image-'.$css_file, plugin_dir_url(__FILE__).'templates/'.$css_file.".css");
			}
		}
	}
	
	//wp_enqueue geht hier nicht mehr -> muss wohl css laden und in html direkt ausgeben oder mit js laden!
	// noch besser: random image widget
 	//wp_enqueue_style('garees-random-image-test', plugin_dir_url(__FILE__).'templates/test.css');
	return $text;
}

// add shortcuts
add_shortcode( 'random_image', 'garees_random_image' );
add_shortcode( 'random_image_template', 'garees_random_image' );

// actions for admins
if(is_admin()) {
	add_action('admin_menu', 'garees_random_image_plugin_menu');
	add_action('admin_head', 'garees_random_image_head');
	add_action('plugin_action_links','garees_random_image_plugin_actions',10, 2);
}
?>
