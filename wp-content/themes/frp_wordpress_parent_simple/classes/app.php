<?php

namespace Frp\WordPress;

class App {

	public
		$key, $parentkey,
		$version = '1.0',
		$options = array(),
		$paths = array(),
		$menus = array();

	private
		$imported_classes = array();

	private static $instance;

	//////////////////////////////////////////////////

	public static function getSingleton(){
		/**
		 * This function checks to see if a current App object
		 * exists, then creates it if necessary.
		 */

		if (empty(self::$instance)){
			self::$instance = new App();
		}
		return self::$instance;
	}

	//////////////////////////////////////////////////

	function dump($var,$die=false){
		echo '<pre>' .print_r($var,1). '</pre>';
		if($die){die();}
	}//dump

	//////////////////////////////////////////////////

	function __construct(){

		$this->initialize();

		add_action( 'wp_head', array(&$this, 'add_app_icons') );
		add_action( 'wp_head', array(&$this, 'add_favicon') );
		add_action( 'wp_head', array(&$this, 'add_meta') );
		add_action( 'wp_head', array(&$this, 'add_og_tags') );

		add_action( 'wp_enqueue_scripts', array(&$this, 'add_scripts') );
		add_action( 'wp_enqueue_scripts', array(&$this, 'add_styles') );
		
		$this->remove_emoji_support();
	}

	//////////////////////////////////////////////////

	private function initialize(){

		/**
		 * This theme has only been tested in WordPress 4.2 or later.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '4.2', '<' ) ) {
			require __DIR__. '/includes/back-compat.php';
		}

		// functionality which is always required
		$this->set_paths();
		$this->set_keys();

	}

	//////////////////////////////////////////////////

	public function set_paths(){

		// Initialize paths for the parent and child themes
		$this->paths['parent_path']	= get_template_directory();
		$this->paths['parent_uri']	= get_template_directory_uri();
		$this->paths['child_path']	= get_stylesheet_directory();
		$this->paths['child_uri']		= get_stylesheet_directory_uri();

		$this->paths['resources_path']		= $this->paths['child_path'].'/Resources';
		$this->paths['resources_uri']			= $this->paths['child_uri'].'/Resources';

	}

	//////////////////////////////////////////////////

	public function set_keys(){
		// keys are used for e.g. translations. The default key is the name of the child theme folder.
		$this->parentkey = basename($this->paths['parent_path']);
		$this->key = basename($this->paths['child_path']);
	}

	//////////////////////////////////////////////////

	public function add_favicon(){
		/**
		 * If the favicon file is available in one of the usual locations,
		 * add a LINK tag to the HTML output. File in the child theme
		 * resources folder takes priority over the one in the web root.
		 */
		if(file_exists($this->paths['resources_path'].'/Images/favico.ico')){
			echo '<link rel="icon" type="image/x-icon" href="' .$this->paths['resources_uri'].'/Images/favico.ico" />';
		}else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/favico.ico')){
			echo '<link rel="icon" type="image/x-icon" href="' .$_SERVER['DOCUMENT_ROOT'].'/favico.ico" />';
		}
	}

	//////////////////////////////////////////////////

	public function add_app_icons(){
		/**
		 * If a touch icon file “icon-touch.png” is available in the child theme
		 * resources folder, add appropriate LINK tags to the HTML output.
		 */
		if(file_exists($this->paths['resources_path'].'/Images/icon-touch.png')){
			echo '<link rel="apple-touch-icon-precomposed" type="image/png" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="72x72" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="114x114" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="144x144" href="' .$this->paths['resources_uri'].'/Images/icon-touch.png" />';
		}
	}

	//////////////////////////////////////////////////

	public function add_styles(){
		/**
		 * Add appropriate LINK tags to the HTML output for the standard CSS
		 * files in the parent theme resource folders.
		 */
		wp_enqueue_style( 'css-reset', $this->paths['parent_uri'] . '/resources/public/css/css-reset.css', null, $this->version, 'all');
		wp_enqueue_style( 'wp-core', $this->paths['parent_uri'] . '/resources/public/css/core.css', null, $this->version, 'all');
	}

	//////////////////////////////////////////////////
	
	public function add_scripts(){
		wp_enqueue_script( 'jquery', null, null, null, false ); // use jQuery from core
	}

	//////////////////////////////////////////////////

	public function add_meta(){
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />'.
			'<meta charset="' .get_bloginfo('charset'). '" />'.
			'<meta name="viewport" content="width=device-width, initial-scale=1.0" />'.
			'<!--[if lt IE 9]>'.
			'<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>'.
			'<![endif]-->'.
			'<link rel="pingback" href="' .get_bloginfo( 'pingback_url' ). '" />';

		if(file_exists($this->paths['resources_path'].'/Images/facebook.jpg')){
			echo '<meta property="og:image" content="' .$this->paths['resources_uri'].'/Images/facebook.jpg" />';
		}
	}

	//////////////////////////////////////////////////
	
	public function add_og_tags(){
		/**
		 * Add the standard Open Graph tags to the HTML header
		 * If a thumbnail image file facebook.png” is available in the child theme
		 * resources folder, add the appropriate META tag to the HTML output.
		 */
			echo '<meta property="og:locale" content="' .get_locale(). '" />'.
				'<meta property="og:type" content="website"/>'.
				'<meta property="og:url" content="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']. '" />'.
				'<meta property="og:title" content="' .wp_title('–', false, 'right') . get_bloginfo('name') . '" />'.
				'<meta property="og:description" content="' .get_the_excerpt(). '" />';
	}

	//////////////////////////////////////////////////

	public function write_output($function){
	  echo $function();
	}

	//////////////////////////////////////////////////
	
	public function remove_emoji_support(){
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

}