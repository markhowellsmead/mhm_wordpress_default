<?php

namespace Frp\WordPress;

class Youtube {

	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
	}

	////////////////////////////////////////////////////////////

	public function getIDfromURL($url=''){
		// get the ID number from a URL like http://www.youtube.com/watch?v=AHn5Q15kaIA
		parse_str( parse_url( $url, PHP_URL_QUERY ), $params );
		return isset($params['v']) ? $params['v'] : '';
	}//getIDfromURL

	////////////////////////////////////////////////////////////

	public function getVideoThumbnailSRC($url=''){
		/*
		*	@rev 			16.11.2012 13:28 mhm
		*	@param 	url		'http://www.youtube.com/watch?v=Cr2_Dn0e5nU'
		*	@return image src	'http://i.ytimg.com/vi/Cr2_Dn0e5nU/0.jpg'
		*/

		if($url == ''){return '';}
		$id = $this->getIDfromURL($url);
		return $id ? '//i.ytimg.com/vi/'.$id.'/0.jpg' : '';
	}// getVideoThumbnailSRC

}
