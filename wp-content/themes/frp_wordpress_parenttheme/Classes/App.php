<?php

namespace Frp\WordPress;

class App {

	public
		$key,
		$version = 0,
		$themeoptions = array(),
		$viewdata = array(),
		$blog_info = array(),
		$paths = array();

	private 
		$imported_classes = array();
			
	private static $instance;

	//////////////////////////////////////////////////

	public static function getSingleton(){
		if (empty(self::$instance)){
			self::$instance = new App();
		}
		return self::$instance;
	}

	//////////////////////////////////////////////////

	function __construct(){

		$this->initialize();
		
		add_action('wp_head', array($this, 'add_app_icons'));
		add_action('wp_head', array($this, 'add_favicon'));
		add_action('wp_head', array($this, 'add_meta'));

	}

	//////////////////////////////////////////////////

	private function initialize(){

		/**
		 * This theme has only been tested in WordPress 4.0 or later.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '4.0', '<' ) ) {
			require __DIR__. '/includes/back-compat.php';
		}

		// functionality which is always required
		$this->extend('helpers');
		$this->extend('core');
		$this->set_paths();
		$this->set_keys();
		$this->extend('language');
		$this->extend('post');
		$this->extend('media');
		$this->load_configuration();
		$this->load_theme_options();
	}

	//////////////////////////////////////////////////

	public function __call($method, $args){
		if($this->imported_functions && array_key_exists($method, $this->imported_functions)){
			$args[] = &$this;
			return call_user_func_array(array($this->imported_functions[$method], $method), $args);
		}else{
			die('Call to undefined method/class function: ' . $method);
		}
	}//__call

	//////////////////////////////////////////////////

	public function extend($functionality, $use_child = false){
		/*
			magic function __call (above) MUST BE USED with this.
			
			load additional class functionality on request.
			looks for a file with the name e.g. Youtube.php
			in the same folder, which must contain a class called 
			e.g. Youtube (i.e. $functionality all lowercase but 
			with upper case first letter)

			usage:

			$this->extend('youtube');
			return $this->getVideoTitle(intval($_GET['videoID']));
			
		*/

		if($use_child){
			$source_folder = $this->paths['template_path'].'/Classes';
		}else{
			$source_folder = __DIR__;
		}

		$functionality = strtolower($functionality);
		$sub_class_name = ucfirst(strtolower($functionality));
		$sub_class_file = $source_folder.'/'.$functionality. '.php';

		if(!file_exists($sub_class_file)){
			throw new \Exception(sprintf( __('Could not find sub class file for functionality %s', $this->key), $functionality));
		}

		$sub_class=null;

		// don't load the same extender class more than once
		if(!in_array($sub_class_name, $this->imported_classes)){

			require_once($sub_class_file);

			$sub_class_name = 'Frp\WordPress\\' . $sub_class_name;

			$sub_class = new $sub_class_name($this);
			$sub_class_functions = get_class_methods($sub_class);
			
			array_push($this->imported_classes, $sub_class_name);
			//array_push($this->imported_functions, array($sub_class_name, $sub_class));

			// overload the main class with the new function
			// but only where the function has not already been pulled in
			// this avoids conflicts where multiple functions have the same name
			
			foreach($sub_class_functions as $key => $function_name){
				$this->imported_functions[$function_name] = &$sub_class;
				$this->$functionality->$function_name = &$sub_class_functions[$function_name];
			}
			
		}

		return (bool)$sub_class;
	}//extend

}