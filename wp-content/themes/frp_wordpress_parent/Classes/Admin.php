<?php
/**
* Functions for WordPress admin area. (“Backend”.)
* These functions will be loaded automatically by the App class.
*
* @since 	11.03.15
*/

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
		/**
		 * Forcibly remove most of the standard dashboard elements
		 */

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
		/**
		 * The contents of the dashboard widget
		 * Referenced by add_dashboard_widgets
		 */
		printf(__('For support, please contact %1s at %2s',$this->app->parentkey),'!frappant Webfactory','<a href="mailto:support@frappant.ch">support@frappant.ch</a>');
	} 

	public function add_dashboard_widgets() {
		/**
		 * Add custom dashboard element/s
		 */
		wp_add_dashboard_widget('default_dashboard_widget', __('Administration section',$this->app->parentkey), array(&$this, 'widget_default') );
	} 

	//////////////////////////////////////////////////

	public function remove_menu_pages($pages = array(), $capability = ''){
		/**
		 * Programattically remove menu items from wp-admin.
		 *
		 * @since 	11.03.15
		 *
		 * @param 	$pages 		array() containing original labels to be removed. e.g. array('Posts', 'Links')
		 * @param 	$capability string 	only remove the $pages if the user doesn't have the appropriate access rights
		 * @return 	null
		 */
		if(count($pages) && $capability!=='' && !current_user_can($capability)){
			foreach($pages as $page){
				remove_menu_page($page);
			}
		}
	}
}
