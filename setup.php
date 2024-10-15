<?php

//use Glpi\Plugin\Hooks;

define('PLUGIN_GITLAB_VERSION', '0.0.4');

// Minimal GLPI version, inclusive
define("PLUGIN_GITLAB_MIN_GLPI", "10.0.0");
// Maximum GLPI version, exclusive
define("PLUGIN_GITLAB_MAX_GLPI", "10.0.99");

function plugin_init_gitlabintegration() {
    global $PLUGIN_HOOKS, $CFG_GLPI;

    // CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
    $PLUGIN_HOOKS['csrf_compliant']['gitlabintegration'] = true;

    $plugin = new Plugin();
    if ($plugin->isInstalled('gitlabintegration') && $plugin->isActivated('gitlabintegration')) {
        $PLUGIN_HOOKS['menu_toadd']['gitlabintegration']['admin'] = [
            'admin' => 'PluginGitlabIntegrationMenu',
        ];

        include_once(GLPI_ROOT . '/plugins/gitlabintegration/inc/itemform.class.php');
        include_once(GLPI_ROOT . '/plugins/gitlabintegration/inc/eventlog.class.php');
        include_once(GLPI_ROOT . '/plugins/gitlabintegration/inc/parameters.class.php');
        include_once(GLPI_ROOT . '/plugins/gitlabintegration/inc/gitlabintegration.class.php');
        include_once(GLPI_ROOT . '/plugins/gitlabintegration/inc/menu.class.php');
        include_once(GLPI_ROOT . '/plugins/gitlabintegration/inc/profiles.class.php');

        $PLUGIN_HOOKS['add_css']['gitlabintegration'] = "css/styles.css";
        $PLUGIN_HOOKS['add_javascript']['gitlabintegration'][] = "js/buttonsFunctions.js";
       
        if (class_exists('PluginGitlabIntegrationItemForm')) {
            $PLUGIN_HOOKS['post_item_form']['gitlabintegration'] = ['PluginGitlabIntegrationItemForm', 'postItemForm'];
        }


    }
}

function plugin_version_gitlabintegration() {
	return [
	   'name'           => t_gitlabintegration('Gitlab Integration'),
	   'version'        => '0.0.4',
	   'author'         => 'Fáiza Letícia Schoeninger e Gabriel dos Passos',
	   'homepage'       => 'https://github.com/gabriesk/gitlabintegration/',
	   'license'        => 'GPLv3+',
       'minGlpiVersion' => PLUGIN_GITLAB_MIN_GLPI,
	   'requirements'   => [
		  'glpi' => [
			 'min' => PLUGIN_GITLAB_MIN_GLPI,
			 'max' => PLUGIN_GITLAB_MAX_GLPI,
		  ]
	   ]
	];
 }


function plugin_gitlabintegration_check_prerequisites() {
	if (version_compare(GLPI_VERSION, PLUGIN_GITLAB_MIN_GLPI, 'lt')){
		echo 'O plugin é requer GLPI >= ' . PLUGIN_GITLAB_MIN_GLPI;
		return false;
	} else {
        return true;
    }
}


function plugin_gitlabintegration_check_config() {
    return true;
}

function t_gitlabintegration($str) {
	return __($str, 'gitlabintegration');
}