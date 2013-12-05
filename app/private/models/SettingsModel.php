<?php

class SettingsModel extends BTModel {
    public function tableName() {
        return 'bt_u_settings';
    }

    public function pk() {
        return 'settings_id';
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
            array('pass_key','optional',array('for'=>array('new','edit'))),
            array('user_id','required',array('message'=>"User ID required",'for'=>array('new'))),
            array('api_key','optional',array('for'=>array('new','edit'))),
            array('domain','optional',array('for'=>array('new','edit'))),
            array('buy_date','optional',array('for'=>array('new','edit'))),
            array('active','optional',array('for'=>array('new','edit'))),
            array('type','required',array('message'=>'Invalid type','for'=>array('new'))),
            array('recurrence','optional',array('for'=>array('new','edit'))),
        );
    }
    /*
    public function beforeSave() {
        if($this->isNew()) {
            if($this->user_id==null){
                $this->user_id = BTAuth::user()->id();
            }
        }
    }
    */
}