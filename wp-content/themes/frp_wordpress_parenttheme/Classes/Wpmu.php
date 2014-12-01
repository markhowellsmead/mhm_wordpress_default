<?php

namespace Frp\WordPress;

class Wpmu {

	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
	}

	//////////////////////////////////////////////////

	public function set_common_upload_dir(&$uploads){

		// usage: add_filter('upload_dir', array($this,'set_common_upload_dir'));

		if(empty($this->upload_dir)){
		    $uploads['basedir'] = $_SERVER['DOCUMENT_ROOT'] . $this->upload_dir;
		    $uploads['baseurl'] = site_url(). $this->upload_dir;
		    $uploads['path'] = $uploads['basedir'] . $uploads['subdir'];
		    $uploads['url'] = $uploads['baseurl'] . $uploads['subdir'];
	   }

	}//set_common_upload_dir

}