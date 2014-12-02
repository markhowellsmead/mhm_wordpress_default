<?php

namespace Frp\WordPress;

class Language {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
		$this->load_translations();
	}

	//////////////////////////////////////////////////

	private function load_translations(){
		// load theme translations if available
		if( is_dir($this->app->paths['parent_path'].'/Resources/Private/Language') ){
			load_theme_textdomain($this->app->key, $this->app->paths['parent_path'].'/Resources/Private/Language');
		}
		if( is_dir($this->app->paths['child_path'].'/Resources/Private/Language') ){
			load_theme_textdomain($this->app->key, $this->app->paths['child_path'].'/Resources/Private/Language');
		}
	}

}