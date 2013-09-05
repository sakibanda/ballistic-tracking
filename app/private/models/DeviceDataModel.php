<?php

class DeviceDataModel extends BTModel {	
	public function tableName() {
		return 'bt_s_device_data';
	}
	
	public function pk() {
		return 'device_id';
	}
	
	public static function getNeededData($wurfl) {
		$data = array();
		
		$data['brand'] = $wurfl->getDeviceCapability('brand_name');
		$data['type'] = $wurfl->getDeviceCapability('model_name');
		$data['os'] = $wurfl->getDeviceCapability('os');
		$data['os_version'] = $wurfl->getDeviceCapability('os_version');
		$data['browser'] = $wurfl->getDeviceCapability('mobile_browser');
		$data['browser_version'] = $wurfl->getDeviceCapability('mobile_browser_version');
		
		return $data;
	}
	
	public static function getHash($wurfl) {
		$data = self::getNeededData($wurfl);
				
		return md5(join('',$data));
	}
	
	public static function getDeviceId($wurfl) {
		$data = self::getNeededData($wurfl);
		
		$hash = self::getHash($wurfl);
		
		$data['hash'] = $hash;
				
		if($row = self::model()->getRow(
				array(
					'conditions'=>array(
						'hash'=>$hash
						)
						)
			)) {
			
			return $row->get('device_id');
		}
		
		$mod = self::model();
		
		$mod->hash = $hash;
		$mod->brand = $data['brand'];
		$mod->type = $data['type'];
		$mod->os = $data['os'];
		$mod->os_version = $data['os_version'];
		$mod->browser = $data['browser'];
		$mod->browser_version = $data['browser_version'];
				
		$mod->save(true,true);
				
		return $mod->get('device_id');
	}
	
	public function delete($flag = 0) {
		
	}
}

?>