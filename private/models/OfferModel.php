<?php

class OfferModel extends BTModel {
	public function tableName() {
		return 'bt_u_offers';
	}
	
	public function pk() {
		return 'offer_id';
	}
	
	public function filters() {
		return array('deleted'=>0);
	}

    public function deletedColumn() {
        return "deleted";
    }

    public function delete($flag = 0) {
        if(!$flag || $flag == DELETE_BIT_SELF) {
            return parent::delete(1);
        }
        return false;
    }
	
	public function defaultValues() {
		return array(
			'payout'=>'0.00'	
		);
	}
	
	public function relations() {
		return array(
			'network'=>array('AffNetworkModel','aff_network_id',self::REL_ONE_ONE)
		);
	}
	
	public function rules() {
		return array(
			array('name','length',array('min'=>1,'max'=>50,'message'=>'Invalid network name', 'for'=>array('edit','new'))),
			array('aff_network_id','required',array('for'=>array('edit','new'))),
			array('url','length',array('min'=>1,'max'=>1000,'message'=>'Invalid campaign url', 'for'=>array('edit','new'))),
			//array('url','url',array('for'=>array('edit','new'))),
			
			array('payout','number',array('for'=>array('edit','new'))),
			
		);
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		if($this->isNew()) {
			$this->user_id = getUserID();
		}
	}
}

?>