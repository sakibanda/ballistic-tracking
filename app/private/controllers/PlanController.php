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

            $user_id = 1;
            $settings = SettingsModel::model()->getRow(array(
                'conditions'=>array(
                    'user_id' => $user_id,
                    'type' => 'Ballistic',
                    'domain' => $domain
                )
            ));

            if($settings){
                $settings->api_key = $key;
                $settings-> buy_date = date('Y-m-d');
            }else{
                $settings = new SettingsModel();
                $settings-> api_key = $key;
                $settings-> domain = $domain;
                $settings-> buy_date = date('Y-m-d');
                $settings-> type = 'Ballistic';
                $settings-> recurrence = 1;
                $settings-> user_id = $user_id;
            }
            if($settings->save()) {
                $success = "Api Key Information Saved.";
            }else{
                $error = $settings->getErrors();
            }
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
        $this->loadTemplate("public_footer");
        if($success){
            $this->loadView("login/login");
        }else{
            $this->loadView("plan/index");
        }
    }
}