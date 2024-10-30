<?php
/*
Plugin Name: Bluetrait Connector Client
Plugin URI: http://wordpress.org/extend/plugins/bluetrait-connector-client/
Description: Connects your WordPress site to Bluetrait.
Version: 1.0
Author: Michael Dale
Author URI: http://bluetrait.com/
*/

/*
Stop people from accessing the file directly and causing errors.
*/
if (!function_exists('add_action')) {
	die('You cannot run this file directly.');
}

/*
Don't break stuff if user tries to use this plugin on anything less than WordPress 2.0.0
*/
if (!function_exists('get_role')) {
	return;
}

/*
	STS Requires PHP 5.3 or greater.
*/
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
	die('Bluetrait Tickets requires PHP 5.3.0 or higher to run.');
}

//define variables
define('STS_SYSTEM', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('STS_CLASSES', STS_SYSTEM . 'classes' . DIRECTORY_SEPARATOR);
define('STS_LIB', STS_SYSTEM . 'lib' . DIRECTORY_SEPARATOR);
define('STS_FUNCTIONS', STS_SYSTEM . 'functions' . DIRECTORY_SEPARATOR);

//include classes
include(STS_CLASSES . 'sts_singleton.class.php');
include(STS_CLASSES . 'sts_config.class.php');
include(STS_CLASSES . 'sts_apptrack.class.php');
include(STS_CLASSES . 'sts_auth.class.php');
include(STS_CLASSES . 'sts_upgrade.class.php');
include(STS_CLASSES . 'sts_wordpress_auth.class.php');
include(STS_CLASSES . 'sts_api_client.class.php');
include(STS_CLASSES . 'sts_admin_html.class.php');
include(STS_CLASSES . 'sts_client_html.class.php');

//this is the file that holds everything together :D
include(STS_CLASSES . 'sts.class.php');

$sts_config	= &sts_singleton::get('sts_config');
$sts_config->set_var('plugin_basename', plugin_basename(__FILE__));
$sts_config->set_var('basename', basename(__FILE__));

$sts = &sts_singleton::get('sts');

//start the plugin here
$sts->load();

?>