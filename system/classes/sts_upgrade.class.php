<?php
/*
	STS Upgrade Class
	Copyright Dalegroup Pty Ltd 2013
	support@dalegroup.net
*/

class sts_upgrade {


	function __construct() {
	
	}
	
		
	//checks if there is a newer version of sts
	function update_check() {
		global $wpdb;
		
		$config		= &sts_singleton::get('sts_config');
		$apptrack	= &sts_singleton::get('sts_apptrack');

		if (current_time('mysql') > $config->get('next_update_check')) {
						
			$send_data['application_id'] 	= 12;
			$send_data['version'] 			= $config->get('version');
			$response 						= $apptrack->send($send_data);
								
			$next_update = $this->now('Y-m-d H:i:s', $config->get('update_check_interval'));
			
			$config->set('next_update_check', $next_update); 
			$config->save();
			
			if (!empty($response)) {	
				$config->set('last_update_response', $response); 
				$config->save();
			}
		}
	}
	
}