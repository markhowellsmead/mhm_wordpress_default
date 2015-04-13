<?php

namespace Frp\WordPress;

class Acf {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(App &$app){
		$this->app = $app;
	}

	//////////////////////////////////////////////////

	/**
	 * Save thumbnail exif location to post when a post is saved.
	 *
	 * @param int $post_id The ID of the post.
	 * @param post $post the post.
	 */
	function add_image_location_to_post( $post_id, $post, $update ) {

		$this->app->get_post_thumbnail_exif();

		if( isset($this->app->view->exif['GPS']) && isset($this->app->view->exif['GPS']['GPSCalculatedDecimal']) ){
			$location_data = array(
				'address' => $this->app->view->exif['GPS']['GPSCalculatedDecimal'],
				'lat' => $this->app->view->exif['GPS']['GPSLatitudeDecimal'], 
				'lng' => $this->app->view->exif['GPS']['GPSLongitudeDecimal'],
				'city' => $this->app->view->exif['GPS']['iptc']['city'],
				'state' => $this->app->view->exif['GPS']['iptc']['state'],
				'country' => $this->app->view->exif['GPS']['iptc']['country']
			);
	
			$this->app->add_or_update_meta($post_id, 'location', serialize($location_data));
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Save thumbnail IPTC keywords to the post as post_tag taxonomy entries
	 *
	 * @param int $post_id The ID of the post.
	 * @param post $post the post.
	 */
	function add_image_tags_to_post( $post_id, $post, $update ){

		$this->app->get_post_thumbnail_exif();

		if( isset($this->app->view->exif['GPS']) && isset($this->app->view->exif['GPS']['iptc']) && isset($this->app->view->exif['GPS']['iptc']['keywords']) ){
			wp_set_post_terms($post_id, $this->app->view->exif['GPS']['iptc']['keywords'], 'post_tag', true);
		}

	}

}