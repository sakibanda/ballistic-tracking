<?php

//BEGIN BALLISTIC ROUTER
try {
	define('BT_IS_ROUTED',true);

	require_once(__DIR__. '/includes/BTApp.php');
	BTApp::routeRequest();
}
catch (Exception $e) {	
	var_dump($e);
	
	echo "Core system error. Cannot continue.";
	BTApp::end();
}
//END BALLISTIC ROUTER