<?php

loadController('AdminController');

class AdminSettingsController extends AdminController {

    public function __construct() {
        $this->loadModel('SettingsModel');
    }

    public function saveAction() {

        $success = false;
        $error = array();
        $settings = new SettingsModel();

        if(isset($_POST['api_key']) && isset($_POST['domain_name'])){
            if(($_POST['api_key']!='') && ($_POST['domain_name']!='')){
                $settings = SettingsModel::model()->getRowFromPk($_POST['id']);
                if($settings != null){
                    $settings->api_key = $_POST['api_key'];
                    $settings->domain = $_POST['domain_name'];
                    $settings-> buy_date = date('Y-m-d');
                }else{
                    $settings = new SettingsModel();
                    $settings->api_key = $_POST['api_key'];
                    $settings->domain = $_POST['domain_name'];
                    $settings-> buy_date = date('Y-m-d');
                    $settings-> recurrence = 1;
                    $settings-> user_id = getUserID();
                    $settings->type = 'Advanced Redirects';
                }
                if($settings->save()) {
                    $success = "Api Key Information Saved.";
                }else {
                    $error = $settings->getErrors();
                }
            }else{
                array_push($error,"Please enter both values. Domain and Api Key are required.");
            }
        }

        $this->setVar("title","Update Settings");
        $this->setVar("error",$error);
        $this->setVar("success",$success);
        $this->setVar("settings",$settings);
        $this->render('admin/index');

    }

}