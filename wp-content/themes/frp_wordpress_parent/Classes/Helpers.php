<?php

namespace Frp\WordPress;

class Helpers {

	/**
	* Global non-WordPress helper functions
	*/

	function strftime($format, $timestamp){
		// add ordinal suffix (th, rd etc.) for English languages
		$format = str_replace('%O', date('S', $timestamp), $format);
		return strftime($format, $timestamp);
	}

	//////////////////////////////////////////////////

	function dump($var,$die=false){
		echo '<pre>' .print_r($var,1). '</pre>';
		if($die){die();}
	}//dump

	//////////////////////////////////////////////////

	public function rsort(&$array){
		/**
		 * rsort with SORT_NATURAL is only available in PHP 5.4+
		 */
		if( version_compare(phpversion(), '5.4.0', '>=') ) {
			rsort($array, SORT_NATURAL);
		}else{
			natsort($array);
			$array = array_reverse($array);
		}
	}//rsort

	//////////////////////////////////////////////////

	function add_or_update_meta($postID,$field,$value){
		//	if meta already exists in db for $post then update it, else create it
		//	return true or false
		return update_post_meta($postID, $field, $value) || add_post_meta($postID, $field, $value);
	}//add_or_update_meta

}