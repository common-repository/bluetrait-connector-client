<?php
/**
 * 	JSON Authentication Class
 *	Copyright Dalegroup Pty Ltd 2013
 *	support@dalegroup.net
 *
 *
 * @package     dgx
 * @author      Michael Dale <mdale@dalegroup.net>
 */

class sts_auth {
	
	private $config = array();
	private $user	= array();

	function __construct() {	
		$this->config['key']				= '';
	}
	
	public function set($name, $value) {
		$this->config[$name] = $value;
	}
	
	public function encrypt($string) {
		$iv = mcrypt_create_iv(
				mcrypt_get_iv_size(
					MCRYPT_RIJNDAEL_256, 
					MCRYPT_MODE_CBC
				), 
				MCRYPT_RAND
			);
		
		$encrypted_data = 
			mcrypt_encrypt(
				MCRYPT_RIJNDAEL_256, 
				md5($this->config['key']), 
				$string, 
				MCRYPT_MODE_CBC, 
				$iv
			);
			
		$encrypted = base64_encode($iv . $encrypted_data);	
		
		return $encrypted;
	}
	
	public function decrypt($string) {
		$string = base64_decode($string);
	
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
		$iv = substr($string, 0, $iv_size);

		//retrieves the cipher text (everything except the $iv_size in the front)
		$string = substr($string, $iv_size);
	
		$decrypted = 
		rtrim(
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_256,
				md5($this->config['key']), 
				$string,
				MCRYPT_MODE_CBC,
				$iv
			),
			"\0"
		);	
		
		return $decrypted; 
	}


}

?>