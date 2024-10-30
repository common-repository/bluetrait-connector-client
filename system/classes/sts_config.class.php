<?php
/*
	STS Config Class
	Copyright Dalegroup Pty Ltd 2013
	support@dalegroup.net
*/

class sts_config {

	private $vars 		= array();
	private $config 	= array();

	function __construct() {
		//global $wpdb;
	
		$this->vars['version']			= '1.0';
		$this->vars['db_version']		= 1;
		//$this->vars['tables']['events']	= $wpdb->prefix . 'btev_events';
		//$this->vars['tables']['users']	= $wpdb->prefix . 'users';

	}
	
	public function load() {
		$this->config = get_option('sts_config');
	}
	
	public function set_var($name, $value) {
		$this->vars[$name] = $value;
	}
	
	public function get_var($name) {
		if (isset($this->vars[$name])) {
			return $this->vars[$name];
		}
		return false;	
	}
	
	public function get_table($name) {
		if (isset($this->vars['tables'][$name])) {
			return $this->vars['tables'][$name];
		}
		return false;
	}
	
	//returns a config value from the site array
	public function get($config_name, $unserialize = true) {
		
		$site = $this->config;
		
		if (!empty($site)) {
			if (array_key_exists($config_name, $site)) {
				if ($unserialize == true) {
					$str = 's';
					$array = 'a';
					$integer = 'i';
					$any = '[^}]*?';
					$count = '\d+';
					$content = '"(?:\\\";|.)*?";';
					$open_tag = '\{';
					$close_tag = '\}';
					$parameter = "($str|$array|$integer|$any):($count)" . "(?:[:]($open_tag|$content)|[;])";           
					$preg = "/$parameter|($close_tag)/";
					if(!preg_match_all($preg, $site[$config_name], $matches)) {           
						return $site[$config_name];
					}
					else {
						return unserialize($site[$config_name]);
					}
				}
				else {
					return $site[$config_name];
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	//sets a config value into the array
	public function set($config_name, $config_value, $update_now = FALSE) {
			
		if (is_array($config_value)) {
			$this->config[$config_name] = serialize($config_value);
		}
		else {
			$this->config[$config_name] = $config_value;
		}
		
		if ($update_now) {
			$this->save_config();
		}
	}

	//saves the site array back to the database
	public function save() {				
		update_option('sts_config', $this->config);	
	}
	
	//populates the site table with info
	public function create() {
		
		$site = array();
		//version 1.0
		$site['installed'] 				= current_time('mysql');
		$site['next_update_check'] 		= '';
		$site['last_update_response'] 	= '';
		$site['version'] 				= $this->get_var('version');
		$site['update_check_interval'] 	= 86400;
		$site['db_version']				= $this->get_var('db_version');
		
		$site['api_enabled']			= 0;
		$site['api_url']				= '';
		$site['api_key']				= '';

		$site['auth_enabled']			= 0;		
		$site['auth_site_id']			= 1;
		$site['auth_key']				= '';

		
		
		add_option('sts_config', $site, 'Bluetrait Connector Config');
	}
}