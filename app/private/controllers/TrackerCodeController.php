<?php

class TrackerCodeController extends BTController {

    public function __construct() {
		parent::__construct();
		
		$this->loadModel('ClickModel');
		$this->loadModel('ClickAdvancedModel');
		$this->loadModel('ClickSiteModel');
		$this->loadModel('CloakerModel');
		$this->loadModel('CampaignModel');
		$this->loadModel('CampaignOptionModel');
		$this->loadModel('LandingPageModel');
		$this->loadModel('OfferModel');
		$this->loadModel('CampaignOfferModel');
		$this->loadModel('ClickPassthroughModel');
		$this->loadModel('TrafficSourceModel');
		$this->loadModel('AffNetworkModel');
		$this->loadModel('CampaignLPModel');
		
		require_once(BT_ROOT . '/private/includes/traffic/link.php');
		require_once(BT_ROOT . '/private/includes/traffic/variables.php');
	}
	
	public function indexAction() {
		$this->setupUser();
		$this->useActionAsCurrentNav();
		$this->setVar("redirects",CloakerModel::model()->getRows());
		
		if(getArrayVar($_GET,'campaign_id')) {
			$campaign = CampaignModel::model()->getRowFromPk($_GET['campaign_id']);
		}else{
			$campaign = CampaignModel::model();
		}
		
		$this->setVar('campaign',$campaign);
		$this->setVar("title","Add A Campaign");
		$this->setVar('traffic_sources',TrafficSourceModel::model()->getRows());
		$campaign->randomizeVariableNames();
		$this->render("tracker/code");
	}
	
	public function generateTrackingLinkAction() {		
		if($_POST['campaign_id']) {
			$this->editCampaign();
		}else{
			$this->newCampaign();
		}
	}
	
