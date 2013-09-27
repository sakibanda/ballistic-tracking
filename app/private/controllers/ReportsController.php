<?php

class ReportsController extends BTUserController {
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



}