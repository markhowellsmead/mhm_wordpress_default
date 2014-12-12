<?php

namespace Frp\WordPress;

class Media {
	
	var $app = null;
	
	public function __construct(&$app){
		$this->app = $app;
	}
	
	//////////////////////////////////////////////////
	
	public function pre_option_upload_url_path(){
		// pre 3.5, you could hard-code the path prefix for media files
		// this allows this functionality to continue to work
		// usage: add_filter('pre_option_upload_url_path', array($this, 'pre_option_upload_url_path'), 10);
		return '/my/custom/path/prefix';
	}
	
	//////////////////////////////////////////////////
	
	public function add_image_sizes($sizes = array()){
		if(!empty($sizes)){
			foreach($sizes as $key => $attr){
				add_image_size($key, intval($attr['width']), intval($attr['height']), (bool)($attr['crop']));
			}
		}
	}
	
	//////////////////////////////////////////////////
	
	public function remove_thumbnail_dimensions($html='') {

		/*
			usage:
			add_filter('the_content', array($this,'remove_thumbnail_dimensions'), 10, 1);
			add_filter('post_thumbnail_html', array($this,'remove_thumbnail_dimensions'), 10, 1);
			add_filter('image_send_to_editor', array($this,'remove_thumbnail_dimensions'), 10, 1);
		*/

	    $html = preg_replace( '/(width|height)=\"\d*\"\s/',"",$html);
	    return $html;
	}
	
	//////////////////////////////////////////////////
	
	protected function wrap_oem_video($html='', $url='', $attr, $post_ID) {

		// usage: add_filter('embed_oembed_html', array($this,'wrap_oem_video'), 10, 4);

		if (is_feed() && strpos($html, "<embed src=" ) !== false) {
			return $html;
		}else{
			return '<div class="oembed video">'.$html.'</div>';
		}

	}
}