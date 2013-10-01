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
        $_POST['order'] = '';

/*        //show breakdown
        runBreakdown(true);

        //show real or filtered clicks
        $mysql['user_id'] = DB::quote(getUserID());
        $breakdown = BTAuth::user()->getPref('breakdown');

        //grab breakdown report
        $breakdown_sql = "SELECT * FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' and type='breakdown' ";
        $breakdown_result = DB::getRows($breakdown_sql);

        $this->setVar("breakdown",$breakdown);
        $this->setVar("breakdown_result",$breakdown_result);*/

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