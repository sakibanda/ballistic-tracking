<?php

class BTValidator {
	protected $_rules = array();
	protected $_errors = array();
	protected $_ruleset = '';
	protected $_fields = array();
	protected $_owner = null;
	
	private $_ruleDefs = array(
		'optional'=>array('val'),
		'required'=>array('val','dependency'),
		'length'=>array('val','min','max'),
		'minlength'=>array('val','length'),
		'maxlength'=>array('val','length'),
		'min'=>array('val','min'),
		'max'=>array('val','max'),
		'email'=>array('val'),
		'url'=>array('val'),
		'number'=>array('val'),
		'digits'=>array('val'),
		'compare'=>array('val','to'),
		'callback'=>array('val','func')
	);
	
	public function __construct($owner) {
		$this->_owner = $owner;
	}
	
	/**
	 * Sets the validator's rule set. Used to differentiate between forms.
	 * 
	 * @param string $set The ruleset
	 */
	public function useRuleSet($set) {
		$this->_ruleset = $set;
	}
	
	/**
	 * Returns the current ruleset
	 * 
	 * @return string The current ruleset
	 */
	public function getRuleSet() {
		return $this->_ruleset;
	}
	
	/**
	 * Set rules for the validator
	 * 
	 * @param type $rules The rules to set
	 */
	public function setRules($rules) {
		$this->_rules = $rules;
	}
	
	/**
	 * Get the vaidator's rules
	 * 
	 * @return array
	 */
	public function getRules() {
		return $this->_rules;
	}
	
	/**
	 * Field is optional
	 * 
	 * $param mixed $val The value to check
	 * @return boolean
	 */
	public function optional($val) {
		return true;
	}
	
	/**
	 * Field is required
	 * 
	 * @param mixed $val The value to check
	 * @return boolean
	 */
	public function required($val) {	
		if($val === null) {
			return false;
		}
		
		if(strlen($val) == 0) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Length range
	 * 
	 * @param type $val Value to check
	 * @param type $min Min length
	 * @param type $max Max length
	 * @return boolean
	 */
	public function length($val,$min,$max) {
		if(!$this->minlength($val,$min)) {
			return false;
		}
		
		return $this->maxlength($val, $max);
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @param string $length Min length of the string
	 * @return boolean
	 */
	public function minlength($val,$length) {
		return (strlen($val) < $length) ? false : true;
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @param string $length Max length of the string
	 * @return boolean
	 */
	public function maxlength($val,$length) {
		return (strlen($val) > $length) ? false : true;
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @param int $min Min value
	 * @return boolean
	 */
	public function min($val,$min) {
		if($val < $min) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @param int $max Max value
	 * @return boolean
	 */
	public function max($val,$max) {
		if($val > $max) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @return boolean
	 */
	public function email($val) {
		return filter_var($val, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @return boolean
	 */
	public function url($val) {
		return filter_var($val, FILTER_VALIDATE_URL);
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @return boolean
	 */
	public function number($val) {
		return is_numeric($val);
	}
	
	/**
	 * 
	 * @param mixed $val The value to check
	 * @return boolean
	 */
	public function digits($val) {		
		return is_int($val);
	}
	
	/**
	 * Compare two fields
	 * 
	 * @param type $val Value to check
	 * @param type $to Field to compare to
	 * @return boolean
	 */
	public function compare($val,$to) {
		return ($val == $this->_fields[$to]);
	}
	
	/**
	 * Callback validation. Passes the owner of this validator (a model) as the second parameter. 
	 * 
	 * @param type $val The value to check
	 * @param type $func The function to fund
	 * @return boolean
	 */
	public function callback($val,$func) {		
		return call_user_func($func,$val,$this->_owner);
	}
	
	/**
	 * Validate the fields
	 * 
	 * @param array $fields
	 * @return boolean
	 */
	public function validate($fields) {
		$valid = true;
		$messages = array();
		
		$this->_fields = $fields;
		
		//Loop rules
		foreach($this->_rules as $rule) {
			$name = $rule[0];
			$rule_name = $rule[1];
			
			$extra = array();
			
			//has extra data?
			if(isset($rule[2])) {
				$extra = $rule[2];
			}
			
			//check field for rule
			$val = (isset($fields[$name])) ? $fields[$name] : null;
			
			$sets = (isset($extra['for'])) ? $extra['for'] : array();

			//no sets means always run the rule
			if($sets) {
				if(!$this->isRuleInCurrentSet($sets)) {
					continue;
				}
			}

			$extra['val'] = $val;

			$args = array();

			//get arguments for rule function
			$def = $this->_ruleDefs[$rule_name];
						
			foreach($def as $arg) {
				if(!isset($extra[$arg])) {
					$args[] = '';
				}
				else {
					$args[] = $extra[$arg];
				}
			}

			$ret = call_user_func_array(array($this,$rule_name),$args);

			if(!$ret) {
				if(!isset($extra['message'])) {
					$extra['message'] = 'Invalid entry: ' . $name;
				}

				$messages[] = $extra['message'];

				$valid = false;
			}
		}
		
		$this->_errors = $messages;
		
		return $valid;
	}
	
	/**
	 * Check if the rule is in the current ruleset
	 * 
	 * @param mixed $sets A string containing a single set, or an array of sets, that the rule to check is part of. 
	 * @return boolean
	 */
	public function isRuleInCurrentSet($sets) {
		if(!$sets) {
			return true; //always run the rule if there's no sets.
		}
		
		//supports array and non array (single) sets.
		if(!is_array($sets)) {
			$sets = array($sets);
		}
		
		foreach($sets as $set) {
			if($this->_ruleset == $set) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Return the validator's errors (or blank array on success)
	 * 
	 * @return array
	 */
	public function errors() {
		return $this->_errors;
	}
}