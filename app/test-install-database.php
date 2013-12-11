<?php
require_once realpath(dirname(__FILE__).'/private/libs/wurfl/TeraWurfl.php');
require_once realpath(dirname(__FILE__).'/private/libs/wurfl/TeraWurflUtils/TeraWurflUpdater.php');
?>
<!doctype html>
<html>
<head>
    <title>Home</title>
</head>
<body>
<h1>Ballistic Tracker Requirement Checker</h1>
<table>
    <tr>
        <td>Name</td>
        <td>Results</td>
    </tr>
    <tr>
        <td>PHP Version</td>
        <?php if(version_compare(PHP_VERSION,"5.0.0") === 1){
            echo "<td>".PHP_VERSION." Passed</td>";
        }else{
            echo "<td>Failed. PHP5 is required but you have ".PHP_VERSION."</td>";
        }?>
    </tr>
    <tr>
        <td>Zip Archive</td>
        <?php if(class_exists("ZipArchive",false)){
            echo "<td>Passed</td>";
        }else{
            echo "<td>Failed</td>";
        }?>
    </tr>
    <tr>
        <td>MySQLi</td>
        <?php if(class_exists("MySQLi")){ // SQL Driver for PHP: function_exists("sqlsrv_connect")
            echo "<td>Passed</td>";
        }else{
            echo "<td>Failed</td>";
        }?>
    </tr>
</table>

<?php
$dsn ="localhost";
$user ="root";
$password ="root";
$dbname ="ballistic_tracking";

//$script_path = BT_ROOT . '/install/db/bt_data.sql';
$script_structure = 'install/db/structure.sql';
$script_data = 'install/db/data.sql';
$mysqli = new mysqli($dsn, $user, $password);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//Validate if database exists
$db_selected = mysqli_select_db($mysqli,$dbname);
if($db_selected){
    $script_sql = 'DROP DATABASE '.$dbname;
    mysqli_query($mysqli,$script_sql);
    echo "<p>Deleting database...</p>";
}

$script_sql = 'CREATE DATABASE '.$dbname;
mysqli_query($mysqli,$script_sql);
echo "<p>Creating database...</p>";

mysqli_select_db($mysqli,$dbname);
$query = file_get_contents($script_structure);
if ($result = mysqli_multi_query($mysqli,$query)){
    clearStoredResults($mysqli);
    echo "<p>Database <b>$dbname</b> has been created successfully...</p>";
}else{
    echo "<p>Error creating database: " . mysqli_error($mysqli) . "<p>";
}

TeraWurflConfig::$DB_SCHEMA = $dbname;
TeraWurflConfig::$DB_HOST = $dsn;
TeraWurflConfig::$DB_USER = $user;
TeraWurflConfig::$DB_PASS = $password;

$wurfl = new TeraWurfl();
$updater = new TeraWurflUpdater($wurfl,TeraWurflUpdater::SOURCE_LOCAL);
$updater->update();

/*
if(createProcedures($mysqli)){
    clearStoredResults($mysqli);
    echo "<p>Store Procedures created</p>";
}else{
    echo "<p>Error creating Store Procedures: " . mysqli_error($mysqli) . "</p>";
}

//Installing the data.
echo "<p>Installing data...</p>";
$handle = @fopen($script_data, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        if(mysqli_query($mysqli,$buffer))
            echo "<p>".$buffer."</p>";
        else
            echo "<p>Error installing data: " . $mysqli->error . "<p>";
    }
    if (!feof($handle)) {
        echo "<p>Error installing data: unexpected fgets() fail</p>";
    }
    fclose($handle);
}
*/
echo "<p>Data has been installed successfully...</p>";
echo "<p>DONE.</p>";
mysqli_close($mysqli);
//return true;
function clearStoredResults($mysqli){
    while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli)){
        if($l_result = mysqli_store_result($mysqli)){
            $l_result->free();
        }
    }
}

function createProcedures($mysqli){
    $TeraWurfl_RIS = "CREATE PROCEDURE `TeraWurfl_RIS`(IN ua VARCHAR(255), IN tolerance INT, IN matcher VARCHAR(64))
BEGIN
DECLARE curlen INT;
DECLARE wurflid VARCHAR(64) DEFAULT NULL;
DECLARE curua VARCHAR(255);

SELECT CHAR_LENGTH(ua)  INTO curlen;
findua: WHILE ( curlen >= tolerance ) DO
	SELECT CONCAT(REPLACE(REPLACE(LEFT(ua, curlen ), '%', '\%'), '_', '\_'),'%') INTO curua;
	SELECT idx.DeviceID INTO wurflid
		FROM TeraWurflIndex idx INNER JOIN TeraWurflMerge mrg ON idx.DeviceID = mrg.DeviceID
		WHERE mrg.match = 1 AND idx.matcher = matcher
		AND mrg.user_agent LIKE curua
		LIMIT 1;
	IF wurflid IS NOT NULL THEN
		LEAVE findua;
	END IF;
	SELECT curlen - 1 INTO curlen;
END WHILE;

SELECT wurflid as DeviceID;
END";
    mysqli_query($mysqli,"DROP PROCEDURE IF EXISTS `TeraWurfl_RIS`");
    mysqli_query($mysqli,$TeraWurfl_RIS);
    $TeraWurfl_FallBackDevices = "CREATE PROCEDURE `TeraWurfl_FallBackDevices`(current_fall_back VARCHAR(64))
BEGIN
find_fallback: WHILE current_fall_back != 'root' DO
	SELECT capabilities FROM TeraWurflMerge WHERE deviceID = current_fall_back;
	IF FOUND_ROWS() = 0 THEN LEAVE find_fallback; END IF;
	SELECT fall_back FROM TeraWurflMerge WHERE deviceID = current_fall_back INTO current_fall_back;
END WHILE;
END";
    mysqli_query($mysqli,"DROP PROCEDURE IF EXISTS `TeraWurfl_FallBackDevices`");
    mysqli_query($mysqli,$TeraWurfl_FallBackDevices);
    return true;
}
?>
</body>
</html>