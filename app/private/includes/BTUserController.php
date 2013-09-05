<?php

/**
 * Simply automatically runs setupUser()
 */
abstract class BTUserController extends BTController {	
	public function __construct() {
		parent::__construct();
	}
	
	public function init() {
		parent::init();
	
		$this->setupUser();
	}
}