<?php

class BTCache {
	protected $_name = '';
	protected $_handler = null;
	protected $_expire = 3600;
	
	public function __construct($name,$expire = 3600) {
		$this->_name = $name;
		$this->_expire = $expire;
	}
	
	public function handler($mode = 'w+') {
		if(!$this->_handler) {
			$this->_handler = fopen($this->location(),$mode);
		}
		
		return $this->_handler;
	}
	
	public function isExpired() {
		if(file_exists($this->location())) {
			if($time = filemtime($this->location())) {
				if(time() - $time > $this->_expire) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function location() {
		return BT_ROOT . '/bt-config/cache/' . $this->_name;
	}
	
	public function close() {
		if($this->_handler) {
			fclose($this->_handler);
			$this->_handler = null;
		}
	}
	
	public function clear() {
		$this->close();
		
		$this->handler('w+'); //this will clear it out!
	}

	public function write($string) {
		if($this->handler()) {
			fwrite($this->handler(),$string);
	
		}
	}
	
	public function read() {
		if(file_exists($this->location())) {
			return file_get_contents($this->location());
		}
		
		return '';
	}
}