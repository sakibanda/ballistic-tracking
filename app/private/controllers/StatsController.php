<?php

class StatsController extends BTUserController {

    public function __construct() {
        $this->loadModel('CampaignModel');
        require_once(BT_ROOT . '/private/includes/reporting/export.php');
    }

    public function indexAction() {
        $this->useActionAsCurrentNav();
        $this->setVar("title", "Campaign Stats");
        $this->render("stats/index");
    }

    public function viewStatsAction(){
        if(@$_POST['campaign_id']) {
            $campaign = CampaignModel::model()->getRowFromPk($_GET['campaign_id']);
        }else{
            $campaign = CampaignModel::model();
        }

        $this->setVar('campaign',$campaign);
        $this->loadView('stats/view_stats');
    }

    public function campaignDataAction(){

        $mysql['user_id'] = DB::quote(getUserID());
        $sql_query = "SELECT * FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' AND meta1 IS NOT NULL group by meta1 order by meta1 ";
        $result = DB::getRows($sql_query);
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $arr[] = BTHtml::encode($row['meta1']);
            $arr[] = BTHtml::encode($row['clicks']);
            $arr[] = "0";//BTHtml::encode($row['lpviews']);
            $arr[] = "0";//BTHtml::encode($row['lpclicks']);
            $arr[] = "0";//BTHtml::encode($row['lpctr']);
            $arr[] = BTHtml::encode($row['leads']);
            $arr[] = "0";//BTHtml::encode($row['offercvr']);
            $arr[] = "0";//BTHtml::encode($row['lpcvr']);
            $arr[] = BTHtml::encode($row['epc']);
            $arr[] = BTHtml::encode($row['cpc']);
            $arr[] = "0";//BTHtml::encode($row['rev']);
            $arr[] = BTHtml::encode($row['cost']);
            $arr[] = "0";//BTHtml::encode($row['profit']);
            $arr[] = BTHtml::encode($row['roi']);
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

    public function subidDataAction(){
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        for($i=0;$i<3;$i++){
            $arr = array();
            $arr[] = "Subid Name".$i;
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