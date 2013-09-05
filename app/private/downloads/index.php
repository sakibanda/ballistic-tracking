<?php

ini_set('display_errors',0);

define('API_VERS','1.0');

$request_uri = $_SERVER['REQUEST_URI'];
//remove query string
if(($pos = strpos($request_uri,'?')) !== false) {
	$request_uri = substr($request_uri,0,$pos);
}

//htaccess handler, only run if loading the api file directly
if(strpos($request_uri,".php") !== false) {
	//attempt to setup htaccess and such, if being loaded directly
	
	if(!file_exists(__DIR__ . '/.htaccess')) {
		$cur = $_SERVER['SCRIPT_NAME'];
		$path = dirname($cur);
		$file = basename($cur);
		$quotes = preg_quote($file);
				
		$htaccess = "
#API REWRITE
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase $path
	RewriteRule ^{$quotes}$ - [L]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . {$file} [L]
</IfModule>";
	
		$fh = fopen(__DIR__ . '/.htaccess','w+');
		if(!$fh)
		{
			echo "Could not setup the <strong>.htaccess</strong> file. Double check your permissions, or create the file manually with the code below:<br><br>\n\n";
			
			echo '<pre>' . htmlentities($htaccess) . '</pre>';
		}
		else {
			fwrite($fh,$htaccess);
			
			fclose($fh);
		}
	}
	
	exit;
}

$payload = array(
	'ip'=>$_SERVER['REMOTE_ADDR'],
	'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
	'referer'=>$_SERVER['HTTP_REFERER'],
	'version'=>API_VERS,
	'slug'=>$request_uri,
	'query'=>$_SERVER['QUERY_STRING']
);

$ch = curl_init("{BT_URL}/api/cloaker");
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($payload));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$url = curl_exec($ch);

if(strpos($url,'http') === false) {
	exit;
}

header("HTTP/1.1 301 Moved Permanently");

//disallow browser caching
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); //HTTP/1.1

header("Pragma: no-cache");
header("Location: $url");