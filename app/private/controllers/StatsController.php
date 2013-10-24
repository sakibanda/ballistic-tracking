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

        $cols = array('v1', 'v2', 'v3', 'v4', 'clicks', 'click_throughs', 'leads', 'conv', 'payout', 'epc', 'income');
        $cnt_query = "select count(1) from (select 1 from";
        $cnt_query .= getReportFilters('reports/stats','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)');
        $cnt_query .= " and adv.v1_id>0 and adv.v2_id>0 and adv.v3_id>0 and adv.v4_id>0 GROUP BY adv.v1_id, adv.v2_id, adv.v3_id, adv.v4_id) thedata";
        $cnt = DB::getVar($cnt_query);
        $sql = 'select tv1.var_value as v1,
				tv2.var_value as v2,
				tv3.var_value as v3,
				tv4.var_value as v4,';
        $sql .= getReportGeneralSelects() . ' from ';
        $sql .= getReportFilters('reports/stats','
			left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)
			LEFT JOIN bt_s_variables AS tv1 on tv1.var_id=adv.v1_id
			LEFT JOIN bt_s_variables AS tv2 on tv2.var_id=adv.v2_id
			LEFT JOIN bt_s_variables AS tv3 on tv3.var_id=adv.v3_id
			LEFT JOIN bt_s_variables AS tv4 on tv4.var_id=adv.v4_id
		');
        $sql .= ' and adv.v1_id>0 and adv.v2_id>0 and adv.v3_id>0 and adv.v4_id>0 group by adv.v1_id, adv.v2_id, adv.v3_id, adv.v4_id ';
        //$sql .= getReportOrder($cols);
        //$sql .= getReportLimits();
        $result = DB::getRows($sql);

        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $arr[] = BTHtml::encode($row['v1']); //Subid1
            $arr[] = BTHtml::encode($row['v2']); //Subid2
            $arr[] = BTHtml::encode($row['v3']); //Subid3
            $arr[] = BTHtml::encode($row['v4']); //Subid4
            $arr[] = BTHtml::encode($row['clicks']); //Clicks
            $arr[] = BTHtml::encode($row['click_throughs']); //Click Throughs
            $arr[] = BTHtml::encode($row['leads']); //Leads
            $arr[] = BTHtml::encode($row['conv']); //Conv %
            $arr[] = BTHtml::encode($row['payout']); //Payout
            $arr[] = BTHtml::encode($row['epc']); //EPC
            $arr[] = BTHtml::encode($row['income']); //Income
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }
}