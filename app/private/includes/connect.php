<?php

ob_start();

if(!defined('BT_IS_ROUTED')) {
	define('BT_IS_ROUTED',false);
}

if(!BT_IS_ROUTED && !defined('IS_AJAX')) {
	define('IS_AJAX',false);
}

ini_set('display_errors',0);

define('BT_ROOT',__DIR__ . '/../../');
define('BT_VERSION','13.05.08');

if(!class_exists('BTApp')) {
	require_once(BT_ROOT . '/private/includes/BTApp.php');
}

//include mysql settings
if(!defined("BYPASS_CONFIG_LOCATION")) {
	//require_once(BT_ROOT . '/bt-config/bt-config.php');
    $file = BT_ROOT . '/bt-config/bt-config.php';
    if(file_exists($file)){
        require_once(BT_ROOT . '/bt-config/bt-config.php');
    }
}else {
	require_once(BT_ROOT . BYPASS_CONFIG_LOCATION);
}
require_once(BT_ROOT . '/private/includes/db.php');
require_once(BT_ROOT . '/private/includes/BTController.php');
require_once(BT_ROOT . '/private/includes/BTUserController.php');
require_once(BT_ROOT . '/private/includes/BTAdminController.php');
require_once(BT_ROOT . '/private/includes/BTModel.php');
require_once(BT_ROOT . '/private/includes/functions.php');
require_once(BT_ROOT . '/private/includes/template.php');
require_once(BT_ROOT . '/private/includes/BTAuth.php');
require_once(BT_ROOT . '/private/includes/traffic/functions-tracking.php');
require_once(BT_ROOT . '/private/includes/browser.php');
require_once(BT_ROOT . '/private/includes/reporting/breakdown.php');
require_once(BT_ROOT . '/private/includes/reporting/stats.php');
require_once(BT_ROOT . '/private/includes/reporting/general.php');
require_once(BT_ROOT . '/private/includes/traffic/filter.php');
require_once(BT_ROOT . '/private/includes/reporting/dayparting.php');
require_once(BT_ROOT . '/private/includes/reporting/weekparting.php');
require_once(BT_ROOT . '/private/includes/navmenu.php');
require_once(BT_ROOT . '/private/libs/FirePHPCore/fb.php');
require_once(BT_ROOT . '/private/includes/BTCache.php');

require_once(BT_ROOT . '/private/includes/BTDialog.php');
require_once(BT_ROOT . '/private/includes/BTHtml.php');
require_once(BT_ROOT . '/private/includes/BTForm.php');
require_once(BT_ROOT . '/private/includes/BTValidator.php');

//Since these are core to the system - just import them now. 
BTApp::importModel('UserModel');
BTApp::importModel('CampaignModel');

function bt_geo_enabled() {
	return true;
}

function bt_mobile_enabled() {
	return true;
}

function bt_cloaker_enabled() {
	return true;
}

if(!defined('HAS_SSL')) {
	define('HAS_SSL',false);
}

if(!defined('LIVE_SITE')) {
	define('LIVE_SITE',false);
}

if(!defined('SHOW_QUERY_LOG')) {
	define('SHOW_QUERY_LOG',false);
}

if(!defined('DEV_LOAD_TEST')) {
	define('DEV_LOAD_TEST',false);
}

define('BT_SYSLOG_MESSAGE',1);
define('BT_SYSLOG_WARNING',2);
define('BT_SYSLOG_ERROR',3);
define('BT_SYSLOG_CRITICAL',4);

define('USER_PRIV_ADMIN',10);
define('USER_PRIV_MANAGER',5);
define('USER_PRIV_NORMAL',1);

define('THEME_CACHE_VER',28);

define('REDIRECT_TYPE_301',31);
define('REDIRECT_TYPE_302',32);
define('REDIRECT_TYPE_307',37);
define('REDIRECT_TYPE_DOUBLE_META',2);
define('REDIRECT_TYPE_JS',10);
define('REDIRECT_TYPE_JSMETA',12);

//DO NOT CHANGE THESE, OR YOU WILL MESS EVERYTHING UP IF YOU NEED TO RECOVER
define('DELETE_BIT_SELF',1);
define('DELETE_BIT_LP',2);
define('DELETE_BIT_TRAFFIC_SOURCE',4);
define('DELETE_BIT_AFF_NETWORK',16);
define('DELETE_BIT_TRACKING_LINK',64);
//END DO NOT CHANGE.

define('AUTH_SESSION_LENGTH',90);

$file = BT_ROOT . '/bt-config/bt-config.php';
if(file_exists($file)){
    DB::connect($dbhost,$dbuser,$dbpass,$dbname);
}

function getBTUrl($vanity = '') {
	return 'http://' . $_SERVER['HTTP_HOST'];
}

function getUserID() {
	if(!BTAuth::user()) {
		return 0;
	}

	return BTAuth::user()->id();
}
