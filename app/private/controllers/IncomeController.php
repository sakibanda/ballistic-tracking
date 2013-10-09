<?php

class IncomeController extends BtUserController {

    public function __construct() {
        $this->loadModel('TrafficSourceModel');
        $this->loadModel('IncomeModel');
    }

    public function indexAction() {
        $success = false;
        $error = array();

        if(isset($_POST['amount'])) {
            $income = IncomeModel::model();
            $income->date = date('Y-m-d',strtotime($_POST['date']));
            $income->campaign_id = $_POST['campaign_id'];
            $income->amount = $_POST['amount'];

            $income->useRuleSet('new');
            if($income->save()) {
                $success = "Income saved";
            }
            else {
                $error = $income->getErrors();
            }
        }

        $campaigns = CampaignModel::model()->getRows(
            array(
                'order'=>'`campaign_id` ASC'
            )
        );

        $this->setVar("campaigns",$campaigns);
        $this->setVar("title","Update Income");
        $this->setVar("error",$error);
        $this->setVar("success",$success);
        $this->render("income/income");
    }

    public function ajaxAction($command = '',$params = array()) {
        switch($command) {
            case 'data_income':
                $start = getArrayVar($_GET,'iDisplayStart',0);
                $limit = getArrayVar($_GET,'iDisplayLength',10);

                $total_incomes = IncomeModel::model()->count();
                $incomes = IncomeModel::model()->getRows(array('order'=>' date desc, campaign_id asc '));

                //$sEcho = $_GET['sEcho'];
                $output = array(
                    //'sEcho'=>(int)$sEcho,
                    //'iTotalRecords'=>(int)$total_incomes,
                    //'iTotalDisplayRecords'=>(int)$total_incomes,
                    'aaData'=>array());

                foreach($incomes as $income) {
                    $arr = array();
                    $arr[] = $income->date;
                    $arr[] = $income->campaign_id . ' - ' . $income->campaign->name;
                    $arr[] = $income->amount;
                    $arr[] = '<input type="hidden" class="income_id" value="' . $income->income_id . '" /> <a href="#" class="delete_income button small grey tooltip"><i class="icon-remove"></i></a>';
                    $output['aaData'][] = $arr;
                }
                echo json_encode($output);
                break;
        }
    }

    public function postDeleteAction() {
        $id = $_POST['income_id'];
        $income = IncomeModel::model()->getRowFromPk($id);
        if($income) {
            if($income->delete()) {
                echo 1;
            }
        }
        return 1;
    }
}