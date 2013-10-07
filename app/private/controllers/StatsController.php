<?php

class StatsController extends BTUserController {

    public function __construct() {
        $this->loadModel('OfferModel');
        require_once(BT_ROOT . '/private/includes/reporting/export.php');
    }

    public function indexAction() {
        $this->useActionAsCurrentNav();
        $this->setVar("title", "Campaign Stats");
        $this->render("stats/index");
    }



    public function dataAction(){
        $aColumns = array( 'offer_id', 'aff_network_id', 'name', 'payout', 'url', 'actions');

        $sort_col = $_GET['iSortCol_0'];
        $sort_dir = $_GET['sSortDir_0'];
        $sort = $aColumns[$sort_col]." ".$sort_dir;

        $offers = OfferModel::model()->getRows(
            array(
                'order'=>$sort
            )
        );
        $sEcho = $_GET['sEcho'];
        $iTotal = count($offers);
        $output = array(
            "sEcho" => $sEcho,
            "iTotalRecords" => 13,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($offers as $offer) {
            $arr = array();
            $arr[] = $offer->offer_id;
            $arr[] = $offer->network->name;
            $arr[] = $offer->name;
            $arr[] = $offer->payout;
            $arr[] = '<a class="button small grey tooltip" target="_blank" href="'.$offer->url.'"><i class="icon-external-link"></i></a>';
            $actions =  '<a href="/offers/edit?id='.$offer->offer_id.'" class="button small grey tooltip" title="Edit"><i class="icon-pencil"></i> Edit</a> ';
            $actions .= '<a rel="'.$offer->offer_id.'" class="button small grey tooltip delete_offer" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>';
            $arr[] = $actions;
            $output['aaData'][] = $arr;
        }
        echo json_encode($output);
    }
}