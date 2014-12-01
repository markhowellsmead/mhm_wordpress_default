<?php

namespace Frp\WordPress;

class Menus {

//////////////////////////////////////////////////

	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
    	add_theme_support('menus');
    }

//////////////////////////////////////////////////

	protected function clean_menu($html=''){
		//	Remove title attribute from menu links
		return preg_replace('/title=\"(.*?)\"/','',$html);
	}

	//////////////////////////////////////////////////

	protected function register_my_menus($menus=array()) {
		if(!empty($menus)){
			register_nav_menus($menus);
			add_action( 'init', array($this, 'register_my_menus') );
		}
	}
}