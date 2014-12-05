<?php
/*
Template Name: Redirect

Assigning this template to a page will cause page requests to 
redirect to the page defined in the custom field with the
key “redirect”. If this value is not defined, then no redirect
will occur and a blank page will be displayed.

@package WordPress
@subpackage Frp_WordPress_Parent
@since v1.0
*/

if (have_posts()) : while (have_posts()) : the_post();

	$redirect_meta_key = "redirect";
	for ($i = 0; $i < 2; $i++) {
		$redirect = get_post_meta($wp_query->post->ID, $redirect_meta_key, true);
		// Old Redirect Code if('' != $redirect) { wp_redirect($redirect); exit; }
		if('' != $redirect) { header("Refresh: 0;url=$redirect"); exit; }
		$redirect_meta_key = "Redirect";
	}

endwhile; endif;
?>