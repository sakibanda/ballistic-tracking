<?php

//our own die, that will display the them around the error message
function _die($message) { 
	$title = "Error";
	
	require(BT_ROOT . '/theme/public_header.php');
	echo $message;
	require(BT_ROOT . '/theme/public_footer.php');
	die();
}

//Gets the first var in the index array that isset
function getArrayCoalesceVar($arr,$indices,$default = '') {
	if(!is_array($indices)) {
		return $default;
	}
	
	foreach($indices as $index) {
		if(isset($arr[$index])) {
			return $arr[$index];
		}
	}
	
	return $default;
}

function loadController($controller) {
	$file = BT_ROOT . '/private/controllers/' . $controller . '.php';
	
	if(!controllerExists($controller)) {
		throw new Exception("Controller does not exist: " . $controller);
	}
	
	require_once($file);
}

function controllerExists($controller) {
	$file = BT_ROOT . '/private/controllers/' . $controller . '.php';
	
	return file_exists($file);
}

function error404() {
	//Failsafe, to prevent an infinite routing loop :)
	if(strpos($_SERVER['REQUEST_URI'],'error') !== false) {
		//An error page 404'ed. This is bad. 
		echo "Critical server error.";
		
		BTApp::log("Encountered error 404 while loading an error page: " . $_SERVER['REQUEST_URI'],"router", BT_SYSLOG_CRITICAL);
		BTApp::end();
	}
	
	//Let's just "tell" the app/router to try again - this time loading the error controller & 404 page.
	$_SERVER['REQUEST_URI'] = '/error/error404';
	BTApp::routeRequest();
	
	BTApp::end();
}

function getArrayVar($arr,$index,$default = '') {
	if(!isset($arr[$index])) return $default;
	
	return $arr[$index];
}

/**
 * Append a query string to a url. Query string should NOT being with ? or &, as this function
 * will handle all of that.
 * 
 * @param string $url
 * @param string $query_string
 * @return string
 */
function appendQueryString($url,$query_string) {
	if(!$query_string) {
		return $url;
	}
	
	if(strpos($url,'?') === false) {
		$url .= '?';
	}
	else {
		$url .= '&';
	}
	
	return $url . $query_string;
}

/**
 * Shortens a string to $len and adds ellipsis, if needed. 
 * 
 * @param string $str
 * @param int $len
 * @return string
 */
function shortenWithEllipsis($str,$len) {
	if(strlen($str) > $len) {
		$str = substr($str,0,$len) . '...';
	}
	
	return $str;
}

function generateRandomString($min,$max) {
	$chrs = '0123456789abcdefghijklmnopqrstuvwxyz';
	$str = '';
	$length = mt_rand($min,$max);
	
	for($i = 0; $i < $length;$i++) {
		$str .= $chrs[mt_rand(0, strlen($chrs) - 1)];
	}
	
	return $str;
}

function bt_build_http_query($data) {
	$url = '';
	
	foreach ($data AS $key=>$value) {
		$url .= $key.'='.$value.'&';
	}
	
	$url = rtrim($url, '&'); 
	
	return $url;
}