<?php
/*
Plugin Name: 	One Page Template
Plugin URI: 	#
Description: 	Configuration for necessary files etc. 
Author: 			Mark Howells-Mead
Version: 			1.0
Author URI: 	http://www.frappant.ch/
*/

class Frp_Template_Onepage {
	
	var $key = 'frp_template_onepage',
			$version = '1.0';

	//////////////////////////////////////////////////

	public function __construct(){

		add_action( 'wp_enqueue_scripts', array(&$this, 'add_styles') );
		add_action( 'wp_enqueue_scripts', array(&$this, 'add_scripts') );

	}

	//////////////////////////////////////////////////
	
	public function add_styles(){

		wp_enqueue_style( $this->key.'-basic', plugins_url( $this->key.'/resources/public/css/basic.css' , dirname(__FILE__) ), array('wp-core'), $this->version);

		wp_enqueue_style( $this->key.'-modules', plugins_url( $this->key.'/resources/public/css/modules.css' , dirname(__FILE__) ), array($this->key.'-basic'), $this->version, '(min-width:0)');
		wp_enqueue_style( $this->key.'-typography', plugins_url( $this->key.'/resources/public/css/typography.css' , dirname(__FILE__) ), array('wp-core'), $this->version, '(min-width:0)');
		wp_enqueue_style( $this->key.'-print', plugins_url( $this->key.'/resources/public/css/print.css' , dirname(__FILE__) ), array('wp-core'), $this->version, 'print');

	}

	//////////////////////////////////////////////////

	public function add_scripts(){

		wp_enqueue_script($this->key.'-anchoranimate', plugins_url( $this->key.'/resources/public/javascript/jquery.anchoranimate.min.js' , dirname(__FILE__) ), array('jquery'), $this->version, true);
		wp_enqueue_script($this->key.'-ui', plugins_url( $this->key.'/resources/public/javascript/ui.min.js' , dirname(__FILE__) ), array('jquery', $this->key.'-anchoranimate'), $this->version, true);
		
		wp_register_script($this->key.'-nth-child-ie8', plugins_url( $this->key.'/resources/public/javascript/nth-child-ie8.min.js' , dirname(__FILE__) ), array('jquery', $this->key.'-anchoranimate'), $this->version, true);
    wp_enqueue_script($this->key.'-nth-child-ie8');
		wp_script_add_data($this->key.'-nth-child-ie8', 'conditional', 'lt IE 9' );

	}

}

new Frp_Template_Onepage();