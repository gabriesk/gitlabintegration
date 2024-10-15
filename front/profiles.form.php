<?php

require '../../../inc/includes.php';

Session::checkLoginUser();

Html::header(PluginGitlabProfiles_User::getTypeName(), $_SERVER['PHP_SELF'],
             "admin", "plugingitlabintegrationmenu", "profiles");

(new PluginGitlabProfiles_User)->showForm(0);

Html::footer();