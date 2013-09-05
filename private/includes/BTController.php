<?php

abstract class BTController {
	protected $_templateVars = array();
	protected $_loadPath = '';
	protected $_loadAction = '';
	protected $_navMenu = null;

	public function __construct() {}
	
	public function init() {}

	public function indexAction() {}
	
	public function setLoadPath($path) {
		$this->_loadPath = $path;
	}
	
	public function getUrl($action = '') {
		if($action) {
			return $this->_loadPath . '/' . $action;
		}
		
		return $this->_loadPath;
	}
	
	public function redirect($action = '') {
		header("Location: " . $this->getUrl($action));
		BTApp::end();
	}
	
	public function doAction($action,$params = array()) {
		$this->_loadAction = $action;
		
		if(!$this->_loadAction) {
			$this->_loadAction = '/';
		}
		
		if(!$action) {
			$this->indexAction();
			BTApp::end();
		}
		
		$tmp = $action . 'Action';
		
		if(method_exists($this,$tmp)) {
			$this->$tmp($params);
			BTApp::end();
		
		}
		
		if(IS_AJAX) {
			$this->ajaxAction($action,$params);
			BTApp::end();
		}
		
		error404();
	}
	
	public function render($view) {
		$this->loadTemplate("protected_header");
		$this->loadView($view);
		$this->loadTemplate("protected_footer");
	}
	
	public function loadView($view) {
		$file = BT_ROOT . '/private/views/' . $view . '.php';	
		
		if(!file_exists($file)) {
			throw new Exception("Could not import view: " . $view);
		}
		
		extract($this->_templateVars);
				
		require($file);
	}
	
	public function loadTemplate($template) {
		$file = BT_ROOT . '/private/views/layout/' . $template . '.php';
		
		if(!file_exists($file)) {
			throw new Exception("Could not load template: " . $template);
		}
		
		extract($this->_templateVars);
				
		require($file);
	}
	
	public function loadModel($model) {
		BTApp::importModel($model);
	}
	
	public function setVar($var,$val) {
		$this->_templateVars[$var] = $val;
	}
	
	public function setVars($vars) {
		$this->_templateVars = $vars;
	}
	
	public function ajaxAction($command = '',$params = array()) {
		
	}
	
	protected function setupUser() {
		$this->_navMenu = new NavMenu(BT_ROOT . '/private/config/horizontal_navmenu.xml');
		$this->_navMenu->setCurrent($this->_loadPath . '/' . $this->_loadAction);
		
		$this->setVar('navmenu',$this->_navMenu);
		
		BTAuth::require_user();
	}
	
	/**
	 * Use the currently loaded action as the nav menu's current item. Useful if
	 * the controller has various action under a submenu. 
	 */
	public function useActionAsCurrentNav() {
		$this->_navMenu->setCurrent($this->_loadPath . '/' . $this->_loadAction);
	}
}