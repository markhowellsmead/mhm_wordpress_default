<?php
/**
* Functions for media and image handling
* These functions will be loaded automatically by the App class.
*
* @since 	11.03.15
*/

namespace Frp\WordPress;

class Media {
	
	var $app = null;
	
	public function __construct(&$app){
		$this->app = $app;
	}
	
	//////////////////////////////////////////////////
	
	public function pre_option_upload_url_path(){
		/**
		 * pre 3.5, you could hard-code the path prefix for media files
		 * this allows this functionality to continue to work
		 * usage: add_filter('pre_option_upload_url_path', array($this, 'pre_option_upload_url_path'), 10);
		 * set the value of $this->app->paths['pre_option_upload_url_path'] BEFORE this function is called
		 */
		return $this->app->paths['pre_option_upload_url_path'];
	}
	
	//////////////////////////////////////////////////
	
	public function add_image_sizes($sizes = array()){
		/**
		 * Add custom image sizes.
		 * See http://codex.wordpress.org/Post_Thumbnails and http://codex.wordpress.org/Function_Reference/add_image_size
		 *
		 * @param  $sizes (array)	Array of sizes to be added. e.g. 
		 * 	array(
		 * 		'mySizeCropped' => array(
		 * 			'width' => 500, 
		 * 			'height' => 250, 
		 * 			'crop' => true
		 * 		),
		 * 		'mySizeUnCropped' => array(
		 * 			'width' => 600, 
		 * 			'height' => 300, 
		 * 			'crop' => false
		 * 		) 
		 * 	)
		 */

		if(!empty($sizes)){
			foreach($sizes as $key => $attr){
				add_image_size($key, intval($attr['width']), intval($attr['height']), (bool)($attr['crop']));
			}
		}
	}
	
	//////////////////////////////////////////////////
	
	public function remove_thumbnail_dimensions($html='') {
		/**
		 * usage:
		 * add_filter('the_content', array($this,'remove_thumbnail_dimensions'), 10, 1);
		 * add_filter('post_thumbnail_html', array($this,'remove_thumbnail_dimensions'), 10, 1);
		 * add_filter('image_send_to_editor', array($this,'remove_thumbnail_dimensions'), 10, 1);
		 */

	    $html = preg_replace( '/(width|height)=\"\d*\"\s/',"",$html);
	    return $html;
	}
	
	//////////////////////////////////////////////////
	
	protected function wrap_oem_video($html='', $url='', $attr) {
		/**
		 * Add wrapper HTML to an OEM video element
		 * See http://codex.wordpress.org/Embeds for OEM reference
		 * Params passed in automatically via add_filter.
		 * usage: add_filter('embed_oembed_html', array($this,'wrap_oem_video'), 10, 3);
		 */

		if (is_feed() && strpos($html, "<embed src=" ) !== false) {
			return $html;
		}else{
			return '<div class="oembed video">'.$html.'</div>';
		}

	}
}