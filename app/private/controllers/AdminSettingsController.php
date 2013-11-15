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

        if(isset($_POST['api_key']) && !empty($_POST['api_key'])){
            if(!($settings = SettingsModel::model()->getRowFromPk($_POST['id']))) {
                $settings = SettingsModel::model();
                $settings->useRuleSet('new');
            }
            $settings->api_key = $_POST['api_key'];
            $settings->domain = $_POST['domain'];

            if($settings->save()) {
                $success = "Settings saved";
            }else {
                $error = $settings->getErrors();
            }
        }

        $this->setVar("title","Update Settings");
        $this->setVar("error",$error);
        $this->setVar("success",$success);
        $this->setVar("settings",$settings);
        $this->render('admin/index');

    }

}