<?php

/**
 * Simple logging class
 */
class BTLogger {
	protected $_file = '';
	private $_fh = null;
	
	/**
	 * Initialize logger, and use the provided filename (relative to the bt-config/logs dir). If the file handler fails to open, the logger will continue to work (and not throw an error), however, nothing will be logged.
	 * 
	 * @param string $file
	 */
	public function __construct($file) {
		$log_dir = BT_ROOT . '/bt-config/logs/';
		
		//attempt to create log dir
		if(!file_exists($log_dir) || !is_dir($log_dir)) {
			mkdir($log_dir);
		}
		
		@$this->_fh = fopen(BT_ROOT . '/bt-config/logs/' . $file,'a+');
	}
	
	/**
	 * Logs a message.
	 * 
	 * @param string $message
	 */
	public function write($message) {
		if($this->_fh) {
			$message = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
			
			fwrite($this->_fh,$message);
		}
	}
	
	/**
	 * Close the file handler.
	 */
	public function close() {
		if($this->_fh) {
			fclose($this->_fh);
		}
	}
}

?>
