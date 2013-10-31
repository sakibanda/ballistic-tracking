<?php

loadController('AdminController');

class AdminSettingsController extends AdminController {

    public function __construct() {
        $this->loadModel('SettingsModel');
    }

    public function saveAction() {

        $success = false;
        $error = array();
        $settings = null;

        if(isset($_POST['keyId'])){
            if(!($settings = SettingsModel::model()->getRowFromPk($_POST['id']))) {
                $settings = SettingsModel::model();
                $settings->useRuleSet('new');
            }
            $settings->keyId = $_POST['keyId'];
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