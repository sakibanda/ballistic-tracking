<?php

class ConfigController extends BTController {

    public function __construct() {
        require_once(BT_ROOT . '/private/libs/wurfl/TeraWurfl.php');
        require_once(BT_ROOT . '/private/libs/wurfl/TeraWurflUtils/TeraWurflUpdater.php');
    }

    public function indexAction() {
        $success = true;
        $message = "";
        $result = false;

        if(isset($_POST['host_name']) && isset($_POST['db_name']) && isset($_POST['user_name']) && isset($_POST['pw_user'])){
            if(($_POST['host_name']!='') && ($_POST['db_name']!='') && ($_POST['user_name']!='') && ($_POST['pw_user']!='')){

                $userDB = $_POST['user_name'];
                $passDB = $_POST['pw_user'];
                $hostDB = $_POST['host_name'];
                $dbName = $_POST['db_name'];

                $file = BT_ROOT . '/install/bt-config_template.php';
                $content = file_get_contents($file);

                $content = str_replace("{host_name}", $hostDB,$content);
                $content = str_replace("{db_name}", $dbName,$content);
                $content = str_replace("{user_name}", $userDB,$content);
                $content = str_replace("{pw_user}", $passDB,$content);

                if(substr(decoct(fileperms(BT_ROOT . '/bt-config')),2)==777){
                    $fh = fopen(BT_ROOT . '/bt-config/bt-config.php','x');
                    $file = BT_ROOT . '/bt-config/bt-config.php';
                    if(file_exists($file)){
                        fwrite($fh,$content);
                        fclose($fh);
                    }

                    if(!$result = $this->installDB($hostDB, $userDB, $passDB,$dbName)){
                        $message = "Can't connect to the database";
                        $success = false;
                    }
                    //Redirect to the validation plan
                    //header('location: /plan');
                    //BTApp::end();
                }else{
                    $message = "Can't write to the directory: ".BT_ROOT . '/bt-config';
                    $success = false;
                }
            }else{
                $message = "Please enter all the values, they All are required.";
                $success = false;
            }
        }

        $this->setVar("title","Database Settings");
        $this->setVar("details",$result);
        $this->setVar("success",$success);
        $this->setVar("message",$message);
        $this->loadTemplate("public_header");
        $this->loadTemplate("public_footer");
        $this->loadView("config/index");
    }

    /*
    ZIPARCHIVE::ER_EXISTS - 10
    ZIPARCHIVE::ER_INCONS - 21
    ZIPARCHIVE::ER_INVAL - 18
    ZIPARCHIVE::ER_MEMORY - 14
    ZIPARCHIVE::ER_NOENT - 9
    ZIPARCHIVE::ER_NOZIP - 19
    ZIPARCHIVE::ER_OPEN - 11
    ZIPARCHIVE::ER_READ - 5
    ZIPARCHIVE::ER_SEEK - 4
    */
    function unzip() {
        $script_file = BT_ROOT . '/install/db/ballistic.zip';
        $zip = new ZipArchive;
        $res = $zip->open($script_file);
        if ($res === TRUE) {
            //echo 'ok';
            $zip->extractTo('install/db/');
            $zip->close();
        } else {
            echo 'failed, code:' . $res;
        }
        return true;
    }

    function installDB($dsn, $user, $password, $dbname){
        $result = false;
        $script_path = BT_ROOT . '/install/db/structure.sql';
        $mysqli = new mysqli($dsn, $user, $password);
        if (mysqli_connect_errno()) {
            //printf("Connect failed: %s\n", mysqli_connect_error());
            return false;
            //exit();
        }

        //Validate if database exists
        $db_selected = mysqli_select_db($mysqli,$dbname);
        if($db_selected){
            $script_sql = 'DROP DATABASE '.$dbname;
            mysqli_query($mysqli,$script_sql);
            $result .= "<p>Deleting database with the same name...</p>";
        }

        $script_sql = 'CREATE DATABASE '.$dbname;
        mysqli_query($mysqli,$script_sql);
        $result .= "<p>Creating a new database...</p>";

        mysqli_select_db($mysqli,$dbname);
        $query = file_get_contents($script_path);
        if (mysqli_multi_query($mysqli,$query)){
            $this->clearStoredResults($mysqli);
            $result .= "<p>Database <b>$dbname</b> has been created successfully...</p>";
        }else{
            //printf("Error creating database: %s\n", mysqli_error($mysqli));
            return false;
            //exit();
        }

        TeraWurflConfig::$DB_SCHEMA = $dbname;
        TeraWurflConfig::$DB_HOST = $dsn;
        TeraWurflConfig::$DB_USER = $user;
        TeraWurflConfig::$DB_PASS = $password;

        $result .= "<p>Installing the data...</p>";
        $wurfl = new TeraWurfl();
        $updater = new TeraWurflUpdater($wurfl,TeraWurflUpdater::SOURCE_LOCAL);
        $updater->update();

        $result .= "<p>Data has been installed successfully...</p>";
        $result .= "<p><b>DONE.</b></p>";
        mysqli_close($mysqli);
        return $result;
    }

    function clearStoredResults($mysqli){
        while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli)){
            if($l_result = mysqli_store_result($mysqli)){
                $l_result->free();
            }
        }
    }
}