<?php

require_once(__DIR__ . '/connect.php');
require_once(BT_ROOT . '/private/includes/BTLogger.php');

class BTApp {
	protected static $_loadPath = '';
	protected static $_controller = null;
	protected static $_action = '';
	protected static $_logger = null;
	
	public static function init() {
		// remove magic_quotes
		if(get_magic_quotes_gpc()) {
			function undo_magic_quotes_array($array) {
				$arr = array();
				
				foreach($array as $ind=>$val) {
					if(!is_array($val) && !is_object($val)) {
						$val = stripslashes($val);
					}
					
					$arr[$ind] = $val;
				}
				return $arr;
			}
			
			$_GET = undo_magic_quotes_array($_GET);
			$_POST = undo_magic_quotes_array($_POST);
			$_COOKIE = undo_magic_quotes_array($_COOKIE);
		}
		
		function bt_trim_whitepsace(&$var) {			
			$var = trim($var);
		}
		
		array_walk_recursive($_GET,'bt_trim_whitepsace');
		array_walk_recursive($_POST,'bt_trim_whitepsace');
				
		self::$_logger = new BTLogger('syslog');
	}
	
	public static function log($message,$type,$level,$extra = array()) {
		self::$_logger->write($message);
		
		$extra['get']  = $_GET;
		$extra['post'] = $_POST;
		$extra['server'] =  $_SERVER;
		$extra['cookie'] = $_COOKIE;

		$extra = json_encode($extra);

		DB::query("insert into bt_g_syslog (type,date,level,message,extra) values ('" . DB::quote($type) . "','" . date('Y-m-d H:i:s') . "','" . DB::quote($level) . "','" . DB::quote($message) . "','" . DB::quote($extra) . "')");

		if($level == BT_SYSLOG_CRITICAL) {
			die();
		}
	}
	
	/**
	 * Log to FirePHP Library
	 * 
	 * @param string $message
	 */
	public static function firelog($message) {
		if(!LIVE_SITE) {
			FB::log($message);
		}
	}
	
	public static function requestPath() {
		return self::$_loadPath;
	}
	
	public static function requestAction() {
		return self::$_action;
	}
	
	public static function controller() {
		return self::$_controller;
	}
	
	public static function routeRequest() {		
		$uri = trim($_SERVER['REQUEST_URI'],'/');
		
		if(($pos = strpos($uri,'?')) !== false) {
			$uri = substr($uri,0,$pos);
		}
		
		$uri = str_replace("..",'',$uri);
		
		$uri_parts = explode('/',$uri);
		
		//no parts? Goto login. 
		if(!$uri_parts[0]) {
            $file = BT_ROOT . '/bt-config/bt-config.php';
            if(file_exists($file)){
                header("Location: /login");
                BTApp::end();
            }else{
                header("Location: /dbconfig");
                BTApp::end();
            }
		}
				
		//Is ajax call?
		$is_ajax = ($uri_parts[0] == 'ajax') ? true : false;
				
		if($is_ajax) {
			array_shift($uri_parts);
		}
		
		if(!defined("IS_AJAX")) {
			define("IS_AJAX",$is_ajax);
		}
		//end ajax

		self::routeController($uri_parts);
		
		self::end();
	}
	
	//find the "closest matching" controller
	protected static function routeController($uri_parts) {
		$limit = count($uri_parts);
		while($limit && !controllerExists($controller_name = joinControllerName($uri_parts,$limit))) {
			$limit--;
		}
						
		if(!controllerExists($controller_name)) {
			error404();
		}
		
		$load_path = array_splice($uri_parts,0,$limit);
		$command = array_shift($uri_parts);
		
		self::$_loadPath = $load_path;
		self::$_action = $command;
				
		loadController($controller_name);
		
		$controller = new $controller_name();
		
		self::$_controller = $controller;
		
		$controller->setLoadPath('/' . implode('/',$load_path));
		
		$controller->init();

		$controller->doAction($command,$uri_parts);
		
		self::end();
	}
	
	public static function importModel($model) {
		$file = BT_ROOT . '/private/models/' . $model . '.php';
				
		if(!file_exists($file)) {
			throw new Exception("Could not import data model: " . $model);
		}
		
		require_once($file);
	}
	
	public static function end() {		
		if(SHOW_QUERY_LOG) {
			printQueryLogFirePhp();
		}
		
		exit;
	}
	
	/**
	 * Returns the installed version string
	 * @return stirng
	 */
	public static function version() {
		return DB::getVar("select version from bt_g_version");
	}
	
	
	public static function autoload($name) {		
		if(substr($name,-5) == 'Model') {
			self::importModel($name);
		}
	}
}

spl_autoload_register(array('BTApp','autoload'));

function joinControllerName($parts,$limit) {
	$name = '';

	for($i = 0;$i < $limit;$i++) {
		$name .= ucfirst($parts[$i]);
	}

	return $name . 'Controller';
}

BTApp::init();