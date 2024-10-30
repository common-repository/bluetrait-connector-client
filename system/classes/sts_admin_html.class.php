<?php

class sts_admin_html {

	//html for event viewer settings
	function subpanel_settings() {
		$sts		= &sts_singleton::get('sts');
		$config		= &sts_singleton::get('sts_config');

		
		if (isset($_POST['submit'])) { 
			$config->set('auth_enabled', $_POST['sts_auth_enabled'] ? 1 : 0);
			$config->set('auth_site_id', (int) $_POST['sts_auth_site_id']);
			$config->set('auth_key', $_POST['sts_auth_key']);

			/*
				$config->set('api_enabled', $_POST['sts_api_enabled'] ? 1 : 0);
				$config->set('api_url', $_POST['sts_api_url']);
				$config->set('api_key', $_POST['sts_api_key']);
			*/

			$config->save();
			
			if (function_exists('btev_trigger_error')) {
				btev_trigger_error('Bluetrait Connector Settings Updated.', E_USER_NOTICE, __FILE__, __LINE__);
			}	
		} ?>
		<div class="wrap">
			<h2>Bluetrait Connector Settings</h2>
			
			<form action="<?php echo $sts->subpanel_settings_link(); ?>" method="post">
				<table class="form-table">
				
					<h3>Authentication Server</h3>
					<tr valign="top">
						<th scope="row">Enabled</th>
						<td>
							<select name="sts_auth_enabled">
								<option value="0">No</option>
								<option value="1" <?php if ($config->get('auth_enabled') == 1) { echo 'selected="selected"'; } ?>>Yes</option>
							</select>
							<br />
							Allows Bluetrait to use this WordPress install for user authentication.
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Site ID</th>
						<td>
							<input name="sts_auth_site_id" type="text" size="10" value="<?php echo esc_html($config->get('auth_site_id')); ?>" />
							<br />
							
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Security Key</th>
						<td>
							<input name="sts_auth_key" type="text" size="30" value="<?php echo esc_html($config->get('auth_key')); ?>" />
							<br />
						</td>
					</tr>
							
					<tr valign="top">
						<th scope="row">URL</th>
						<td>
							<?php
								$sts_auth_url = get_option('siteurl') . '/?sts_auth';
							?>
							<input name="sts_auth_url" type="text" size="100" value="<?php echo esc_html($sts_auth_url); ?>" disabled="disabled" />
							<br />
							Editing Disabled
						</td>
					</tr>
								
				</table>
				<!--
				<br />
				<table class="form-table">
			
					<h3>API Client</h3>
					<tr valign="top">
						<th scope="row">Enabled</th>
						<td>
							<select name="sts_api_enabled">
								<option value="0">No</option>
								<option value="1" <?php if ($config->get('api_enabled') == 1) { echo 'selected="selected"'; } ?>>Yes</option>
							</select>
							<br />
							
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Bluetrait Sub Domain</th>
						<td>
							<input name="sts_api_url" type="text" size="30" value="<?php echo esc_html($config->get('api_url')); ?>" />.bluetrait.com
							<br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">API Key</th>
						<td>
							<input name="sts_api_key" type="text" size="30" value="<?php echo esc_html($config->get('api_key')); ?>" />
							<br />
						</td>
					</tr>
				</table>
				-->
				<p><input type="submit" name="submit" value="Submit" class="button-primary" /></p>
			</form>
			<br />
			<div id="sts_uninstall">
				<script type="text/javascript">
				<!--
				function sts_uninstall() {
					if (confirm("Are you sure you wish to uninstall Bluetrait Connector?")){
						return true;
					}
					else{
						return false;
					}
				}
				//-->
				</script>
				<form action="<?php echo $sts->subpanel_settings_link(); ?>" method="post" onsubmit="return sts_uninstall(this);">
				<?php
					if (function_exists('wp_nonce_field')) {
						wp_nonce_field('sts-uninstall');
					}
					?>
					<p class="submit"><input type="submit" name="sts_submit_uninstall" value="Uninstall" class="button" /></p>
				</form>
			</div>
		</div>
		<?php
	}
	

}

?>