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

        $time_predefined = getArrayVar($_POST,'time_predefined');
        $to = getArrayVar($_POST,'to');
        $from = getArrayVar($_POST,'from');
        $time = $this->grab_time($time_predefined,$from,$to);
        $start = DB::quote($time['from']);
        $end = DB::quote($time['to']);

        $camp_id = getArrayVar($_POST,'campaign_id');
        $traffic_source_id = getArrayVar($_POST,'traffic_source_id');
        $click_filter = getArrayVar($_POST,'click_filter');
        $cvr = getArrayVar($_POST,'cvr');
        $user_id = DB::quote(getUserID());
        $sql_select = "SELECT ";
        $sql_total = "SELECT count(*) ";

        $aColumns = array();

        if(!empty($_POST['clickData'])) {
            foreach($_POST['clickData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                    if($option == "c.time as date"){
                        array_push($aColumns,"c.time");
                    }else{
                        array_push($aColumns,$option);
                    }
                }
            }
        }

        if(!empty($_POST['campaignData'])) {
            foreach($_POST['campaignData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                    if($option == "cp.name as cName"){ array_push($aColumns,"cp.name"); }
                    else if($option == "o.name as oName"){ array_push($aColumns,"o.name"); }
                    else{ array_push($aColumns,$option); }
                }
            }
        }

        if(!empty($_POST['deviceData'])) {
            foreach($_POST['deviceData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                    array_push($aColumns,$option);
                }
            }
        }

        if(!empty($_POST['carrierData'])) {
            foreach($_POST['carrierData'] as $option){
                if($option=="carrier")
                    $sql_select .="'' as $option,";
                else
                    $sql_select .="$option,";
                array_push($aColumns,$option);
            }
        }

        if(!empty($_POST['tokenData'])) {
            foreach($_POST['tokenData'] as $option){
                if($option != ""){
                    $sql_select .="$option,";
                    if($option == "v1.var_value as v1"){ array_push($aColumns,"v1.var_value"); }
                    else if($option == "v2.var_value as v2"){ array_push($aColumns,"v2.var_value"); }
                    else if($option == "v3.var_value as v3"){ array_push($aColumns,"v3.var_value"); }
                    else if($option == "v4.var_value as v4"){ array_push($aColumns,"v4.var_value"); }
                    else{ array_push($aColumns,$option); }
                }
            }
        }

        $sort_col = $_POST['iSortCol_0'];
        $sort_dir = $_POST['sSortDir_0'];
        $sort = $aColumns[$sort_col]." ".$sort_dir;

        $sql_select = trim($sql_select, ',');
        $sql_from =" FROM bt_s_clicks c
        LEFT JOIN bt_s_clicks_site cs ON c.click_id = cs.click_id
        LEFT JOIN bt_s_clicks_advanced ca ON c.click_id = ca.click_id
        LEFT JOIN bt_s_device_data d ON ca.device_id = d.device_id
        LEFT JOIN bt_s_ips i ON (ca.ip_id = i.ip_id)
        LEFT JOIN bt_s_keywords k ON (ca.keyword_id = k.keyword_id)
        LEFT JOIN bt_g_geo_locations l ON (ca.location_id = l.location_id)
        LEFT JOIN bt_g_organizations org ON (ca.org_id = org.org_id) ";
        if(!empty($_POST['tokenData'])){
            $sql_from .="LEFT JOIN bt_s_variables v1 ON (ca.v1_id = v1.var_id)
            LEFT JOIN bt_s_variables v2 ON (ca.v2_id = v2.var_id)
            LEFT JOIN bt_s_variables v3 ON (ca.v3_id = v3.var_id)
            LEFT JOIN bt_s_variables v4 ON (ca.v4_id = v4.var_id) ";
        }
        $sql_from .="LEFT JOIN bt_u_campaigns cp ON c.campaign_id = cp.campaign_id
        LEFT JOIN bt_u_campaign_offers co ON (cp.campaign_id = co.campaign_id)
        LEFT JOIN bt_u_offers o ON (co.offer_id = o.offer_id)
        LEFT JOIN bt_u_traffic_sources ts ON (c.traffic_source_id = c.traffic_source_id)";

        $sql_where =" WHERE ";
        if($camp_id)
            $sql_where .="cp.campaign_id = '$camp_id' AND ";

        if($traffic_source_id)
            $sql_where .="ts.traffic_source_id = '$traffic_source_id' AND ";

        if($cvr)
            $sql_where .="c.lead > 0 AND ";

        if ($click_filter == 'real') { $sql_where .= " c.filtered='0' AND "; }
        $sql_where .="cp.deleted = 0 AND o.deleted = 0 AND ts.deleted=0 AND cp.user_id = '$user_id' ";
        $sql_where .="AND c.time >= '$start' and c.time <= '$end' ";
        $sql_group = " GROUP BY c.click_id";
        $sql_order = " ORDER BY ".$sort;
        $limit = " LIMIT ".intval($_POST['iDisplayStart']).",".intval($_POST['iDisplayLength']);
        $sql_count = $sql_total.$sql_from.$sql_where.$sql_group;
        $sql_query = $sql_select.$sql_from.$sql_where.$sql_group.$sql_order.$limit;

        $iTotal = DB::getRows($sql_count);
        $report_rows = DB::getRows($sql_query);

        $sEcho = $_POST['sEcho'];
        $output = array(
            "sEcho" => $sEcho,
            "iTotalRecords" => count($iTotal),
            "iTotalDisplayRecords" => count($iTotal),
            "aaData" => array()
        );

        $lead_val = "";
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
                }else if($innerRow=="lead"){
                    if($value=="0"){
                        $arr[] = "";
                        $lead_val="0";
                    }else{
                        $arr[] = BTHtml::encode($value);
                        $lead_val="";
                    }
                }else if($innerRow=="payout"){
                    if($lead_val=="0"){
                        $arr[] = "";
                    }else{
                        $arr[] = BTHtml::encode($value);
                    }
                }else if($innerRow=="lead_time"){
                    if($lead_val=="0"){
                        $arr[] = "";
                    }else{
                        $arr[] = date('m/d/y g:ia',$value);
                    }
                }else if($innerRow=="lifetime"){
                    if($lead_val=="0"){
                        $arr[] = "";
                    }else{
                        //$nowtime = time();
                        //$oldtime = $value;
                        $arr[] = $this->seconds2human($value);
                        //$arr[] = $this->time_elapsed($nowtime-$oldtime);//DD:HH:MM
                    }
                }else{
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

    public function grab_time($time_predefined,$time_from,$time_to) {
        if (($time_predefined == 'today') or ($time_from != '')) {
            $time['from'] = mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
            $time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));
        }

        if($time_predefined == 'yesterday') {
            $time['from'] = mktime(0,0,0,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400));
            $time['to'] = mktime(23,59,59,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400));
        }

        if($time_predefined == 'last7') {
            $time['from'] = mktime(0,0,0,date('m',time()-86400*7),date('d',time()-86400*7),date('Y',time()-86400*7));
            $time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));
        }

        if($time_predefined == 'last14') {
            $time['from'] = mktime(0,0,0,date('m',time()-86400*14),date('d',time()-86400*14),date('Y',time()-86400*14));
            $time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));
        }

        if($time_predefined == 'last30') {
            $time['from'] = mktime(0,0,0,date('m',time()-86400*30),date('d',time()-86400*30),date('Y',time()-86400*30));
            $time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));
        }

        if($time_predefined == 'thismonth') {
            $time['from'] = mktime(0,0,0,date('m',time()),1,date('Y',time()));
            $time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));
        }

        if($time_predefined == 'lastmonth') {
            $time['from'] = mktime(0,0,0,date('m',time()-2629743),1,date('Y',time()-2629743));
            $time['to'] = mktime(23,59,59,date('m',time()-2629743),getLastDayOfMonth(date('m',time()-2629743), date('Y',time()-2629743)),date('Y',time()-2629743));
        }

        if($time_predefined == '') {
            $time['from'] = strtotime($time_from);
            $time['to'] = strtotime($time_to);
        }


        $time['time_predefined'] = $time_predefined;
        return $time;
    }

    function time_elapsed($secs){
        $bit = array(
            //'y' => $secs / 31556926 % 12,
            'w' => $secs / 604800 % 52,
            'd' => $secs / 86400 % 7,
            'h' => $secs / 3600 % 24,
            'm' => $secs / 60 % 60,
            //'s' => $secs % 60
        );

        foreach($bit as $k => $v)
            if($v > 0)$ret[] = $v . $k;

        return join(' ', $ret);
    }

    function seconds2human($ss) {
        $m = floor(($ss%3600)/60);
        $h = floor(($ss%86400)/3600);
        $d = floor(($ss%2592000)/86400);
        return "$d days, $h hours, $m minutes";
    }

}