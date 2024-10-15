<?php

use Glpi\Application\View\TemplateRenderer;

require '../../../inc/includes.php';
require '../../../src/Search.php';

echo "<script>console.log('PHP: " . "Hi" . "');</script>";

echo "<script>console.log('PHP: " . json_encode(Session::checkValidSessionId()) . "');</script>";

Session::checkValidSessionId();

Session::checkLoginUser();

$start = $_GET['start'];

echo "<script>console.log('PHP: " . json_encode($start) . "');</script>";
echo "<script>console.log('PHP: " . json_encode($_GET) . "');</script>";

echo "<script>console.log('PHP: " . "Hi" . "');</script>";

Html::header(PluginGitlabProfiles_User::getTypeName(), $_SERVER['PHP_SELF'],
             "admin", "plugingitlabintegrationmenu", "profiles");
PluginGitlabProfiles_User::title();
Search::show('PluginGitlabProfiles_User', true);
PluginGitlabProfiles_User::configPage($start);
PluginGitlabProfiles_User::massiveActions($start);
PluginGitlabProfiles_User::configPage($start);

PluginGitlabProfiles_User::dialogActions();

Html::footer();


