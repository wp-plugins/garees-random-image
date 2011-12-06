<?php 
	define("ABSPATH", str_replace("wp-content/plugins/garees-random-image", "", dirname(__FILE__)));
	
	//The inclusion of these files allows full use of all functions of wordpress
	require_once(ABSPATH.'wp-load.php');
	require_once(ABSPATH.'wp-admin/includes/admin.php');
	
	//echo '<link rel="stylesheet" id="garees-admin-css"  href="' . plugins_url('garee_admin.css', __FILE__) . '" type="text/css" media="all" />'. "\n";
	echo '<link rel="stylesheet" id="admin-css"  href="' . admin_url() . 'css/global.css" type="text/css" media="all" />'. "\n";
	echo '<link rel="stylesheet" id="admin-css"  href="' . admin_url() . 'css/wp-admin.css" type="text/css" media="all" />'. "\n";
	echo '<style type="text/css">body,img {margin:1px;}</style>';
	
	if (isset($_GET['category'])) {
		$category = $_GET['category'];
	} else {
		die("missing get");
	}
	 

	$args_posts = array(
	   'post_type' => 'post',		   
	   'category' => $category,
	   'numberposts' => -1,
	   'post_status' => 'publish',
	);
	
	$posts = get_posts($args_posts);
	$no_posts = count($posts);
	
	$images_found = false;
	
	echo "<div id='gareeMain'>";
	
	foreach ($posts as $post) {	
		
		$args = array(
		   'post_type' => 'attachment',
		   'post_mime_type' => 'image',
		   'numberposts' => -1,
		   'post_status' => null,
		   'post_parent' => $post->ID,
		);		
	
		// get post
		$images = get_posts($args);
		$no_images = count($images);
		
		if ($no_images > 0) {
			foreach ($images as $image) {	
				$attributes = wp_get_attachment_image_src( $image->ID, 'thumbnail');
				$image_title = $image->post_title;
				$image_url = get_permalink($image->ID);
				
				echo '<a href="'.$image_url.'" target="_blank"><img src="'.$attributes[0].'" alt="'.$image_title.'" title="'.$image_title.'" width='.$attributes[1].' height='.$attributes[2].' /></a>';
				$images_found = true;
			}
		}

	}
	if(!($images_found)) {
		echo "<p>There are no images in this category!</p>";
	}
	
	echo "</div>";

?>
