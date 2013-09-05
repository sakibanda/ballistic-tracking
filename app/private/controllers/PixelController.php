<?php

class PixelController extends BTUserController {
	public function __construct() {
	}
	
	public function codeAction() {	
		$this->useActionAsCurrentNav();
	
		$this->setVar("title","Get Pixel Or Postback");
		
		$this->render("pixel/code");
	}
}