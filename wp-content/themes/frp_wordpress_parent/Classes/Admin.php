<?php
namespace Frp\WordPress;

class Admin {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(App &$app){
		$this->app = $app;

		// Hook into the 'wp_dashboard_setup' action to remove the widgets defined above
		add_action('wp_dashboard_setup', array(&$this, 'remove_dashboard_widgets') );
		add_action('wp_dashboard_setup', array(&$this, 'add_dashboard_widgets') ); 		// For Multisite Network Admin Dashboard use wp_network_dashboard_setup instead of wp_dashboard_setup.
	}

	//////////////////////////////////////////////////

	public function remove_dashboard_widgets() {
		global $wp_meta_boxes;
	
		// side widgets
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		
		//normal widgets
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	}

	//////////////////////////////////////////////////

	public function widget_default() {
		printf(__('For support, please contact %1s at %2s',THEME),'!frappant Webfactory','<a href="mailto:support@frappant.ch">support@frappant.ch</a>');
	} 

	//////////////////////////////////////////////////

	public function add_dashboard_widgets() {
		wp_add_dashboard_widget('default_dashboard_widget', __('Administration section',THEME), array(&$this, 'widget_default') );
	} 

	//////////////////////////////////////////////////

	public function remove_menu_pages($pages = array(), $capability = ''){
		if(count($pages) && $capability!=='' && !current_user_can($capability)){
			foreach($pages as $page){
				remove_menu_page($page);
			}
		}
	}
}
