<?php

class StatsController extends BTUserController {

    public function __construct() {
        $this->loadModel('OfferModel');
        require_once(BT_ROOT . '/private/includes/reporting/export.php');
    }

    public function indexAction() {
        $this->useActionAsCurrentNav();
        $this->setVar("title", "Campaign Stats");
        $this->render("stats/index");
    }

    public function dataAction(){
        $offers = OfferModel::model()->getRows();
        $iTotal = sizeof($offers);
        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $_GET['iDisplayLength'],
            "aaData" => array()
        );
        echo json_encode($output);
    }
}