	protected function newCampaign() {
		$this->setupUser();
		DB::startTransaction();
		$traffic_source_id = getArrayVar($_POST,'traffic_source_id');
		$cloaker_id = getArrayVar($_POST,'cloaker_id');
		$slug = getArrayVar($_POST,'slug');
		$name = getArrayVar($_POST,'name');
		$type = getArrayVar($_POST,'tracker_type');
		if(!$name){
			echo json_encode(array('message'=>'Please name your campaign'));
			DB::rollback();
			BTApp::end();
		}

		if(!$traffic_source_id){
			echo json_encode(array('message'=>"You must select a network account"));
			DB::rollback();
			BTApp::end();
		}
		
		$campaign = CampaignModel::model();
		$campaign->traffic_source_id = $traffic_source_id;
		$campaign->cloaker_id = $cloaker_id;
		$campaign->name = $name;
		
		if($cloaker_id) {
			if(!$slug) {
				echo json_encode(array('message'=>'Enter a URL slug'));
				DB::rollback();
				BTApp::end();
			}
			$campaign->slug = $slug;
		}else{
			$campaign->slug = '';
		}
		
		$offer_prepend = '';
		switch($type) {
			case 'lp':
				$campaign->type = 1;
				$offer_prepend = 'lp';
				break;
			case 'direct':
				$campaign->type = 2;				
				break;
		}
		$campaign->save();
				
		$position = 0;
		for($i = 0,$cnt = count($_POST[$offer_prepend . 'offer_name']);$i < $cnt;$i++) {

            if($_POST[$offer_prepend . 'offer_id'][$i]){
                $offer = OfferModel::model()->getRowFromPk($_POST[$offer_prepend . 'offer_id'][$i]);
            }else{
                $offer = OfferModel::model();
            }

			$offer->aff_network_id = $_POST[$offer_prepend . 'offer_aff_network_id'][$i];
			$offer->name = $_POST[$offer_prepend . 'offer_name'][$i];
			$offer->url = $_POST[$offer_prepend . 'offer_url'][$i];
			$offer->payout = $_POST[$offer_prepend . 'offer_payout'][$i];
								
			if(!$offer->save()) {
				echo json_encode(array('message'=>'Could not save offers'));
				DB::rollback();
				BTApp::end();
			}
			
			$campoffer = CampaignOfferModel::model();
			$campoffer->campaign_id = $campaign->id();
			$campoffer->offer_id = $offer->id();
			
			switch($type) {
				case 'lp': //Todo: add in LP offer rotation here. Same position, multiple entries & weights.
					$campoffer->position = $position;
					$campoffer->weight = 0;
					
					//$lp = LandingPageModel::model();
					$position++;
					break;
				case 'direct':
					$campoffer->position = 0;
					$campoffer->weight = $_POST['offer_weight'][$i];
					break;
			}
			
			if(!$campoffer->save()) {
				echo json_encode(array('message'=>'Could not save campaign offers'));
				DB::rollback();
				BTApp::end();
			}
		}
		
		switch($type) {
			case 'lp':
				for($i = 0,$cnt = count($_POST['lp_name']);$i < $cnt;$i++) {
					$lp = LandingPageModel::model();
					$lp->name = $_POST['lp_name'][$i];
					$lp->url = $_POST['lp_url'][$i];
					
					if(!$lp->save()) {
						echo json_encode(array('message'=>'Could not save LPs'));
						DB::rollback();
						BTApp::end();
					}
					
					$camp_lp = CampaignLPModel::model();
					$camp_lp->campaign_id = $campaign->id();
					$camp_lp->landing_page_id = $lp->id();
					$camp_lp->weight = $_POST['lp_weight'][$i];
					
					if(!$camp_lp->save()) {
						echo json_encode(array('message'=>'Could not save campaign LPs'));
						DB::rollback();
						BTApp::end();
					}
				}
				break;
		}
		
		foreach($_POST['opt'] as $name=>$val) {			
			if(!$campaign->addOption($name,$val,"")) {
				DB::rollback();
				echo json_encode(array('message'=>'Could not save options'));
				BTApp::end();
			}
		}

        for($j = 0,$cnt = count($_POST['variable_name']);$j < $cnt;$j++) {
            $name = $_POST['variable_name'][$j];
            $note = $_POST['variable_note'][$j];
            $pass_lp = $_POST['variable_lp'][$j];
            $pass_offer = $_POST['variable_offer'][$j];

            $name = 'pass_' . $name;
            if($campaign->type==1)
                $val = json_encode(array('lp'=>$pass_lp,'offer'=>$pass_offer));
            else
                $val = json_encode(array('offer'=>'1'));

            if(!$campaign->addOption($name,$val,$note)) {
                DB::rollback();
                echo json_encode(array('message'=>'Could not save Variables Passthroughs'));
                BTApp::end();
            }
        }
		
		DB::commit();
		echo json_encode(array('message'=>'1','campaign_id'=>$campaign->id()));
	}
	
