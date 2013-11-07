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

        //$campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
        $sql_query = "SELECT * FROM bt_c_statcache c JOIN bt_u_campaigns p ON (p.campaign_id = c.meta1) ";
        $sql_query .= "WHERE c.user_id='".$mysql['user_id']."' AND c.type='stats' AND (c.meta3 is null or c.meta3=0)";
        $result = DB::getRows($sql_query);

        $total = "SELECT sum(clicks) FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' AND type='stats' AND meta3!=0 ";
        $lpclicks = DB::getVar($total);
        if($lpclicks==null)
            $lpclicks="0";

        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $leads = $row['leads'];
            $clicks = $row['clicks'];
            $arr[] = BTHtml::encode($row['campaign_id'].": ".$row['name']); //Campaign Name
            $arr[] = BTHtml::encode($clicks);
            $arr[] = "0";//BTHtml::encode($row['lpviews']); No database
            $arr[] = $lpclicks;//BTHtml::encode($row['lpclicks']);

            //lpctr
            $lpctr = (($lpclicks/$clicks)*100);
            $arr[] = number_format($lpctr,2,'.','') . '%';

            $arr[] = BTHtml::encode($leads);

            //offercvr
            if($lpclicks>0){
                $offercvr = (($leads/$lpclicks)*100);
                $arr[] = number_format($offercvr,2,'.','') . '%';
            }else{
                $arr[] = "0.0%";
            }

            //lpcvr
            $lpcvr = (($leads/$clicks)*100);
            $arr[] = number_format($lpcvr,2,'.','') . '%';

            $arr[] = money_format('$%i', BTHtml::encode($row['epc']));
            $arr[] = money_format('$%i', BTHtml::encode($row['cpc']));

            //rev
            $rev = $row['payout'] + $row['income'];
            $arr[] = money_format('$%i', BTHtml::encode($rev));

            //cost
            $arr[] = money_format('$%i', BTHtml::encode($row['cost']));

            //profit
            if($row['payout']>0){
                $profit = $row['payout'] - $row['cost'];
                $arr[] = $this->formatMoney($profit);
            }else{
                $arr[] = $this->formatMoney($row['cost']);
            }

            //roi
            $arr[] = number_format(BTHtml::encode($row['roi']),0,'.','') . '%';
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }

    public function offerDataAction(){
        $campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
        $sql_query = "SELECT * FROM bt_c_statcache c JOIN bt_u_offers o ON (o.offer_id = c.meta3) ";
        $sql_query .= "WHERE c.user_id='".$mysql['user_id']."' AND c.type='stats' AND c.meta3>0 ";
        $result = DB::getRows($sql_query);

        $total = "SELECT sum(clicks) FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' AND type='stats' AND (meta3 is null or meta3=0)";
        $camp_clicks = DB::getVar($total);
        if($camp_clicks==null)
            $camp_clicks="0";

        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $leads = $row['leads'];
            $clicks = $row['clicks'];
            $arr[] = BTHtml::encode($row['offer_id'].": ".$row['name']); //Offer Name
            $arr[] = BTHtml::encode($row['clicks']); //lpclicks

            //lpctr
            if($camp_clicks>0){
                $lpctr = (($clicks/$camp_clicks)*100);
                $arr[] = number_format($lpctr,2,'.','') . '%';
            }else{
                $arr[] = "0.0%";
            }

            $arr[] = BTHtml::encode($row['leads']);

            //offercvr
            $offercvr = (($leads/$clicks)*100);
            $arr[] = number_format($offercvr,2,'.','') . '%';

            //lpcvr
            if($camp_clicks>0){
                $lpcvr = (($leads/$camp_clicks)*100);
                $arr[] = number_format($lpcvr,2,'.','') . '%';
            }else{
                $arr[] = "0.0%";
            }

            $arr[] = money_format('$%i', BTHtml::encode($row['epc']));
            $arr[] = money_format('$%i', BTHtml::encode($row['cpc']));
            $rev = $row['payout'] + $row['income'];

            //rev
            $arr[] = money_format('$%i', BTHtml::encode($rev));

            //cost
            $arr[] = money_format('$%i', BTHtml::encode($row['cost']));

            //profit
            if($row['payout']>0){
                $profit = $row['payout'] - $row['cost'];
                $arr[] = $this->formatMoney($profit);
            }else{
                $arr[] = $this->formatMoney($row['cost']);
            }

            //roi
            $arr[] = number_format(BTHtml::encode($row['roi']),0,'.','') . '%';
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }

    public function lpDataAction(){
        $campaign_id = @$_GET['campaign_id'];
        $mysql['user_id'] = DB::quote(getUserID());
        $sql_query = "SELECT * FROM bt_c_statcache c JOIN bt_u_landing_pages lp ON (lp.landing_page_id = c.meta4) ";
        $sql_query .= "WHERE c.user_id='".$mysql['user_id']."' AND c.type='stats' AND c.meta3=0 AND c.meta4>0 ";
        $result = DB::getRows($sql_query);

        $total = "SELECT sum(clicks) FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' AND type='stats' AND (meta3 is null or meta3=0)";
        $camp_clicks = DB::getVar($total);
        if($camp_clicks==null)
            $camp_clicks="0";

        $output = array(
            //"sEcho" => $sEcho,
            //"iTotalRecords" => $iTotal,
            //"iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($result as $row) {
            $arr = array();
            $leads = $row['leads'];
            $clicks = $row['clicks'];

            $arr[] = BTHtml::encode($row['landing_page_id'].": ".$row['name']); //LP Name
            $arr[] = BTHtml::encode($clicks);
            $arr[] = "0";//BTHtml::encode($row['lpviews']); No database

            $arr[] = BTHtml::encode($row['clicks']); //lpclicks

            //lpctr
            if($camp_clicks>0){
                $lpctr = (($clicks/$camp_clicks)*100);
                $arr[] = number_format($lpctr,2,'.','') . '%';
            }else{
                $arr[] = "0.0%";
            }

            $arr[] = BTHtml::encode($row['leads']);

            //offercvr
            $offercvr = (($leads/$clicks)*100);
            $arr[] = number_format($offercvr,2,'.','') . '%';

            //lpcvr
            if($camp_clicks>0){
                $lpcvr = (($leads/$camp_clicks)*100);
                $arr[] = number_format($lpcvr,2,'.','') . '%';
            }else{
                $arr[] = "0.0%";
            }

            $arr[] = money_format('$%i', BTHtml::encode($row['epc']));
            $arr[] = money_format('$%i', BTHtml::encode($row['cpc']));

            //rev
            $rev = $row['payout'] + $row['income'];
            $arr[] = money_format('$%i', BTHtml::encode($rev));

            //cost
            $arr[] = money_format('$%i', BTHtml::encode($row['cost']));

            //profit
            if($row['payout']>0){
                $profit = $row['payout'] - $row['cost'];
                $arr[] = $this->formatMoney($profit);
            }else{
                $arr[] = $this->formatMoney($row['cost']);
            }

            //roi
            $arr[] = number_format(BTHtml::encode($row['roi']),0,'.','') . '%';
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }

    public function formatMoney($value){
        if($value<0)
            return money_format('$%(i', BTHtml::encode($value));
        else
            return money_format('$%i', BTHtml::encode($value));
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
        $sql .= 'Limit 0,10';
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
            $arr[] = BTHtml::encode($row['click_throughs']); //Click Throughs -> Offer Clicks
            $arr[] = BTHtml::encode($row['leads']); //Leads

            //offercvr
            $offercvr = (($row['leads'] / $row['click_throughs'])*100);
            $arr[] = number_format(BTHtml::encode($offercvr),2,'.','') . '%'; //Conv% -> offercvr

            $arr[] = money_format('$%i', BTHtml::encode($row['payout'])); //Payout

            //$arr[] = number_format(BTHtml::encode($row['epc']),2,'.','') . '%'; //EPC
            $arr[] = money_format('$%i', BTHtml::encode($row['epc']));

            $arr[] = money_format('$%i', BTHtml::encode($row['income'])); //Income
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }
}