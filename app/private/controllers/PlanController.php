<?php

class PlanController extends BTController {

    public function __construct() {
        $this->loadModel('SettingsModel');
        $this->loadModel('UserModel');
    }

    public function indexAction() {
        $success = false;
        $error = array();

        if(isset($_POST['api_key']) && isset($_POST['domain_name'])){
            $key = $_POST['api_key'];
            $domain = $_POST['domain_name'];
            $email = $_POST['email'];

            $user = UserModel::model()->getRow(array(
                'conditions'=>array(
                    'email' => $email
                )
            ));
            $user_id = $user->user_id;

            $settings = SettingsModel::model()->getRow(array(
                'conditions'=>array(
                    'user_id' => $user->$user_id,
                    'type' => 'Ballistic',
                    'domain' => $domain
                )
            ));

            if($settings){
                $settings->api_key = $key;
            }else{
                $settings = new SettingsModel();
                $settings-> api_key = $key;
                $settings-> domain = $domain;
                $settings-> buy_date = date('Y-m-d');
                $settings-> type = 'Ballistic';
                $settings-> recurrence = 1;
                $settings-> user_id = $user_id;

                if($settings->save()) {
                    $success = "Api Key Information Saved.";
                }else{
                    $error = $settings->getErrors();
                }
            }
            /*
            $sql_plan = "SELECT * FROM bt_u_settings WHERE type = 'Ballistic' ";
            $sql_plan .= "AND user_id =".$user_id." AND domain = '".$_SERVER['HTTP_HOST']."' AND api_key = ''";
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
            }*/
        }

        if(isset($_GET['error'])){
            if($_GET['error']=="1")
                $error="Invalid API KEY";
            if($_GET['error']=="2")
                $error="Please enter your API KEY information";
        }

        $this->setVar("title","Plan Validation");
        $this->setVar("success",$success);
        $this->setVar("error",$error);
        $this->loadTemplate("public_header");
        $this->loadView("plan/index");
        $this->loadTemplate("public_footer");
    }
}