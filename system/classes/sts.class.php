<?php
/*
	STS Class
	Copyright Dalegroup Pty Ltd 2013
	support@dalegroup.net
*/

class sts {


	function __construct() {
		//global $wpdb;
	
	}

		
	public function load() {
		$config		= &sts_singleton::get('sts_config');

		$config->load();
		
		$db_version = $config->get('db_version');
		
		if ($db_version == false) {
			$this->install();
		}
		
		$this->load_hooks();
	}
	
	private function load_hooks() {
		
		//menu actions
		add_action('admin_menu', array($this, 'admin_menu_settings'));
		
		//load STS
		add_action('init', array($this, 'add_admin_cap'));
		add_action('init', array($this, 'submit_uninstall'));
		add_action('init', array($this, 'request_handle'), 100);

	}
	
	
	//install database tables
	private function install() {
		$config		= &sts_singleton::get('sts_config');
	
		$config->create();
		$config->load();
		
		if (function_exists('btev_trigger_error')) {
			btev_trigger_error('Bluetrait Connector ' . $config->get_var('version') . ' Plugin Has Been Successfully Installed.', E_USER_NOTICE, __FILE__, __LINE__);
		}		
	}
		
	
	//this function does the uninstalling
	function uninstall() {
		global $wpdb;

		$config		= &sts_singleton::get('sts_config');
		
		/*
		Deactivate Plugin
		*/
		$current = get_option('active_plugins');
		array_splice($current, array_search($config->get_var('plugin_basename'), $current), 1 ); // Array-fu!
		update_option('active_plugins', $current);

		/*
		Delete Options from WordPress Table
		*/
		delete_option('sts_config');
		
		/*
		Redirect To Plugin Page
		*/
		wp_redirect('plugins.php?deactivate=true');
	
	}
		
	
	//returns the users ip address
	function ip_address() {
		return $_SERVER['REMOTE_ADDR'];
	}

	
	//basic date function.
	public function now($format = 'Y-m-d H:i:s', $add_seconds = 0) {

		$base_time = time() + $add_seconds + 3600 * get_option('gmt_offset');
		
		switch($format) {
		
			case 'Y-m-d H:i:s':
				return gmdate('Y-m-d H:i:s', $base_time);
			break;
			
			case 'H:i:s':
				return gmdate('H:i:s', $base_time);
			break;
			
			case 'Y-m-d':
				return gmdate('Y-m-d', $base_time);
			break;
			
			case 'Y':
				return gmdate('Y', $base_time);
			break;
			
			case 'm':
				return gmdate('m', $base_time);
			break;
			
			case 'd':
				return gmdate('d', $base_time);
			break;
		
		}
	}
		
	//this function checks that the user is really trying to uninstall and if they have permission to (if so uninstall)
	function submit_uninstall() {
		if (isset($_POST['sts_submit_uninstall'])) {
			if (current_user_can('sts')) {
				if (function_exists('check_admin_referer')) {
					check_admin_referer('sts-uninstall');
				}
				$this->uninstall();
			}
			else {
				if (function_exists('btev_trigger_error')) {
					btev_trigger_error("Unauthorised Uninstall Attempt of Bluetrait Connector.", E_USER_WARNING, __FILE__, __LINE__);
				}
			}
		}
	}

	//get the current id of user logged in
	function user_id() {
		if (function_exists('wp_get_current_user')) {
			$user_object = wp_get_current_user();
			return $user_object->ID;
		}
		else {
			return 0;
		}
	}

	//add a role so that we can check if the user can "do stuff" (TM)
	function add_admin_cap() {
		$role = get_role('administrator');
		$role->add_cap('sts');
	}


	//adds the event viewer settings to the options submenu
	function admin_menu_settings() {
		$config		= &sts_singleton::get('sts_config');
		$sts_admin_html		= &sts_singleton::get('sts_admin_html');

		if (function_exists('add_options_page')) {
			add_options_page('Bluetrait', 'Bluetrait', 8, 'sts_settings', array($sts_admin_html, 'subpanel_settings'));
		}
	}

	//link to event viewer settings
	function subpanel_settings_link() {
		return 'options-general.php?page=sts_settings';
	}
	
	//hooks into Wordpress so that we can output "custom content"
	function request_handle() {
		$config		= &sts_singleton::get('sts_config');

		if (isset($_GET['sts_auth'])) {
			if ($config->get('auth_enabled')) {
				$sts_wordpress_auth		= &sts_singleton::get('sts_wordpress_auth');
				$sts_wordpress_auth->process_request();
			}
		}
	}


}