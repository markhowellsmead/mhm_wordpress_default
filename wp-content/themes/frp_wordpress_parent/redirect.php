<?php
/*
Template Name: Redirect
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