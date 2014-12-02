<?php

namespace Frp\WordPress;

class Post {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
		if(is_singular() && get_option( 'thread_comments' )){
			wp_enqueue_script( 'comment-reply' );
		}
	}

	function get_post_meta($post_id, $key, $single=true, $filter = false, $fallback_id=0){
		/*
			standard function to extend get_post_meta
			if value on $post_id isn't set or is
			empty, and $fallback_id is not 0, then 
			return the value from the fallback post 
			instead (even if it is empty).
		*/
		$meta_value = get_post_meta($post_id, $key, $single);
		if(empty($meta_value) && $fallback_id!=0){
			$meta_value = get_post_meta($fallback_id, $key, $single);
		}
		if($filter){
			$meta_value = apply_filters('the_content',$meta_value);
		}
		
		return $meta_value;
	}

	//////////////////////////////////////////////////

	protected function get_post_ancestors(){
	
		throw new \Exception('get_post_ancestors is called but not complete');
	
		// add an array to the post of all ancestor IDs (top-down). The first entry is the top-level parent page.
		if($this->viewdata['post']){
			$this->viewdata['post']->ancestors = array_reverse(get_post_ancestors($this->viewdata['post']));
			$this->viewdata['rootpage'] = $this->viewdata['post']->ancestors[0] ? $this->viewdata['post']->ancestors[0] : $this->viewdata['post']->ID;
		}
	}

	//////////////////////////////////////////////////

	function is_in_rootline($pid=0){
		if(!intval($pid)){
			return false;
		}
		global $post;
		$anc = get_post_ancestors($post->ID);
		foreach($anc as $ancestor){
			if(is_page() && $ancestor == $pid){
				return true;
			}
		}
		if(is_page()&&(is_page($pid))){
			return true;   // we're at the page or at a sub page
		}else{
			return false;  // we're elsewhere
		}
	}

	//////////////////////////////////////////////////

	public function get_post_slug($id=-1){
		// get the slug of the specified (not current) post
	    $post_data = get_post($id);
		return $post_data->post_name; 
	}

	//////////////////////////////////////////////////

	protected function inherit($post, $key, $final_fallback_id=0){
		// Get meta value from current post
		// If empty, check parent
		// If empty, check grandparent
		// If empty, check post with ID $godID
		// apply_filters is not yet implemented
		// Return string

		$val = $this->get_post_meta($post->ID, $key, true, false, $post->post_parent);
		
		if($val==''){
			$parent = get_post($post->post_parent);
			$val = $this->get_post_meta($parent->ID, $key, true, false, $parent->post_parent);

			if($val=='' && $final_fallback_id){
				$final_fallback_post = get_post($final_fallback_id);
				$val = $this->get_post_meta($final_fallback_post->ID, $key, true, false);
			}
		}
		return $val;
	}

	//////////////////////////////////////////////////
	
	public function pageTitle($atts){
		global $post;
		
		if(!is_array($atts)){
			$atts=array($atts);
		}

		$pagetitle_regular = ($title_alt = get_post_meta($post->ID,"title_alt",true))!=="" ? $title_alt : get_the_title($post->ID);
		
		if(!$atts["showparent"] || $atts["showparent"]==false){

			return '<h3>'.$pagetitle_regular.'</h3>';

		}else{

			switch(count($post->ancestors)){
				
				case 0:
					$out='<h3>'.$pagetitle_regular.'</h3>';
					break;
				
				case 1:
					$pagetitle_parent = get_the_title($post->post_parent);
					return '<h4>'.$pagetitle_parent.'</h4>'
						.'<h3>'.$pagetitle_regular.'</h3>';
					break;
				
				default;
					$pagetitle_parent=get_the_title($post->post_parent);	//	always use menu title

					$page_grandparent=get_post($post->post_parent);

					$pagetitle_grandparent=get_the_title($page_grandparent->post_parent);	//	always use menu title

					return '<h4>'.($pagetitle_grandparent!=''?$pagetitle_grandparent.' » ':'').$pagetitle_parent.'</h4>'
						.'<h3>'.$pagetitle_regular.'</h3>';
						
					break;
			}
		}
	}

}