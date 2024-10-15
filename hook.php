<?php

function plugin_gitlabintegration_install() {
	
	global $DB;

	$config = new Config();
   	$config->setConfigurationValues('plugin:Gitlab Integration', ['configuration' => false]);

   	ProfileRight::addProfileRights(['gitlabintegration:read']);
	
	//instanciate migration with version
	$migration = new Migration(null);

	// //Create table glpi_plugin_gitlab_integration only if it does not exists yet!
	plugin_gitlabintegration_create_integration($DB);

	//Create table glpi_plugin_gitlab_integration only if it does not exists yet!
	plugin_gitlabintegration_create_profiles($DB);

	//Create table glpi_plugin_gitlab_parameters only if it does not exists yet!
	plugin_gitlabintegration_create_parameters($DB);

	//Insert parameters at table glpi_plugin_gitlab_parameters only if it exist!
	plugin_gitlabintegration_insert_parameters($DB);

	plugin_gitlabintegration_create_constraints($DB);
 
	return true;
}


function plugin_gitlabintegration_uninstall() {

	global $DB;
	
	$config = new Config();
	$config->deleteConfigurationValues('plugin:Gitlab Integration', ['configuration' => false]);

	ProfileRight::deleteProfileRights(['gitlabintegration:read']);

	$notif = new Notification();
	$options = ['itemtype' => 'Ticket',
				'event'    => 'plugin_gitlabintegration',
				'FIELDS'   => 'id'];
	foreach ($DB->request('glpi_notifications', $options) as $data) {
		$notif->delete($data);
	}

	//Drop table glpi_plugin_gitlab_integration only if it exists!
	plugin_gitlabintegration_delete_integration($DB);
	
	//Drop table glpi_plugin_gitlab_profiles_users only if it exists!
	plugin_gitlabintegration_delete_profiles($DB);

	//Drop table glpi_plugin_gitlab_parameters only if it exists!
	plugin_gitlabintegration_delete_parameters($DB);
	
	return true;
}

function plugin_gitlabintegration_create_integration($DB) {
	if (!$DB->tableExists('glpi_plugin_gitlab_integration')) {
	    $query = "CREATE TABLE `glpi_plugin_gitlab_integration` (
				   `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				   `ticket_id` INT(11) UNSIGNED NOT NULL,
				   `gitlab_project_id` INT(11) UNSIGNED NOT NULL,
				   PRIMARY KEY  (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	    $DB->queryOrDie($query, $DB->error());
	}
}

function plugin_gitlabintegration_create_profiles($DB) {
	if (!$DB->tableExists('glpi_plugin_gitlab_profiles_users')) {
	    $query = "CREATE TABLE `glpi_plugin_gitlab_profiles_users` (
				   `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				   `profile_id` INT(11) UNSIGNED NOT NULL,
				   `user_id` INT(11) UNSIGNED NOT NULL,
				   `created_at` DATETIME,
				   PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC";
	    $DB->queryOrDie($query, $DB->error());
	}
}

function plugin_gitlabintegration_create_parameters($DB) {
	if (!$DB->tableExists('glpi_plugin_gitlab_parameters')) {
	    $query = "CREATE TABLE `glpi_plugin_gitlab_parameters` (
				   `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				   `name` VARCHAR(50) NOT NULL,
				   `value` VARCHAR(125),
				   PRIMARY KEY  (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
	    $DB->queryOrDie($query, $DB->error());
	}
}

function plugin_gitlabintegration_create_constraints($DB){
	if ($DB->tableExists('glpi_plugin_gitlab_profiles_users')) {
		$query = "ALTER TABLE glpi_plugin_gitlab_profiles_users
						ADD CONSTRAINT fk_gitlab_profile
						FOREIGN KEY (profile_id) REFERENCES glpi_profiles (id)";
		$DB->queryOrDie($query, $DB->error());

		$query = "ALTER TABLE glpi_plugin_gitlab_profiles_users
						ADD CONSTRAINT fk_gitlab_user
						FOREIGN KEY (user_id) REFERENCES glpi_users (id)";
		$DB->queryOrDie($query, $DB->error());
	}

	if ($DB->tableExists('glpi_plugin_gitlab_parameters')) {
		$query = "ALTER TABLE glpi_plugin_gitlab_parameters
						ADD CONSTRAINT uk_name 
						UNIQUE (name) ";
		$DB->queryOrDie($query, $DB->error());
	}

	if ($DB->tableExists('glpi_plugin_gitlab_integration')) {
		$query = "ALTER TABLE glpi_plugin_gitlab_integration
	                ADD CONSTRAINT fk_gitlab_ticket
					FOREIGN KEY (ticket_id) REFERENCES glpi_tickets (id)";
		$DB->queryOrDie($query, $DB->error());
	}

}
function plugin_gitlabintegration_delete_integration($DB) {
	if ($DB->tableExists('glpi_plugin_gitlab_integration')) {
		$drop_count = "DROP TABLE glpi_plugin_gitlab_integration";
		$DB->query($drop_count); 
	}
}

function plugin_gitlabintegration_delete_profiles($DB) {
	if ($DB->tableExists('glpi_plugin_gitlab_profiles_users')) {
		$drop_count = "DROP TABLE glpi_plugin_gitlab_profiles_users";
		$DB->query($drop_count);
	} 
}

function plugin_gitlabintegration_delete_parameters($DB) {
	if ($DB->tableExists('glpi_plugin_gitlab_parameters')) {
		$drop_count = "DROP TABLE glpi_plugin_gitlab_parameters";
		$DB->query($drop_count);
	} 
}

function plugin_gitlabintegration_insert_parameters($DB) {
	if ($DB->tableExists('glpi_plugin_gitlab_parameters')) {

		$ini_array = parse_ini_file("gitlabintegration.ini");

		$parameters = [[
			'name'  => 'gitlab_url',
			'value' => $ini_array['GITLAB_URL'] == "" ? NULL : $ini_array['GITLAB_URL']
		],
		[
			'name'  => 'gitlab_token',
			'value' => $ini_array['GITLAB_TOKEN'] == "" ? NULL : $ini_array['GITLAB_TOKEN']
		]];
		
		foreach ($parameters as $parameter) {
			$DB->insert(
				'glpi_plugin_gitlab_parameters', [
					'name'  => $parameter['name'],
					'value' => $parameter['value']
				]
			);
		}
	}
}

?>
