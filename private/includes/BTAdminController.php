<?php

abstract class BTAdminController extends BTUserController {
	public function __construct() {
		parent::__construct();
	}
	
	public function init() {
		parent::init();
		
		BTAuth::require_admin();
	}
}