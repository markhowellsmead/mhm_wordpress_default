<?php

class Theme_Extender {

	//////////////////////////////////////////////////

	function __construct(){
		$this->themedata = wp_get_theme();
		add_filter('body_class',array($this,'add_bodyclasses'));
	}

	//////////////////////////////////////////////////

	public function add_bodyclasses() {
	
		$classes = array();
		
		if(!empty($this->body_classes)){
			foreach($this->body_classes as $arrayValue){
				$classes[] = $arrayValue;
			}
		}

		return $classes;

	}

	//////////////////////////////////////////////////

	function add_themefeatures(){
	
		$this->body_classes = array(urlencode($this->key));

		add_filter('the_content', array($this,'remove_thumbnail_dimensions'), 10 );
		add_filter('post_thumbnail_html', array($this,'remove_thumbnail_dimensions'), 10 );
		add_filter('image_send_to_editor', array($this,'remove_thumbnail_dimensions'), 10 );
		add_filter('embed_oembed_html', array($this,'wrap_oem_video'), 10);

		//add_filter('pre_option_upload_url_path', array($this, 'pre_option_upload_url_path'), 10);
		//add_filter('upload_dir', array($this,'set_common_upload_dir'));
		//add_filter('mce_external_plugins', array($this,'mce_external_plugins'),10);

		$this->add_editor_style();

		add_theme_support('html5', array('search-form'));
	}

	//////////////////////////////////////////////////

	function add_editor_style(){
		if(file_exists($this->paths['template_path'].'css/editor.css')){
			add_editor_style('css/editor.css');
		}
	}	

}