<?php

namespace Frp\WordPress;

class App {

	public
		$key,
		$version = '1.0',
		$options = array(),
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
	
	function dump($var,$die=false){
		echo '<pre>' .print_r($var,1). '</pre>';
		if($die){die();}
	}//dump

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
		$this->set_paths();
		$this->set_keys();
		$this->extend('helpers');		// helper functions
		$this->extend('language');		// load translations
		$this->extend('view');			// setup page or post view
		$this->extend('media');			// functions for media / image handling
		$this->extend('admin');			// wp-admin (backend) functions
		
		$this->load_configuration();	// load any special configuration files if available

		add_action( 'wp_enqueue_scripts', array(&$this, 'add_default_style') );

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

	public function extend($functionality, $child_key = ''){
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

		if($child_key!==''){
			$source_folder = $this->paths['child_path'].'/Classes';
		}else{
			$source_folder = $this->paths['parent_path'].'/Classes';
		}

		$functionality = strtolower($functionality);
		$sub_class_name = ucfirst(strtolower($functionality));
		$sub_class_file = $source_folder.'/'.$sub_class_name. '.php';

		$sub_class=null;

		// don't load the same extender class more than once
		if(!in_array($sub_class_name, $this->imported_classes)){

			if(!file_exists($sub_class_file)){
				trigger_error(sprintf( __('Could not find sub class file for functionality “%s”', $this->key), $functionality), E_USER_ERROR);
			}
	
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

	//////////////////////////////////////////////////

	public function set_paths(){

		// Initialize paths for the parent and child themes
		$this->paths['parent_path']			= get_template_directory();
		$this->paths['parent_uri']			= get_template_directory_uri();
		$this->paths['child_path']			= get_stylesheet_directory();
		$this->paths['child_uri']			= get_stylesheet_directory_uri();

		// Configuraiton files and resources like images, CSS and JavaScript are stored in the child theme
		$this->paths['resources_path']		= $this->paths['child_path'].'/Resources';
		$this->paths['resources_uri']		= $this->paths['child_uri'].'/Resources';
		$this->paths['configuration_path']	= $this->paths['child_path'].'/Configuration';

	}

	//////////////////////////////////////////////////

	public function set_keys(){
		// keys are used for e.g. translations. The default key is the name of the child theme folder.
		$this->key = basename($this->paths['child_path']);
	}

	//////////////////////////////////////////////////

	public function load_configuration(){
		// load configuration files for e.g. advanced custom fields
		if( is_dir($this->paths['configuration_path']) ){
			$this->extend('filesystem');
			$this->configuration = $this->getFiles($this->paths['configuration_path']);
			foreach($this->configuration as $file){
				include_once($file);
			}
		}
	}

	/////////////////////////////////////////////
	
	public function add_default_style($file='', $media='all', $filekey=''){
		wp_enqueue_style( 'css-reset', $this->paths['parent_uri'] . '/Resources/Public/Css/css-reset.css', null, $this->version, 'all');
		wp_enqueue_style( 'basic', $this->paths['parent_uri'] . '/Resources/Public/Css/basic.css', array('css-reset'), $this->version, 'all');
		wp_enqueue_style( $this->key.'-style', get_stylesheet_uri(), array('css-reset', 'basic'), $this->version, 'all');
		wp_enqueue_style( $this->key.'-childstyle', $this->paths['resources_uri'].'/Public/Css/style.css', array('css-reset', 'basic', $this->key.'-style'), $this->version, 'all');
	}

	//////////////////////////////////////////////////

	public function add_style($path='', $key = '', $dependency = null){
		if(file_exists($this->paths['child_path'] . $path) && $key!==''){
			wp_enqueue_style( $this->key.'-'.$key, $this->paths['child_uri'].$path, $dependency, $this->version, 'all');
		}else{
			if($key == ''){
				trigger_error(sprintf(__('CSS key for %s was not defined', $this->key), $path), E_USER_WARNING);
			}else{
				trigger_error(sprintf(__('%s does not exist', $this->key), $path), E_USER_WARNING);
			}
		}
	}

	//////////////////////////////////////////////////

	public function add_favicon(){
		if(file_exists($this->paths['resources_path'].'/Images/favico.ico')){
			echo '<link rel="icon" type="image/x-icon" href="' .$this->paths['resources_uri'].'/Images/favico.ico" />';
		}else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/favico.ico')){
			echo '<link rel="icon" type="image/x-icon" href="' .$_SERVER['DOCUMENT_ROOT'].'/favico.ico" />';
		}
	}

	//////////////////////////////////////////////////

	public function add_app_icons(){
		if(file_exists($this->paths['resources_path'].'/Images/icon-touch.png')){
			echo '<link rel="apple-touch-icon-precomposed" type="image/png" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="72x72" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="114x114" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="144x144" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />';
		}
	}

	//////////////////////////////////////////////////

	public function add_meta(){

		echo '
			<meta property="og:locale" content="' .WPLANG. '"/>
			<meta property="og:type" content="website"/>
			<meta property="og:url" content="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']. '" />
			<meta property="og:title" content="' .wp_title('–', false, 'right') . get_bloginfo('name') . '" />
			<meta property="og:description" content="' .get_the_excerpt(). '" />';
			
		if(file_exists($this->paths['resources_path'].'/Images/facebook.jpg')){
			echo '<meta property="og:image" content="' .$this->paths['resources_uri'].'/Images/facebook.jpg" />';
		}
	}

	/////////////////////////////////////////////
	
	public static function clean_menu($html){
		return preg_replace('/title=\"(.*?)\"/','',$html);
	}

}