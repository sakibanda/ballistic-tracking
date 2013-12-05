<?php

class ConfigController extends BTController {

    public function indexAction() {
        $success = true;
        $message = "";

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

                    if($this->unzip()){
                        if($this->installDB($hostDB, $userDB, $passDB,$dbName)){
                            //Redirect to the validation plan
                            header('location: /plan');
                            BTApp::end();
                        }
                    }else{
                        $message = "There was a problem with database.";
                        $success = false;
                    }

                }else{
                    $message = "Can't write to the directory: ".BT_ROOT . '/bt-config';
                    $success = false;
                }
            }else{
                $message = "Can't connect to the database";
                $success = false;
            }
        }

        $this->setVar("title","Database Settings");
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
        $script_path = BT_ROOT . '/install/db/bt_data.sql';
        $mysqli = new mysqli($dsn, $user, $password);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $db_selected = mysqli_select_db($mysqli,$dbname);
        if($db_selected){
            $script_sql = 'DROP DATABASE '.$dbname;
            mysqli_query($mysqli,$script_sql);
        }
        $script_sql = 'CREATE DATABASE '.$dbname;
        mysqli_query($mysqli,$script_sql);

        $mysqli->select_db($dbname);
        $query_structure = file_get_contents($script_path);
        if (mysqli_multi_query($mysqli, $query_structure))
            echo "Database my_db created successfully\n";
        else
            echo 'Error creating database: ' . mysqli_error($mysqli) . "\n";

        mysqli_close($mysqli);
        return true;
    }
}