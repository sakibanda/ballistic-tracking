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

        $camp_id = @$_POST['campaign_id'];
        $devices = @$_POST['deviceData'];

        if(isset($_GET['deviceData'])){
            //$user_id = DB::quote(getUserID());
            $sql_report = "SELECT ";
            $title_table = "";
            if(!empty($_POST['clickData'])) {
                foreach($_POST['clickData'] as $option){
                    $sql_report .="$option,";
                    $title_table .= "<th>".$option."</th>";

                }
            }
            if(!empty($_POST['campaignData'])) {
                foreach($_POST['campaignData'] as $option){
                    $sql_report .="$option,";
                    $title_table .= "<th>".$option."</th>";
                }
            }

            if(!empty($_POST['deviceData'])) {
                foreach($_POST['deviceData'] as $option){
                    $sql_report .="$option,";
                    $title_table .= "<th>".$option."</th>";
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

                        $sql_report .=" WHERE
                c.campaign_id = '18' AND c.deleted = 0 AND c.user_id = '2' limit 3   ";

            echo "SQL__: ".$sql_report;

            $report_rows = DB::getRows($sql_report);

            //echo mysql_field_name($report_rows, 1);

            echo "<table border='1'>";
            echo "<tr>".$title_table."</tr>";

            foreach($report_rows as $row){
                echo "<tr>";
                for($columns = 0;$columns<count($row);$columns++){
                    echo "<td>" . $row['brand'] . "</td>";
                }
                echo "</tr>";
            }

//        foreach($report_rows as $row){
//            echo "<tr>";
//                for($columns = 0;$columns<=count($row);$columns++){
//                    echo "<td>" . $row . "</td>";
//                }
//                echo "</tr>";
//         }
            echo "</table>";
        }

        //$_POST['order'] = '';
        /*
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
        */
        $this->loadView('reports/view_custom_report');
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