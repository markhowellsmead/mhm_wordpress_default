<?php
/**
* Functions for translations and language features
* These functions will be loaded automatically by the App class.
*
* @since 	11.03.15
*/

namespace Frp\WordPress;

class Language {
	
	var $app = null;
	var $languages_loaded = false;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
		$this->load_translations();
	}

	//////////////////////////////////////////////////

	private function load_translations(){
		/**
		 * Loads translation files if the relevant directory exists.
		 * Translations in the parent theme can be accessed using the key $this->app->parentkey
		 * Translations in the child theme can be accessed using the key $this->app->key
		 *
		 * @since 	11.03.15
		 */

		if( is_dir($this->app->paths['parent_path'].'/Resources/Private/Language') ){
			$this->languages_loaded = load_theme_textdomain($this->app->parentkey, $this->app->paths['parent_path'].'/Resources/Private/Language');
		}
		if( is_dir($this->app->paths['child_path'].'/Resources/Private/Language') ){
			$this->languages_loaded = load_theme_textdomain($this->app->key, $this->app->paths['child_path'].'/Resources/Private/Language');
		}

	}

}
