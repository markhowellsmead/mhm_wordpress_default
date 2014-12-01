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

	public function get_language_attributes($doctype = 'html') {
		//	sprachvariante der website herausstellen anhand lang= auf <html> tag
		//	ist eine umgeschriebene version der wordpress-version dieser FN
		//	gibt ergebnis zurück anstatt echo() zu machen
		$attributes = array();
		$output = '';

		if ( function_exists( 'is_rtl' ) )
			$attributes[] = 'dir="' . ( is_rtl() ? 'rtl' : 'ltr' ) . '"';

		if ( $lang = get_bloginfo('language') ) {
			if ( get_option('html_type') == 'text/html' || $doctype == 'html' )
				$attributes[] = "lang=\"$lang\"";
			if ( get_option('html_type') != 'text/html' || $doctype == 'xhtml' )
				$attributes[] = "xml:lang=\"$lang\"";
		}

		$output = implode(' ', $attributes);
		$output = apply_filters('language_attributes', $output);

		return $output;
	}//get_language_attributes

	//////////////////////////////////////////////////

	private function load_translations(){
		// load theme translations if available
		if( is_dir($this->app->paths['template_path'].'/Resources/Private/Language') ){
			load_theme_textdomain($this->app->key, $this->app->paths['template_path'].'/Resources/Private/Language');
		}
	}

}