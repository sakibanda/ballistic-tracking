<?php

class OffersController extends BTUserController {

    public function __construct() {
		$this->loadModel('AffNetworkModel');
		$this->loadModel('OfferModel');
	}
	
	public function indexAction() {
		$this->setVar('title','My Offers');
		$this->render('offers/index');
	}

	public function listAction() {
        $aColumns = array( 'offer_id', 'aff_network_id', 'name', 'payout', 'url', 'actions');
        $colSearchs = array('name', 'payout');
        $sort_col = $_GET['iSortCol_0'];
        $sort_dir = $_GET['sSortDir_0'];
        $sort = $aColumns[$sort_col]." ".$sort_dir;

        $like = "";
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
            for ( $i=0 ; $i<count($colSearchs) ; $i++ ){
                $like .= $colSearchs[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch'])."%' OR ";
            }
            $like = substr_replace( $like, "", -3 );
        }

		if($network_id = getArrayVar($_GET,'network')){
            $offers = OfferModel::model()->getRows(
                array(
                    'order'=>$sort,
                    'limit'=>intval($_GET['iDisplayLength']),
                    'offset'=>intval($_GET['iDisplayStart']),
                    //'like'=>$like,
                    'conditions'=>array('aff_network_id'=>$network_id)
                )
            );
		}else {
            $offers = OfferModel::model()->getRows(
                array(
                    'order'=>$sort,
                    'limit'=>intval($_GET['iDisplayLength']),
                    'offset'=>intval($_GET['iDisplayStart']),
                    'like'=>$like
                )
            );
		}
        $sEcho = $_GET['sEcho'];
        $iTotal = 0;
        if($network_id = getArrayVar($_GET,'network')){
            $iTotal = count(OfferModel::model()->getRows(array('conditions'=>array('aff_network_id'=>$network_id))));
        }else{
            $iTotal = count(OfferModel::model()->getRows());
        }
        $output = array(
            "sEcho" => $sEcho,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );
        foreach($offers as $offer) {
            $arr = array();
            if($offer->network!=null){


            $arr[] = $offer->offer_id;
            //$arr[] = $offer->network->name;
            $arr[] = $offer->network->name;
            $arr[] = $offer->name;
            $arr[] = $offer->payout;
            $arr[] = '<a class="button small grey tooltip" target="_blank" href="'.$offer->url.'"><i class="icon-external-link"></i></a>';
            $actions =  '<a href="/offers/edit?id='.$offer->offer_id.'" class="button small grey tooltip" title="Edit"><i class="icon-pencil"></i> Edit</a> ';
            $actions .= '<a rel="'.$offer->offer_id.'" class="button small grey tooltip delete_offer" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>';
            $arr[] = $actions;
                $output['aaData'][] = $arr;
            }
            }
        echo json_encode($output);
		//$this->setVar('offers',$offers);
		//$this->loadView('offers/view_offers');
	}

    public function saveAction(){
        $offer = OfferModel::model();
        $offer->user_id = getUserID();
        $offer->useRuleSet("new");
        $offer->aff_network_id = $_POST['aff_network_id'];
        $offer->name = $_POST['name'];
        $offer->url = $_POST['url'];
        $offer->payout = $_POST['payout'];
        if((strpos($offer->url,'http') !== 0)) {
            $offer->url = 'http://' . $offer->url;
        }
        if($offer->save()){
            echo "success";
        }
        BTApp::end();
    }

    public function editAction(){
        if(@$_GET['id']){
            $offer = OfferModel::model()->getRowFromPk($_GET['id']);
        }
        $this->setVar('offer',$offer);
        $this->setVar('title','Edit an Offer');
        $this->render('offers/edit');
    }

    public function updateAction(){
        $offer_id = $_POST['offer_id'];
        $offer = OfferModel::model()->getRowFromPk($offer_id);
        $offer->useRuleSet("edit");
        $offer->aff_network_id = $_POST['aff_network_id'];
        $offer->name = $_POST['name'];
        $offer->url = $_POST['url'];
        $offer->payout = $_POST['payout'];
        if((strpos($offer->url,'http') !== 0)) {
            $offer->url = 'http://' . $offer->url;
        }
        if($offer->save()){
            $this->setVar("success","Offer Edited");
            $this->redirect("index");
        }
        BTApp::end();
    }

	public function deleteAction(){
		if(@$_POST['offer_id']){
			$offer = OfferModel::model()->getRow(array(
				'conditions'=>array(
					'offer_id'=>$_POST['offer_id']
				)
			));
			if(!$offer) {
				echo 'Invalid ID';
				BTApp::end();
			}
			$offer->delete();
            $this->setVar("success","Offer Deleted");
		}
		echo $offer->name;
	}

	public function affNetworksAction() {
		$this->useActionAsCurrentNav();
		
		$add_success = false;
		$delete_success = false;
		$error = '';

		if (isset($_POST['name'])) {
			
			if (isset($_POST['aff_network_id']) && $_POST['aff_network_id']) { 
				$network = AffNetworkModel::model()->getRow(array(
					'conditions'=>array(
						'aff_network_id'=>$_POST['aff_network_id']
					)
				));
				
				$network->useRuleSet("edit");
			}
			else {
				$network = AffNetworkModel::model();
				$network->useRuleSet("new");
			}
			
			$network->name = $_POST['name'];
			
			if($network->save()) {
				$this->setVar("success","Network Saved");
			}
			else {
				$this->setVar('error',$network->getErrors());
			}
		}
		else if (isset ( $_GET ['delete_aff_network_id'] )) {			
			$network = AffNetworkModel::model()->getRow(array(
				'conditions'=>array(
					'aff_network_id'=>$_GET['delete_aff_network_id']
				)
			));
			
			if($network) {
				$network->delete();
				$this->setVar("success","Network Deleted");
			}
		}
		
		$id = getArrayVar($_GET,'aff_network_id',0);
		
		if(!($network = AffNetworkModel::model()->getRowFromPk($id))) {
			$network = AffNetworkModel::model();
		}
		
		$this->setVar("title","Affiliate Networks");
		
		$aff_networks = AffNetworkModel::model()->getRows(
			array(
				'order'=>'name asc'
			)
		);
	
		$this->setVar('network',$network);
		$this->setVar('aff_networks',$aff_networks);
		
		$this->render("offers/aff_networks");
	}
	
	public function deleteCampaignAction() {
		$id = $_GET['delete_offer_id'];
		
		$camp = OfferModel::model()->getRowFromPk($id);
		
		if(!$camp) {
			echo 'Invalid ID';
			BTApp::end();
		}
		
		$camp->delete();
		
		echo 0;
	}
	
	public function OffersAction() {
		$id = $_GET['aff_network_id'];
		
		if(!($network = AffNetworkModel::model()->getRowFromPk($id))) {
			$network = AffNetworkModel::model();
		}
		
		$this->setVar('network',$network);
		$this->loadView('offers/aff_campaigns');
	}
	
	public function viewAffiliateNetworksAction() {
		$aff_networks = AffNetworkModel::model()->getRows(
			array(
				'order'=>'name asc'
			)
		);

		$this->setVar('aff_networks',$aff_networks);
		
		$this->loadView("offers/view_aff_networks");
	}
}