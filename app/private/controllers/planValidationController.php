<?php

class planValidationController extends BTController {
    public function indexAction() {
        $success = true;
        $message = "";
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
                    $message = 'Invalid credentials.';
                    $success = false;
                }else{
                    $sql_update = "UPDATE bt_u_settings as s SET s.api_key ='".BTAuth::salt_pass($_POST['key'])."' WHERE s.settings_id = ".$result[0]['settings_id'];
                    if(!DB::query($sql_update)){ $success = false; $message = 'Failed to activate plan, please try again';}
                    BTAuth::set_auth_cookie(" ",time());
                    header('location: /login');
                    BTApp::end();
                }
            }else{
                $success = false;
                $message = 'Currently there are pending activation plans for this domain.';
            }
        }

        $this->setVar("title","Plan Validation");
        $this->loadTemplate("public_header");
        $this->setVar("success",$success);
        $this->setVar("message",$message);
        $this->loadView("planValidation/planValidation");

        $this->loadTemplate("public_footer");
    }
}