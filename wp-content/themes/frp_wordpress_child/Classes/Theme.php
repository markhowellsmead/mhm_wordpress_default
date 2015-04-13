<?php
/**
* Theme
* This file and PHP class contains all of the functionality which applies ONLY to this child theme.
* Functions which could be useful in other themes belong in the parent theme class repository.
*
* @since 	02.12.14
*
*/

namespace Frp\WordPress;

class Theme {

	public function __construct(App $app){
		
		$this->app = $app;
		
		/**
		 * Examples of custom theme-specific calls
		 * 
		 * example - add the following line if you need RSS feeds on the site
		 * add_theme_support( 'automatic-feed-links' ); 

		 * example - register custom menus. (See http://codex.wordpress.org/Function_Reference/register_nav_menus )
		 * $this->app->menus = array(
		 * 		'mainmenu' 	=> __('Default navigation', $this->app->key),
		 * 		'submenu' 	=> __('Secondary navigation', $this->app->key)
		 * );
		 * $this->app->extend('menus');
		 * 
		 */
		/*$this->app->menus = array(
			'mainmenu' 	=> __('Default navigation', $this->app->key),
			'submenu' 	=> __('Secondary navigation', $this->app->key)
		);*/

		$this->app->extend('menu');
		$this->app->register_menus(array(
			'mainmenu' 	=> __('Default navigation', $this->app->key),
			'submenu' 	=> __('Secondary navigation', $this->app->key)
		));

	}
	
}