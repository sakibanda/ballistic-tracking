<?php

class PlanValidationController extends BTController {
    public function indexAction() {
        $success = false;
        $error = array();

        //$cookie = getArrayVar($_COOKIE,'bt_auth');
        //if(!$cookie) { return false; }
        if(isset($_POST['key']) && isset($_POST['domain_name'])){
            //$split = explode('|',$cookie);
            $user_id = 2;//DB::quote($split[1]);
            $sql_plan = "SELECT s.settings_id ,s.pass_key,s.api_key, s.domain FROM bt_u_settings s WHERE s.type = 'Ballistic' ";
            $sql_plan .= "AND s.user_id =".$user_id." AND s.domain = '".$_SERVER['HTTP_HOST']."' AND s.api_key = ''";
            if(DB::getRows($sql_plan)){
                $result = DB::getRows($sql_plan);

                if ($result[0]['pass_key']!=BTAuth::salt_pass($_POST['key'])){
                    $error = 'Invalid credentials.';
                }else{
                    $sql_update = "UPDATE bt_u_settings as s SET s.api_key ='".BTAuth::salt_pass($_POST['key'])."' WHERE s.settings_id = ".$result[0]['settings_id'];
                    if(!DB::query($sql_update)){
                        $error = 'Failed to activate plan, please try again';
                    }
                    BTAuth::set_auth_cookie(" ",time());
                    header('location: /login');
                    BTApp::end();
                }
            }else{
                $error = 'Currently there are pending activation plans for this domain.';
            }
        }

        if(isset($_GET['error'])){
            if($_GET['error']=="1")
                $error="Invalid API KEY";
        }

        $this->setVar("title","Plan Validation");
        $this->loadTemplate("public_header");
        $this->setVar("success",$success);
        $this->setVar("error",$error);
        $this->loadView("planValidation/index");

        $this->loadTemplate("public_footer");
    }
}