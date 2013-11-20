<?php

class dbConfigController extends BTController {

    public function indexAction() {
        $success = true;
        $message = "";
        if(isset($_POST['host_name']) && isset($_POST['db_name']) && isset($_POST['user_name']) && isset($_POST['pw_user'])){
            if(($_POST['host_name']!='') && ($_POST['db_name']!='') && ($_POST['user_name']!='') && ($_POST['pw_user']!='')){
                $file = BT_ROOT . '/private/includes/bt-config_template.php';
                $content = file_get_contents($file);

                $content = str_replace("{host_name}", $_POST['host_name'],$content);
                $content = str_replace("{db_name}", $_POST['db_name'],$content);
                $content = str_replace("{user_name}", $_POST['user_name'],$content);
                $content = str_replace("{pw_user}", $_POST['pw_user'],$content);

                if(substr(decoct(fileperms(BT_ROOT . '/bt-config')),2)==777){
                    $fh = fopen(BT_ROOT . '/bt-config/bt-config.php','x');
                    $file = BT_ROOT . '/bt-config/bt-config.php';
                    if(file_exists($file)){
                        fwrite($fh,$content);
                        fclose($fh);
                    }
                    //Redirect to the validation plan
                    header('location: /planValidation');
                    BTApp::end();
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

 }