	protected function editCampaign() {
		DB::startTransaction();
		
		if(!($campaign = CampaignModel::model()->getRowFromPk($_POST['campaign_id']))) {
			echo json_encode(array('message'=>'Could not save: invalid campaign ID'));
			DB::rollback();
			BTApp::end();
		}
		
		$campaign->name = $_POST['name'];
		$campaign->cloaker_id = $_POST['cloaker_id'];
		$campaign->slug = $_POST['slug'];
		$campaign->save();
		
		switch($campaign->type) {
			case 1:
				foreach($campaign->offers as $offer) {
					if(!in_array($offer->id(),$_POST['campaign_lpoffer_id'])) {
						$offer->delete();
					}
				}
				
				for($i = 0,$cnt = count($_POST['campaign_lpoffer_id']);$i < $cnt;$i++) {
					$id = $_POST['campaign_lpoffer_id'][$i];
					
					if(!$id) { //new
						$offer = OfferModel::model();
						$offer->aff_network_id = $_POST['lpoffer_aff_network_id'][$i];
						$offer->name = $_POST['lpoffer_name'][$i];
						$offer->url = $_POST['lpoffer_url'][$i];
						$offer->payout = $_POST['lpoffer_payout'][$i];
											
						if(!$offer->save()) {
							echo json_encode(array('message'=>'Could not add offers'));
							DB::rollback();
							BTApp::end();
						}
						
						$campoffer = CampaignOfferModel::model();
						$campoffer->campaign_id = $campaign->id();
						$campoffer->position = 0;
						$campoffer->weight = 0;
						$campoffer->offer_id = $offer->id();
						
						if(!$campoffer->save()) {
							echo json_encode(array('message'=>'Could not add campaign offers'));
							DB::rollback();
							BTApp::end();
						}
					}else{ //edit
						$campoffer = CampaignOfferModel::model()->getRowFRomPk($id);
						$offer = $campoffer->offer;
                        $offer->aff_network_id = $_POST['lpoffer_aff_network_id'][$i];
						$offer->name = $_POST['lpoffer_name'][$i];
						$offer->url = $_POST['lpoffer_url'][$i];
						$offer->payout = $_POST['lpoffer_payout'][$i];
						
						if(!$offer->save()) {
							echo json_encode(array('message'=>'Could not save offers'));
							DB::rollback();
							BTApp::end();
						}
					}
				}
				
				foreach($campaign->landing_pages as $lp) {
					if(!in_array($lp->id(),$_POST['campaign_lp_id'])) {
						$lp->delete();
					}
				}
				
				for($i = 0,$cnt = count($_POST['campaign_lp_id']);$i < $cnt;$i++) {
					$id = $_POST['campaign_lp_id'][$i];
					
					if(!$id) { //new
						$lp = LandingPageModel::model();
						$lp->name = $_POST['lp_name'][$i];
						$lp->url = $_POST['lp_url'][$i];
						
						if(!$lp->save()) {
							echo json_encode(array('message'=>'Could not add LPs'));
							DB::rollback();
							BTApp::end();
						}
						
						$camp_lp = CampaignLPModel::model();
						$camp_lp->campaign_id = $campaign->id();
						$camp_lp->landing_page_id = $lp->id();
						$camp_lp->weight = $_POST['lp_weight'][$i];
						
						if(!$camp_lp->save()) {
							echo json_encode(array('message'=>'Could not add campaign LPs'));
							DB::rollback();
							BTApp::end();
						}
					}else{ //edit
						$camp_lp = CampaignLPModel::model()->getRowFromPk($id);
						$camp_lp->weight = $_POST['lp_weight'][$i];
						
						if(!$camp_lp->save()) {
							echo json_encode(array('message'=>'Could not save campaign LPs'));
							DB::rollback();
							BTApp::end();
						}
						
						$lp = $camp_lp->landing_page;
						$lp->name = $_POST['lp_name'][$i];
						$lp->url = $_POST['lp_url'][$i];
						
						if(!$lp->save()) {
							echo json_encode(array('message'=>'Could not save LPs'));
							DB::rollback();
							BTApp::end();
						}
					}
				}
				
				break;
			case 2:
				foreach($campaign->offers as $offer) {
					if(!in_array($offer->id(),$_POST['campaign_offer_id'])) {
						$offer->delete();
					}
				}
				
				for($i = 0,$cnt = count($_POST['campaign_offer_id']);$i < $cnt;$i++) {
					$id = $_POST['campaign_offer_id'][$i];
					
					if(!$id) { //new
						$offer = OfferModel::model();
						$offer->aff_network_id = $_POST['offer_aff_network_id'][$i];
						$offer->name = $_POST['offer_name'][$i];
						$offer->url = $_POST['offer_url'][$i];
						$offer->payout = $_POST['offer_payout'][$i];
											
						if(!$offer->save()) {
							echo json_encode(array('message'=>'Could not save offers'));
							DB::rollback();
							BTApp::end();
						}
						
						$campoffer = CampaignOfferModel::model();
						$campoffer->campaign_id = $campaign->id();
						$campoffer->position = 0;
						$campoffer->weight = $_POST['offer_weight'][$i];
						$campoffer->offer_id = $offer->id();
						
						if(!$campoffer->save()) {
							echo json_encode(array('message'=>'Could not save offers'));
							DB::rollback();
							BTApp::end();
						}
					}
					else { //edit
						$campoffer = CampaignOfferModel::model()->getRowFRomPk($id);
						$campoffer->weight = $_POST['offer_weight'][$i];
						
						if(!$campoffer->save()) {
							echo json_encode(array('message'=>'Could not save offers'));
							DB::rollback();
							BTApp::end();
						}
						
						$offer = $campoffer->offer;
						$offer->name = $_POST['offer_name'][$i];
                        $offer->aff_network_id = $_POST['offer_aff_network_id'][$i];
						$offer->url = $_POST['offer_url'][$i];
						$offer->payout = $_POST['offer_payout'][$i];
						
						if(!$offer->save()) {
							echo json_encode(array('message'=>'Could not save offers'));
							DB::rollback();
							BTApp::end();
						}
					}
				}
				break;
		}
		
		foreach($_POST['opt'] as $name=>$val) {			
			$opt = $campaign->options[$name];			
			$opt->value = $val;
						
			if(!$opt->save()) {
				DB::rollback();				
				echo json_encode(array('message'=>'Could not save options'));
				BTApp::end();
			}
		}

        foreach($campaign->options as $option) {
            if(strpos($option->name,'pass_') === 0) {
                $option->delete();
            }
        }

        for($j = 0,$cnt = count($_POST['variable_name']);$j < $cnt;$j++) {
            $name = $_POST['variable_name'][$j];
            $note = $_POST['variable_note'][$j];
            $name = 'pass_' . $name;
            if($campaign->type==1){
                $pass_lp = $_POST['variable_lp'][$j];
                $pass_offer = $_POST['variable_offer'][$j];
                $val = json_encode(array('lp'=>$pass_lp,'offer'=>$pass_offer));
            }else{
                $val = json_encode(array('offer'=>'1'));
            }
            if(!$campaign->addOption($name,$val,$note)) {
                DB::rollback();
                echo json_encode(array('message'=>'Could not save Variables Passthroughs'));
                BTApp::end();
            }
        }
		
		DB::commit();
		echo json_encode(array('message'=>'2','campaign_id'=>$campaign->id()));
	}
	
