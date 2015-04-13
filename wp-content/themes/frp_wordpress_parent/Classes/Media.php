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

	protected function wrap_oem_video($html='', $url='', $attr, $post_ID) {
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

	//////////////////////////////////////////////////

	public function get_post_thumbnail_exif($postID = 0){

		global $post;

		if($post && $post->ID){
			$thumbnailID = get_post_thumbnail_id($post->ID);
			if($thumbnailID){
				$metaData = wp_get_attachment_metadata($thumbnailID);
				$paths = wp_upload_dir();
				$pre = $paths['basedir'];

				$this->app->view->post_thumbnail = $pre . '/' . $metaData['file'];

				$this->app->view->exif = exif_read_data($this->app->view->post_thumbnail, 0, true);

				$gps = $this->app->view->exif['GPS'];

				if(isset($gps['GPSLongitude']) && isset($gps['GPSLongitude'])){
					$this->extend_post_thumbnail_exif();
				}
			}
		}
	}

	//////////////////////////////////////////////////

	public function DMStoDEC($deg,$min,$sec){
		// Converts DMS ( Degrees / minutes / seconds )
		// to decimal format longitude / latitude
		return $deg+((($min*60)+($sec))/3600);
	}//DMStoDEC

	//////////////////////////////////////////////////

	public function DECtoDMS($dec){
		// Converts decimal longitude / latitude to DMS
		// ( Degrees / minutes / seconds )

		// This is the piece of code which may appear to
		// be inefficient, but to avoid issues with floating
		// point math we extract the integer part and the float
		// part by using a string function.

		$vars = explode(".",$dec);
		$deg = $vars[0];
		$tempma = "0.".$vars[1];

		$tempma = $tempma * 3600;
		$min = floor($tempma / 60);
		$sec = $tempma - ($min*60);

		return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
	}//DECtoDMS

	//////////////////////////////////////////////////

	private function extend_post_thumbnail_exif(){

		$exifdata = &$this->app->view->exif['GPS'];

		/*
		[GPSLatitudeRef] => N
		[GPSLatitude] => Array
		(
			[0] => 57/1
			[1] => 31/1
			[2] => 21334/521
		)

		[GPSLongitudeRef] => W
		[GPSLongitude] => Array
		(
			[0] => 4/1
			[1] => 16/1
			[2] => 27387/1352
		)
		*/

		$GPS = array();

		$GPS['lat']['deg'] = explode('/',$exifdata['GPSLatitude'][0]);
		$GPS['lat']['deg'] = $GPS['lat']['deg'][0] / $GPS['lat']['deg'][1];
		$GPS['lat']['min'] = explode('/',$exifdata['GPSLatitude'][1]);
		$GPS['lat']['min'] = $GPS['lat']['min'][0] / $GPS['lat']['min'][1];
		$GPS['lat']['sec'] = explode('/',$exifdata['GPSLatitude'][2]);
		$GPS['lat']['sec'] = floatval($GPS['lat']['sec'][0]) / floatval($GPS['lat']['sec'][1]);

		$exifdata['GPSLatitudeDecimal'] = $this->DMStoDEC($GPS['lat']['deg'],$GPS['lat']['min'],$GPS['lat']['sec']);
		if($exifdata['GPSLatitudeRef']=='S'):
			$exifdata['GPSLatitudeDecimal'] = 0-$exifdata['GPSLatitudeDecimal'];
		endif;

		$GPS['lon']['deg'] = explode('/',$exifdata['GPSLongitude'][0]);
		$GPS['lon']['deg'] = $GPS['lon']['deg'][0] / $GPS['lon']['deg'][1];
		$GPS['lon']['min'] = explode('/',$exifdata['GPSLongitude'][1]);
		$GPS['lon']['min'] = $GPS['lon']['min'][0] / $GPS['lon']['min'][1];
		$GPS['lon']['sec'] = explode('/',$exifdata['GPSLongitude'][2]);
		$GPS['lon']['sec'] = floatval($GPS['lon']['sec'][0]) / floatval($GPS['lon']['sec'][1]);

		$exifdata['GPSLongitudeDecimal'] = $this->DMStoDEC($GPS['lon']['deg'],$GPS['lon']['min'],$GPS['lon']['sec']);
		if($exifdata['GPSLongitudeRef']=='W'):
			$exifdata['GPSLongitudeDecimal'] = 0-$exifdata['GPSLongitudeDecimal'];
		endif;

		$exifdata['GPSCalculatedDecimal'] = $exifdata['GPSLatitudeDecimal'].','.$exifdata['GPSLongitudeDecimal'];
		//$exifdata['googlemaps_image'] = '<img src="http://maps.googleapis.com/maps/api/staticmap?center='.$exifdata['GPSCalculatedDecimal'].'&zoom=14&size=980x540&maptype=hybrid&markers=color:red|'.$exifdata['GPSCalculatedDecimal'].'&sensor=false" alt="google map">';

		$size=getimagesize($this->app->view->post_thumbnail,$info);
		if(isset($info['APP13'])){
			$iptc = iptcparse($info['APP13']);

			if (is_array($iptc)) {
				$exifdata['iptc']['caption'] = isset($iptc["2#120"])?$iptc["2#120"][0]:'';
				$exifdata['iptc']['graphic_name'] = isset($iptc["2#005"])?$iptc["2#005"][0]:'';
				$exifdata['iptc']['urgency'] = isset($iptc["2#010"])?$iptc["2#010"][0]:'';
				$exifdata['iptc']['category'] = @$iptc["2#015"][0];

				 // note that sometimes supp_categories contans multiple entries
				$exifdata['iptc']['supp_categories'] = @$iptc["2#020"][0];
				$exifdata['iptc']['spec_instr'] = @$iptc["2#040"][0];
				$exifdata['iptc']['creation_date'] = @$iptc["2#055"][0];
				$exifdata['iptc']['photog'] = @$iptc["2#080"][0];
				$exifdata['iptc']['credit_byline_title'] = @$iptc["2#085"][0];
				$exifdata['iptc']['city'] = @$iptc["2#090"][0];
				$exifdata['iptc']['state'] = @$iptc["2#095"][0];
				$exifdata['iptc']['country'] = @$iptc["2#101"][0];
				$exifdata['iptc']['otr'] = @$iptc["2#103"][0];
				$exifdata['iptc']['headline'] = @$iptc["2#105"][0];
				$exifdata['iptc']['source'] = @$iptc["2#110"][0];
				$exifdata['iptc']['photo_source'] = @$iptc["2#115"][0];
				$exifdata['iptc']['caption'] = @$iptc["2#120"][0];

				$exifdata['iptc']['keywords'] = @$iptc["2#025"];
			}
		}

	}
}