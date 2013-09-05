<?php

ini_set('display_errors',0);

define('API_VERS','1.0');

$cid = (isset($_GET['cid'])) ? $_GET['cid'] : 0;
$btid = (isset($_GET['btid'])) ? $_GET['btid'] : 0;

define('CLOAKING_TYPE_NONE',0);
define('CLOAKING_TYPE_301',31);
define('CLOAKING_TYPE_302',32);
define('CLOAKING_TYPE_307',37);
define('CLOAKING_TYPE_DOUBLE_META',2);
define('CLOAKING_TYPE_JS',10);
define('CLOAKING_TYPE_JSMETA',12);

function metaCloak($url) {
	?><html>
	<head>
		<meta http-equiv="refresh" content="0;url=<?php echo $url; ?>">
	</head>
	<body>
	</body>
	</html><?php
}

function jsCloak($url) {
	?><html>
    <head>
        <title>Redirecting...</title>
        <script type='text/javascript'>
            document.location.href = "<?php echo $url; ?>";
        </script>
    </head>
    
    <body onLoad=' document.location.href="<?php echo $url; ?>"; '>
    
    </body>
	</html><?php
}

$referer = (isset($_GET['real_referer'])) ? $_GET['real_referer'] : $_SERVER['HTTP_REFERER'];

$payload = array('cid'=>$cid,'btid'=>$btid,'query_string'=>$_SERVER['QUERY_STRING'],'user_ip'=>$_SERVER['REMOTE_ADDR'],'user_agent'=>$_SERVER['HTTP_USER_AGENT'],'user_referer'=>$referer,'api_version'=>API_VERS);

$ch = curl_init("http://ballistictracking.com/api/");
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($payload));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$api_data = curl_exec($ch);

$api_ret = json_decode($api_data);

if(isset($api_ret->error)) {
	BTApp::end();
}

$cloaking_type = $api_ret->type;
$cloaking_url = $api_ret->url;

switch($cloaking_type) {
	case CLOAKING_TYPE_301:
		header("Status: 301"); //  Moved Permanently
		header("Location: {$cloaking_url}");
		break;
	case CLOAKING_TYPE_302:
		header("Status: 302"); //  Found
		header("Location: {$cloaking_url}");
		break;
	case CLOAKING_TYPE_307:
		header("Status: 307"); //  Moved Temporarily
		header("Location: {$cloaking_url}");
		break;
	case CLOAKING_TYPE_DOUBLE_META:
		metaCloak($cloaking_url);
		break;
	case CLOAKING_TYPE_JS:
		jsCloak($cloaking_url);
		break;
	default:
		header('Location: '.$cloaking_url);
		break;
}
BTApp::end();