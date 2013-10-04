<?php

class ReportsController extends BTUserController {

    public function __construct() {
        require_once(BT_ROOT . '/private/includes/reporting/export.php');
    }

    public function indexAction() {
        $this->useActionAsCurrentNav();
        $this->setVar("title", "Custom Reports");
        $this->render("reports/index");
    }

    public function viewCustomReportAction() {
        $this->loadView('reports/view_custom_report');
    }

    public function customReportAction() {

        $camp_id = getArrayVar($_POST,'campaign_id');
        $user_id = DB::quote(getUserID());

        $sql_report = "SELECT ";
        $title_table = "";
        if(empty($_POST['clickData']) && empty($_POST['campaignData']) && empty($_POST['deviceData'])){
            echo "<tr><td><div id='errorData'>No options have been selected. To create a report, select from the options above and click 'Create Report' below.</div></td></tr>";
        }else{

            if(!empty($_POST['clickData'])) {
                foreach($_POST['clickData'] as $option){
                    $sql_report .="$option,";
                    $title_table .= "<th>".substr($option,2)."</th>";
                }
            }
            if(!empty($_POST['campaignData'])) {
                foreach($_POST['campaignData'] as $option){
                    $sql_report .="$option,";
                    $title_table .= "<th>".substr($option,2)."</th>";
                }
            }

            if(!empty($_POST['deviceData'])) {
                foreach($_POST['deviceData'] as $option){
                    $sql_report .="$option,";
                    $title_table .= "<th>".substr($option,2)."</th>";
                }
            }
            $sql_report = trim($sql_report, ',');

            $sql_report.=" FROM
                bt_u_campaigns AS c
                    JOIN
                bt_u_campaign_offers co ON (co.campaign_id = c.campaign_id)
                    JOIN
                bt_u_offers o ON (co.offer_id = o.offer_id)
                    JOIN
                bt_s_clicks click ON (click.campaign_id = c.campaign_id)
                    JOIN
                bt_s_clicks_site cs USING (click_id)
                    JOIN
                bt_s_clicks_advanced adv USING (click_id)
                    JOIN
                bt_s_ips ON (bt_s_ips.ip_id = adv.ip_id)
                    JOIN
                bt_s_device_data d ON (d.device_id = adv.platform_id)";

            $sql_report .=" WHERE ";
            if($camp_id){
                $sql_report .="c.campaign_id = '$camp_id' AND c.deleted = 0 AND ";
            }
            $sql_report .="c.user_id = '$user_id' limit 10 ";

            $report_rows = DB::getRows($sql_report);

            echo "<tr>".$title_table."</tr>";
            foreach($report_rows as $row => $innerArray){
                echo "<tr>";
                foreach($innerArray as $innerRow => $value){
                    echo "<td>" . $value . "</td>";
                }
                echo "</tr>";
            }
        }
    }

    public function breakdownAction() {
        $this->useActionAsCurrentNav();
        $this->setVar("title", "Breakdown");
        $this->render("reports/breakdown");
    }

    public function getBreakdownData() {
        $cols = array('time','clicks','leads','conv','payout','epc','cpc','income','cost','net','roi');
        $start = (int)$_GET['iDisplayStart'];
        $length = (int)$_GET['iDisplayLength'];

        if($start == 0) {
            runBreakdown(true);
        }

        $cnt = DB::getVar("select count(*) from bt_c_statcache WHERE user_id='".DB::quote(getUserID())."' and type='breakdown'");
        $breakdown_sql = "SELECT * FROM bt_c_statcache WHERE user_id='".DB::quote(getUserID())."' and type='breakdown' limit $start,$length";
        $breakdown_result = DB::getRows($breakdown_sql);
        $breakdown_type = BTAuth::user()->getPref('breakdown');

        foreach($breakdown_result as &$row) {
            $ex = explode('-',$row['time_from']);

            if ($breakdown_type == 'day') {
                $row['time'] = date('M d, Y', mktime(0,0,0,$ex[1],$ex[2],$ex[0]));
            } elseif ($breakdown_type == 'month') {
                $row['time'] = date('M Y', mktime(0,0,0,$ex[1],1,$ex[0]));
            } elseif ($breakdown_type == 'year') {
                $row['time'] = date('Y', mktime(0,0,0,1,1,$ex[0]));
            }
        }

        return array('data'=>$breakdown_result,'cols'=>$cols,'cnt'=>$cnt);
    }

    public function exportBreakdownAction() {
        doReportExport('breakdown',array($this,'getBreakdownData'),'csv','Time,Clicks,Leads,Conv %,Payout,EPC,CPC,Income,Cost,Net,ROI');
    }

    public function viewBreakdownAction() {
        $_POST['order'] = '';
        //show breakdown
        runBreakdown(true);
        //show real or filtered clicks
        $mysql['user_id'] = DB::quote(getUserID());
        $breakdown = BTAuth::user()->getPref('breakdown');
        //grab breakdown report
        $breakdown_sql = "SELECT * FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' and type='breakdown' ";
        $breakdown_result = DB::getRows($breakdown_sql);
        $this->setVar("breakdown",$breakdown);
        $this->setVar("breakdown_result",$breakdown_result);
        $this->loadView('reports/view_breakdown');
    }

}