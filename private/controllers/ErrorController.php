<?php

class ErrorController extends BTController {
	public function Error404Action() {
		header('HTTP/1.0 404 Not Found');
		
		$this->setVar("title","Error 404");
		$this->loadTemplate("public_header");
		
		$this->loadView('error/404');
		
		$this->loadTemplate("public_footer");
		
		exit;
	}
}