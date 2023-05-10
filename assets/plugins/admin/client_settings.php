<?php
if (!defined('MODX_BASE_PATH')) {
	die('HACK???');
}


switch($eventName) {

	case 'OnClientSettingsSave': {
		if(isset($fields['commerce_disabled'])) {
			$modx->db->update(['disabled'=>$fields['commerce_disabled'][1]], $modx->getFullTableName('site_modules'), 'id=4');
			$modx->db->update(['disabled'=>$fields['commerce_disabled'][1]], $modx->getFullTableName('site_plugins'), 'id=11');
			$modx->db->update(['disabled'=>$fields['commerce_disabled'][1]], $modx->getFullTableName('site_snippets'), 'id IN (28,24,25,22,23,27,26)');				
		}


		if(isset($fields['devmode'])) {
			$site_start = $fields['devmode'][1]>0 ? 8 : 1;
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('site_start', '{$site_start}')");
		}

		if(isset($fields['client_company_name'])) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('site_name', '{$fields['client_company_name'][1]}')");
		}

		if(isset($fields['email_to'])) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('emailsender', '{$fields['email_to'][1]}')");
		}

		if(isset($fields['smtp_username']) && !empty($fields['smtp_username'][1])) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('smtp_username', '{$fields['smtp_username'][1]}')");
		}

		if(isset($fields['smtp_host']) && !empty($fields['smtp_host'][1])) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('smtp_host', '{$fields['smtp_host'][1]}')");
		}

		if(isset($fields['smtp_port']) && !empty($fields['smtp_port'][1])) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('smtp_port', '{$fields['smtp_port'][1]}')");
		}

		if(isset($fields['smtppw']) && !empty($fields['smtppw'][1])) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('smtppw', '{$fields['smtppw'][1]}')");
		}

		if(
			isset($fields['smtp_username']) 
			&& !empty($fields['smtp_username'][1]) 
			&& isset($fields['smtp_host']) 
			&& !empty($fields['smtp_host'][1]) 
			&& isset($fields['smtp_port']) 
			&& !empty($fields['smtp_port'][1]) 
			&& isset($fields['smtppw']) 
			&& !empty($fields['smtppw'][1])
		) {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('email_method', 'smtp')");
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('smtp_auth', '1')");
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('smtp_secure', 'ssl')");
		} else {
			$modx->db->query("REPLACE INTO " . $modx->getFullTableName('system_settings') . " (setting_name, setting_value) VALUES ('email_method', 'mail')");
		}


		if(isset($fields['https'])) {
			$htaccess = file_get_contents(MODX_BASE_PATH .'.htaccess');
			if($fields['https'][1]>0) {
				$htaccess = str_replace(
					['#RewriteCond %{HTTP:X-Forwarded-Proto} !=https', '#RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R=301,L]'],
					['RewriteCond %{HTTP:X-Forwarded-Proto} !=https', 'RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R=301,L]'],
					$htaccess
				);
			} else {
				$htaccess = str_replace(
					['RewriteCond %{HTTP:X-Forwarded-Proto} !=https', 'RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R=301,L]'],
					['#RewriteCond %{HTTP:X-Forwarded-Proto} !=https', '#RewriteRule .* https://%{SERVER_NAME}%{REQUEST_URI} [R=301,L]'],
					$htaccess
				);
			}
			file_put_contents(MODX_BASE_PATH .'.htaccess', $htaccess);
		}
	
		break;
	}

	case 'OnBeforeClientSettingsSave': {
		if(isset($fields['smtppw']) && !empty($fields['smtppw'][1])) {
			$v = trim($fields['smtppw'][1]);
			$v = base64_encode($v) . substr(str_shuffle('abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 7);
			$params['fields']['smtppw'][1] = str_replace('=','%',$v);
		}
		break;
	}
}