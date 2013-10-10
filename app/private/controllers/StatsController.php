<?php

class StatsController extends BTUserController {

    public function __construct() {
        $this->loadModel('CampaignModel');
        require_once(BT_ROOT . '/private/includes/reporting/export.php');
    }

    public function indexAction() {
        $this->useActionAsCurrentNav();
        $this->setVar("title", "Campaign Stats");

        if(getArrayVar($_GET,'campaign_id')) {
            $campaign = CampaignModel::model()->getRowFromPk($_GET['campaign_id']);
        }else{
            $campaign = CampaignModel::model();
        }

        $this->setVar('campaign',$campaign);
        $this->render("stats/index");
    }

    public function campaignDataAction(){
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        for($i=0;$i<3;$i++){
            $arr = array();
            $arr[] = "Campaign Name".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }

    public function offerDataAction(){
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        for($i=0;$i<3;$i++){
            $arr = array();
            $arr[] = "Offer Name".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }

    public function lpDataAction(){
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        for($i=0;$i<3;$i++){
            $arr = array();
            $arr[] = "LP Name".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $arr[] = "0".$i;
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }
}