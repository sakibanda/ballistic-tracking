<?php

// ** MySQL settings ** //
$dbname = '{db_name}';
$dbuser = '{user_name}';
$dbpass = '{pw_user}';
$dbhost = '{host_name}';

define('DEBUG',true);
define('LIVE_SITE',false);

define('HAS_SSL',false);

ini_set('display_errors',1);

define('SHOW_QUERY_LOG',true);

define('QUERY_LOG_ENABLE',true);

define('API_SERVER','http://lptrck.us');

define('GLOBAL_HASH_SALT','BsDpiDFePMMTh8B');