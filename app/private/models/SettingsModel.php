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
            array('keyId','optional',array('for'=>array('new','edit'))),
            array('domain','optional',array('for'=>array('new','edit')))
        );
    }

    public function beforeSave() {
        if($this->isNew()) {
            $this->user_id = BTAuth::user()->id();
        }
    }
}