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
        $sql_select = "SELECT ";
        $sql_total = "SELECT count(*) as total ";

        if(!empty($_POST['clickData'])) {
            foreach($_POST['clickData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                }
            }
        }

        if(!empty($_POST['campaignData'])) {
            foreach($_POST['campaignData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                }
            }
        }

        if(!empty($_POST['deviceData'])) {
            foreach($_POST['deviceData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                }
            }
        }

        $sql_select = trim($sql_select, ',');
        $sql_from =" FROM
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

        $sql_where =" WHERE ";
        $sql_where .="click.lead > 0 AND ";
        if($camp_id){
            $sql_where .="c.campaign_id = '$camp_id' AND ";
        }
        $sql_where .="c.deleted = 0 AND o.deleted = 0 AND c.user_id = '$user_id' ";
        $limit = "LIMIT ".intval($_POST['iDisplayStart']).",".intval($_POST['iDisplayLength']);

        $sql_count = $sql_total.$sql_from.$sql_where;
        $sql_query = $sql_select.$sql_from.$sql_where.$limit;

        $iTotal = DB::getVar($sql_count);
        $report_rows = DB::getRows($sql_query);

        $sEcho = $_POST['sEcho'];
        $output = array(
            "sEcho" => $sEcho,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );

        foreach($report_rows as $row => $innerArray){
            $arr = array();
            foreach($innerArray as $innerRow => $value){
                if($innerRow=="click_id"){
                    $arr[] = BTHtml::encode(base_convert($value,10,36));
                }else if($innerRow=="date"){
                    $arr[] = date('m/d/y g:ia',$value);
                }else if($innerRow=="referer_url"){
                    $url = BTHtml::encode($value);
                    if($url){
                        $parse = parse_url($url);
                        $arr[] = sprintf('<a href="%s" target="_new" title="Referer" class="tablelink">%s</a> ',$url,$parse['host']);
                    }else{
                        $arr[] = $url;
                    }
                }else if($innerRow=="ip_address"){
                    $arr[] = sprintf('<a target="_new" href="http://whois.arin.net/rest/ip/%s" class="tablelink">%s</a>',$value,$value);
                }else if($innerRow=="browser"){
                    if($value==""){ $arr[] = "No User Agent"; }
                    else{$arr[] = BTHtml::encode($value);}
                }else if($innerRow=="lead_time"){
                    $arr[] = date('m/d/y g:ia',$value);
                }else if($innerRow=="lifetime"){
                    $arr[] = date('m/d/y g:ia',$value);
                }else{
                    if($value=="")
                        $value ="Unknown";

                    $arr[] = BTHtml::encode($value);
                }
            }
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
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