	public function offerRowAction(){
		if(@$_GET['campaign_offer_id']) {
			$campoffer = CampaignOfferModel::model()->getRowFromPk($_GET['campaign_offer_id']);
		}else {
			$campoffer = CampaignOfferModel::model();
		}
        $this->setVar('campoffer',$campoffer);
        if(@$_GET['campaign_offer_type']!="lp"){
            $this->loadView('tracker/offer_row');
        }else{
            $this->loadView('tracker/lpoffer_row');
        }
	}

    public function offerExistingRowAction(){
        if(@$_GET['offer_id']) {
            $offer = OfferModel::model()->getRowFromPk($_GET['offer_id']);
        }else {
            $offer = OfferModel::model();
        }

        $campoffer = CampaignOfferModel::model();
        $campoffer->offer_id = $offer->id();

        $this->setVar('campoffer',$campoffer);
        if(@$_GET['offer_type']!="lp"){
            $this->loadView('tracker/offer_row');
        }else{
            $this->loadView('tracker/lpoffer_row');
        }
    }

    /* show the existing offers associated to Aff Network selected */
    public function offersByAffNetworkAction(){
        if(@$_GET['aff_network_id']){
            $campoffers = OfferModel::model()->getRows(
                array(
                    'conditions'=>array(
                        'aff_network_id'=>$_GET['aff_network_id']
                    ),
                    'order'=>'offer_id asc'
                )
            );
        }else{
            $campoffers = OfferModel::model()->getRows(
                array(
                    'order'=>'offer_id asc'
                )
            );
        }

        $arr = array();
        foreach($campoffers as $offer){
            $arr[] = array(
                'id'=>$offer->offer_id,
                'name'=>$offer->name
            );
        }
        echo json_encode($arr);
    }

    //Landing Page
	public function landingPageRowAction() {
		if(@$_GET['campaign_lp_id']) {
			$camplp = CampaignLPModel::model()->getRowFromPk($_GET['campaign_lp_id']);
		}else {
			$camplp = CampaignLPModel::model();
		}
		$this->setVar('camplp',$camplp);
		$this->loadView('tracker/landing_page_row');
	}

    //Landing Page
    public function variablePassRowAction(){
        $this->loadView('tracker/var_pass_row');
    }


    //Update allow duplicate pixel
    public function updatedeDuplicateAction(){
        if(isset($_POST['campaign_id'])){
            isset($_POST['allow_duplicate']) ? $allow=1:$allow=0;
            $sql = "UPDATE bt_u_campaigns as c SET c.allow_duplicate_conversion =".$allow." WHERE c.campaign_id =".$_POST['campaign_id'];
            DB::query($sql);
        }else{
            echo json_encode(array('message'=>'Error updating the duplicate conversion'));
        }
    }
}