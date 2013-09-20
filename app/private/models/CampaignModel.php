<?php

BTApp::importModel('CampaignOptionModel');

class CampaignModel extends BTModel {	
	public function tableName() {
		return 'bt_u_campaigns';
	}
	
	public function pk() {
		return 'campaign_id';
	}
	
	public function delete($flag = 0) {		
		if(!$flag || ($flag == DELETE_BIT_SELF)) {
			return parent::delete(1);
		}
						
		return false;
	}
	
	public function deletedColumn() {
		return "deleted";
	}
	
	public function filters() {
		return array('deleted'=>0);
	}
	
	public function option($name) {
		if(!$this->options[$name]) {
			return null;
		}
		
		return $this->options[$name];
	}
	
	public function rules() {
		return array(
			array('traffic_source_id','required',array('message'=>'You must select a traffic source.','for'=>array('new'))),
			array('cloaker_id','optional',array('for'=>array('new','edit'))),
			array('name','required',array('message'=>'You must enter a name.','for'=>array('new','edit'))),
			
			array('slug','optional',array('for'=>array('new','edit'))),
			array('type','required',array('message'=>'Invalid type','for'=>array('new'))),
			
			array('rotate','optional',array('for'=>array('rotation')))
		);
	}
	
	public function relations() {
		return array(
			'options'=>array('CampaignOptionModel','campaign_id',self::REL_ONE_MANY,'name',array(CampaignOptionModel::model(),'setDefaultPreferences')),
			'cloaker'=>array('CloakerModel','cloaker_id',self::REL_ONE_ONE),
			'traffic_source'=>array('TrafficSourceModel','traffic_source_id',self::REL_ONE_ONE),
			'offers'=>array('CampaignOfferModel','campaign_id',self::REL_ONE_MANY),
			'landing_pages'=>array('CampaignLPModel','campaign_id',self::REL_ONE_MANY),
		);
	}
	
	public static function trackerWithData($offer_id,$landing_page_id,$traffic_source_id,$cloaker_id) {	
		return self::model()->getRow(array(
			'conditions'=>array(
				'offer_id'=>$offer_id,
				'landing_page_id'=>$landing_page_id,
				'traffic_source_id'=>$traffic_source_id,
				'cloaker_id'=>$cloaker_id
			)
		));
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		if($this->isNew()) {
			$this->user_id = getUserID();
		}
	}
	
	public function getUrl($cloaked = true) {
		$tracking_variables = array();

		$tracking_variables[$this->option('var_v1')->value] = $this->option('default_var_v1')->value;
		
		$tracking_variables[$this->option('var_v2')->value] = $this->option('default_var_v2')->value;
		
		$tracking_variables[$this->option('var_v3')->value] = $this->option('default_var_v3')->value;
		
		$tracking_variables[$this->option('var_v4')->value] = $this->option('default_var_v4')->value;
		
		$tracking_variables[$this->option('var_kw')->value] = $this->option('default_var_kw')->value;

		$tracking_variable_string = '';
		if($tracking_variables) {
			$tracking_variable_string = bt_build_http_query($tracking_variables);
		}
		
		if($this->cloaker_id && $cloaked) {						
			$cloaker = CloakerModel::model()->getRowFromPk($this->cloaker_id);
			
			if($cloaker) {
				return $cloaker->getUrl() . '/' . $this->slug . '?' . $tracking_variable_string;
			}
		}
		
		if($this->type == 1) {
			return 'http://' . getTrackingDomain() . '/tracker/lp/' . $this->get('campaign_id') . '?' . $tracking_variable_string;
		}
		
		return  'http://' . getTrackingDomain() . '/tracker/direct/' . $this->get('campaign_id') . '?' . $tracking_variable_string;
	}
	
	public function addOption($name,$value,$note) {
		BTApp::importModel('CampaignOptionModel');
		$opt = CampaignOptionModel::model();
		$opt->name = $name;
		$opt->value = $value;
		$opt->note = $note;
		$opt->campaign_id = $this->id();
		$opt->useRuleSet('new');
		return $opt->save();
	}
	
	public static function getPixelTypes() {
		return array(
			array('value'=>0,'label'=>'None'),
			array('value'=>1,'label'=>'Image'),
			array('value'=>2,'label'=>'Iframe'),
			array('value'=>3,'label'=>'Javascript'),
			array('value'=>4,'label'=>'Postback')
		);
	}
	
	public function randomizeVariableNames() {
		$opt = $this->options['var_kw'];
		
		if($opt->isNew()) {
			$opt->value = CampaignOptionModel::getUniqueVariableName(2,7);
		}
		
		$opt = $this->options['var_v1'];
		if($opt->isNew()) {
			$opt->value = CampaignOptionModel::getUniqueVariableName(2,7);;
		}
		
		$opt = $this->options['var_v2'];
		if($opt->isNew()) {
			$opt->value = CampaignOptionModel::getUniqueVariableName(2,7);
		}
	
		$opt = $this->options['var_v3'];
		if($opt->isNew()) {
			$opt->value = CampaignOptionModel::getUniqueVariableName(2,7);
		}
		
		$opt = $this->options['var_v4'];
		if($opt->isNew()) {
			$opt->value = CampaignOptionModel::getUniqueVariableName(2,7);
		}
	}

    public function duplicate($id) {
        DB::startTransaction();

        $query = "insert into " . $this->tableName() . " (name, user_id, traffic_source_id, rotate, cloaker_id, type)";
        $query .= "select concat(name,' copy') as name, user_id, traffic_source_id, rotate, cloaker_id, type from " . $this->tableName() . " ";
        $query .= "where campaign_id='" . DB::quote($id) . "'";
        if(!DB::query($query)) {
            DB::rollback();
            return false;
        }

        $new_id = DB::insertId();

        if(!$new_id) {
            DB::rollback();
            return false;
        }

        if(!CampaignOfferModel::model()->duplicate($id,$new_id)) {
            DB::rollback();
            return false;
        }

        if(!CampaignLPModel::model()->duplicate($id,$new_id)) {
            DB::rollback();
            return false;
        }

        if(!CampaignOptionModel::model()->duplicate($id,$new_id)) {
            DB::rollback();
            return false;
        }

        if(!SpendingModel::model()->duplicate($id,$new_id)) {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $new_id;
    }
}