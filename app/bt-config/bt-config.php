<?php

// ** MySQL settings ** //
$dbname = 'ballistictest';
$dbuser = 'root';
$dbpass = 'root';
$dbhost = 'localhost';

define('DEBUG',true);
define('LIVE_SITE',false);

define('HAS_SSL',false);

ini_set('display_errors',1);

define('SHOW_QUERY_LOG',true);

define('QUERY_LOG_ENABLE',true);

define('API_SERVER','http://lptrck.us');

define('GLOBAL_HASH_SALT','BsDpiDFePMMTh8B');