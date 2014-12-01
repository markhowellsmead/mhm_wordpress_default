<?php

namespace Frp\WordPress;

class Core {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(App &$app){
		$this->app = $app;
		$this->get_blog_info();
	}

	//////////////////////////////////////////////////

	public function set_paths(){
		$this->app->paths['template_path']		= get_template_directory();
		$this->app->paths['template_uri']		= get_stylesheet_directory_uri();
		$this->app->paths['configuration_path']	= $this->app->paths['template_path'].'/Configuration';
		$this->app->paths['resources_path']		= $this->app->paths['template_path'].'/Resources/Public';
		$this->app->paths['resources_uri']		= $this->app->paths['template_uri'].'/Resources/Public';
	}

	//////////////////////////////////////////////////

	public function set_keys(){
		// keys are used for e.g. translations. The default key is the name of the child theme folder.
		$this->app->key = basename($this->app->paths['template_path']);
	}

	//////////////////////////////////////////////////

	public function load_theme_options(){
		// not ready yet
		/*if(file_exists($this->app->paths['configuration_path'].'/themeoptions.inc.php')){
			require_once($this->app->paths['configuration_path'].'/themeoptions.inc.php');
			$this->app->themeoptions = get_option('themeoptions_'.$this->app->key);
			$this->app->themeoptions['email'] = antispambot($this->app->themeoptions['email']);
		}*/
	}

	/////////////////////////////////////////////
	
	public function load_configuration(){
		// load configuration files for e.g. advanced custom fields
		if( is_dir($this->app->paths['template_path'].'/Configuration') ){
			$this->app->extend('filesystem');
			$this->app->configuration = $this->app->getFiles($this->app->paths['template_path'].'/Configuration');
			foreach($this->app->configuration as $file){
				include_once($file);
			}
		}
	}

	/////////////////////////////////////////////
	
	public function add_style($file='', $media='all', $filekey=''){
		if(file_exists($this->app->paths['resources_path'].$file)){
			if(empty($filekey)){
				$filekey = pathinfo($file, PATHINFO_FILENAME);
			}
			if($filekey=='stylesheet'){
				trigger_error('Cannot add a custom CSS file with the name “stylesheet”.', E_USER_WARNING);
			}else{
				$key = $this->app->key.'-'.$filekey;
				wp_register_style($key, $this->app->paths['resources_uri'].$file, null, $this->app->version, $media);
				wp_enqueue_style($key);
			}
		}
	}

	//////////////////////////////////////////////////

	public function add_favicon(){
		if(file_exists($this->app->paths['resources_path'].'/Images/favico.ico')){
			echo '<link rel="icon" type="image/x-icon" href="' .$this->app->paths['resources_uri'].'/Images/favico.ico" />';
		}else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/favico.ico')){
			echo '<link rel="icon" type="image/x-icon" href="' .$_SERVER['DOCUMENT_ROOT'].'/favico.ico" />';
		}
	}

	//////////////////////////////////////////////////

	public function add_app_icons(){
		if(file_exists($this->app->paths['resources_path'].'/Images/icon-touch.png')){
			echo '<link rel="apple-touch-icon-precomposed" type="image/png" href="' .$this->app->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="72x72" href="' .$this->app->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="114x114" href="' .$this->app->paths['resources_uri'].'/Images/icon-touch.png" />'.PHP_EOL.
			'<link rel="apple-touch-icon-precomposed" type="image/png" sizes="144x144" href="' .$this->app->paths['resources_uri'].'/Images/icon-touch.png" />';
		}
	}

	//////////////////////////////////////////////////

	public function add_meta(){

		echo '<meta property="og:type" content="blog" />
			<meta property="og:url" content="http:// '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']. '" />
			<meta property="og:title" content="' .$this->app->blog_info['name'].'" />
			<meta property="og:description" content="" />';
			
		if(file_exists($this->app->paths['resources_path'].'/Images/facebook.jpg')){
			echo '<meta property="og:image" content="' .$this->app->paths['resources_uri'].'/Images/facebook.jpg" />';
		}
	}

	/////////////////////////////////////////////
	
	public static function clean_menu($html){
		return preg_replace('/title=\"(.*?)\"/','',$html);
	}

	//////////////////////////////////////////////////

	public function get_blog_info(){
	
		// the array can be extended as necessary
		// a query for all options returns A LOT of data, most of which isn't required
		// see /wp-admin/options.php for an example
	
		$this->app->blog_info = array(
			'name' => get_bloginfo('name'),
			'description' => get_bloginfo('name')
		);
	}

}