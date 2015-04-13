<?php

namespace Frp\WordPress;

class Menu {

//////////////////////////////////////////////////

	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
    	add_theme_support('menus');
    }

//////////////////////////////////////////////////

	protected function clean_menu($html=''){
		/**
		 * Remove title attribute from menu links
		 */
		return preg_replace('/title=\"(.*?)\"/','',$html);
	}

	//////////////////////////////////////////////////

	public function register_menus($menus = array()) {
		/**
		 * Registers and initializes nav menu function
		 * if an array of menus is passed to this function.
		 * Usage: add following code to the child theme “Theme” class.
		 * $this->app->extend('menu');
		 * $this->app->register_menus(array(
		 * 	'mainmenu' 	=> __('Default navigation', $this->app->key),
		 * 	'submenu' 	=> __('Secondary navigation', $this->app->key)
		 * ));
		 *
		 * Use http://codex.wordpress.org/Function_Reference/wp_nav_menu to display the menu.
		 * e.g. wp_nav_menu(array('theme_location' => 'mainmenu'));
		 */

		if(!empty($menus)){
	    	add_theme_support('menus');
	    	$this->app->menus = $menus;
			add_action( 'init', array($this, 'register_nav_menus') );
		}
	}

	//////////////////////////////////////////////////

	public function register_nav_menus(){
		/**
		 * Called by the WordPress hook. Register the nav menus with the core.
		 */
		if($this->app->menus && !empty($this->app->menus)){
			register_nav_menus($this->app->menus);
		}
	}

}