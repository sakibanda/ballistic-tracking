<?php

class IncomeModel extends BTModel {
    public function tableName() {
        return 'bt_u_income';
    }

    public function pk() {
        return 'income_id';
    }

    public function filters() {
        return array('deleted'=>0);
    }

    public function deletedColumn() {
        return "deleted";
    }

    public function delete($flag = 0) {
        $this->deleted = 1;
        return $this->save(false,true);
    }

    public function rules() {
        return array(
            array('campaign_id','required',array('message'=>'Select a campaign','for'=>array('new'))),
            array('date','callback',array('func'=>array($this,'checkDuplicateDate'),'message'=>'An income for that date already exists','for'=>array('new'))),
            array('date','required',array('message'=>'Select a date','for'=>array('new'))),
            array('amount','required',array('message'=>'Enter an income amount','for'=>array('new')))
        );
    }

    public function relations() {
        return array(
            'campaign'=>array('CampaignModel','campaign_id',self::REL_ONE_ONE)
        );
    }

    public function beforeSave() {
        if($this->isNew()) {
            $this->user_id = BTAuth::user()->id();
        }
    }

    public function checkDuplicateDate() {
        if(self::getRow(array('conditions'=>
        array(
            'date'=>$this->date,
            'campaign_id'=>$this->campaign_id,
            'deleted'=>0
        )))) {
            return false;
        }
        return true;
    }

    public function duplicate($old_id,$campaign_id) {
        $campaign_id = DB::quote($campaign_id);
        $old_id = DB::quote($old_id);
        $query = "insert into " . $this->tableName() . " (campaign_id, date, amount)";
        $query .= "select '$campaign_id', date, amount from " . $this->tableName() . " where campaign_id='$old_id'";
        if(!DB::query($query)) {
            return false;
        }
        return true;
    }
}

?>