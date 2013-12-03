<?php

class dbConfigController extends BTController {

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
        $this->loadTemplate("public_header");
        $this->setVar("success",$success);
        $this->setVar("message",$message);
        $this->loadView("dbconfig/dbconfig");
        $this->loadTemplate("public_footer");
    }

    function unzip() {
        $script_file = BT_ROOT . '/install/db/ballistic.zip';
        $zip = new ZipArchive;
        $zip->open($script_file);
        $zip->extractTo(BT_ROOT . '/install/db/');
        $zip->close();
        return true;
    }

    function installDB($dsn, $user, $password, $dbname){

        // Connect to MySQL
        $link = mysql_connect($dsn, $user, $password);
        if(!$link){
            die('Could not connect: ' . mysql_error());
        }

        // Make my_db the current database
        $db_selected = mysql_select_db($dbname, $link);
        if(!$db_selected) {
            // If we couldn't, then it either doesn't exist, or we can't see it.
            $sql = 'CREATE DATABASE '.$dbname;
            if(mysql_query($sql, $link)){
                $script_path = BT_ROOT . '/install/db/ballistic.sql';
                $command = "mysql -u{$user} -p{$password} "
                    . "-h {$dsn} -D {$dbname} < {$script_path}";
                $output = shell_exec($command);
                echo "Database my_db created successfully\n";
            }else{
                echo 'Error creating database: ' . mysql_error() . "\n";
            }
        }
        mysql_close($link);
        return true;
    }

 }