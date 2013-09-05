<?php

class NavMenu {
	protected $_items = null;
	protected $_curItem = '';
	
	public function __construct($xml_location) {
		$xml = simplexml_load_file($xml_location);
		
		$this->_items = $xml;
	}
	
	public function setCurrent($item) {
		$this->_curItem = '/' . trim($item,'/');
	}
	
	public function render($items = null,$root = false) {
		if($items === null) {
			$items = $this->_items;
			$root = true;
		}
	
		if($root) {
			echo '<nav><ul class="collapsible accordion">';
		}
		
		foreach($items as $item) {
			$attrs = $item->attributes();
		
			if(isset($attrs['restriction'])) {
				if(!$this->checkRestriction($attrs['restriction'])) {
					continue;
				}
			}
		
			$children = $item->children();
			
			$classes = "";
			
			echo '<li';
			
			if($this->checkIfChildIsCurrent($item)) {
				$classes .= " current";
			}
			
			if((string)$attrs['section']) {
				$classes .= " section";
			}
			
			echo ' class="' . $classes . '" ';
			
			echo '>';
						
			if(!isset($attrs['link'])) {
				$item->addAttribute('link','');
			}
			
			if((string)$attrs['section']) {
				echo (string)$attrs['label'];
			}
			else {
				echo '<a href="' . (string)$attrs['link'] . '">' . (string)$attrs['label'] . '</a>';
				
				if(count($children)) {
					echo '<ul>';
					$this->render($children,false);
					echo '</ul>';
				}
			}
			
			echo '</li>';
		}
		
		if($root) {
			echo '</ul></nav>';
		}
	}
	
	public function checkIfChildIsCurrent($item) {
		$attrs = $item->attributes();
		
		if(isset($attrs['restriction'])) {
			if(!$this->checkRestriction($attrs['restriction'])) {
				return false;
			}
		}
		
		//this item is current?
		if(isset($attrs['link'])) {
			$link = (string)$attrs['link'];
			
			if($link == $this->_curItem) {
				return true;
			}
		}
		else {
			//recursion
			$children = $item->children();
						
			foreach($children as $child) {
				$cur = $this->checkIfChildIsCurrent($child);
				
				if($cur) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function checkRestriction($restrict) {
		$not = false;
		
		$restrict = (string)$restrict;
		
		//First check if the restriction is the name of a function
		if(function_exists($restrict)) {
			return $restrict();
		}
		
		//then check constants
		if(substr($restrict,0,1) == '!') {
			$not = true;
			$restrict = substr($restrict,1);
		}
	
		if(defined($restrict)) {			
			$val = constant($restrict);
			
			if($not) {
				return (!$val);
			}
			else {
				return ($val);
			}
		}
	}
}