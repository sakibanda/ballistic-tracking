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
            $settings = SettingsModel::model()->getRowFromPk($_POST['id']);
            if($settings != null){
                if($settings->pass_key == $_POST['api_key']){
                    $settings->api_key = $_POST['api_key'];
                    $settings->domain = $_POST['domain'];
                    if($settings->save()) {
                        $success = "Settings saved";
                    }else {
                        $error = $settings->getErrors();
                    }
                }else{
                    array_push($error,"The API KEY did not recognize.");
                }
            }else{
                $settings = new SettingsModel();
                array_push($error,"It seems like You haven't purchased any API KEY.");
            }
        }

        $this->setVar("title","Update Settings");
        $this->setVar("error",$error);
        $this->setVar("success",$success);
        $this->setVar("settings",$settings);
        $this->render('admin/index');

    }

}