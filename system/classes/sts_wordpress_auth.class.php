<?php
/*
	STS WordPress Auth Class
	Copyright Dalegroup Pty Ltd 2013
	support@dalegroup.net
*/

class sts_wordpress_auth {


	public function process_request() {
		//send headers for JSON
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-Type: application/json; charset=utf-8');

		$config		= &sts_singleton::get('sts_config');
		$sts_auth	= &sts_singleton::get('sts_auth');
		
		$sts_site_id 		= $config->get('auth_site_id');
		$sts_security_key 	= $config->get('auth_key');

		$sts_send_array['success'] = 0;

		if (isset($_POST['site_id']) && isset($_POST['data'])) {
			if ($_POST['site_id'] == $sts_site_id) {
				
				/*
					Set your authentication key here
				*/
				$sts_auth->set('key', $sts_security_key);
				
				$sts_data = $sts_auth->decrypt($_POST['data']);
				$sts_receive_array = json_decode($sts_data, true);
				if (is_array($sts_receive_array)) {
					if ($sts_receive_array['task'] == 'authenticate') {
						
						/*
							This is where the WordPress auth function exists. (WordPress sucks!! Why do I need to addslashes() !?)
						*/
						$sts_wp_result = wp_authenticate(addslashes($sts_receive_array['username']), addslashes($sts_receive_array['password']));

						//if the login was a success we can continue
						if (isset($sts_wp_result->ID) && (int) $sts_wp_result->ID != 0) {

							$sts_send_array['success'] 	= 1;
							$sts_send_array['name'] 	= $sts_wp_result->data->user_nicename;
							$sts_send_array['email'] 	= $sts_bb_result->data->user_email;		

							if (function_exists('btev_trigger_error')) {
								btev_trigger_error('Login Successful "' . $sts_receive_array['username'] . '"', E_USER_NOTICE, __FILE__, __LINE__);
							}
						}
						echo $sts_auth->encrypt(json_encode($sts_send_array));	
					}
				}
			}
		}
		
		exit;
	}

}

?>