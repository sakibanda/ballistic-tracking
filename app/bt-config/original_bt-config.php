<?php

// ** MySQL settings ** //
$dbname = 'ballistic_tracking';    		// The name of the database
$dbuser = 'btrck';     		  		// Your MySQL username
$dbpass = '12345'; 			// ...and password
$dbhost = 'localhost';    					// 99% chance you won't need to change this value

define('DEBUG',true);
define('LIVE_SITE',false);

define('HAS_SSL',false);

ini_set('display_errors',1);

define('SHOW_QUERY_LOG',true);

define('QUERY_LOG_ENABLE',true);

define('API_SERVER','http://lptrck.us');

define('GLOBAL_HASH_SALT','BsDpiDFePMMTh8B');