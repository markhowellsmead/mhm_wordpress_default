<?php
add_action('wp_enqueue_scripts', 'childtheme_enqueue_style', 11 );
function childtheme_enqueue_style(){
	wp_enqueue_style(basename(plugin_dir_path( __FILE__ )), get_stylesheet_directory_uri().'/css/style.css', null, '1.0.0', 'all');
//	wp_enqueue_style('googlefont', '//fonts.googleapis.com/css?family=Merriweather', null, '1.0.0', 'all');
	
	
	
}
