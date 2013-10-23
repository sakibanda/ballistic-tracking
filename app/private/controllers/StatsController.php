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

        if(@$_GET['campaign_id']) {
            $campaign = CampaignModel::model()->getRowFromPk($_GET['campaign_id']);
            BTAuth::user()->setPref('campaign_id',$_GET['campaign_id']);
        }else{
            $campaign_id = BTAuth::user()->getPref('campaign_id');
            $campaign = CampaignModel::model()->getRowFromPk($campaign_id);
        }

        runStats(true);

        $this->setVar('campaign',$campaign);
        $this->loadView('stats/view_stats');
    }

    public function campaignDataAction(){

        $campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
        $sql_query = "SELECT * FROM bt_c_statcache c JOIN bt_u_campaigns p ON (p.campaign_id = c.meta1) ";
        $sql_query .= "WHERE c.user_id='".$mysql['user_id']."' AND c.type='stats' AND c.meta3=0 ";
        $result = DB::getRows($sql_query);
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $arr[] = BTHtml::encode($row['name']); //Campaign Name
            $arr[] = BTHtml::encode($row['clicks']);
            $arr[] = "0";//BTHtml::encode($row['lpviews']); No database
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

            //id, user_id, time_from, time_to, type
            //clicks, click_throughs, click_throughs_rates, leads, conv, payout, epc, cpc, income, cost, net
            //meta1, meta2, meta3, meta4
        }
        echo json_encode($output);
    }

    public function offerDataAction(){
        $campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
        $sql_query = "SELECT * FROM bt_c_statcache c JOIN bt_u_offers o ON (o.offer_id = c.meta3) ";
        $sql_query .= "WHERE c.user_id='".$mysql['user_id']."' AND c.type='stats' AND c.meta3>0 ";
        $result = DB::getRows($sql_query);
        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $arr[] = BTHtml::encode($row['name']); //Campaign Name
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

    public function lpDataAction(){
        $campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
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
        $campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
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