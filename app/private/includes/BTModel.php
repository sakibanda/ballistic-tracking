<?php

$btDefaultParams = array(
	'conditions'=>array(),
	'limit'=>0,
	'offset'=>0,
	'order'=>'',
	'group'=>'',
	'cols'=>array('*')
);

abstract class BTModel {
	const REL_ONE_ONE = 1;
	const REL_ONE_MANY = 2;
	
	protected $_orig = array();
	protected $_data = array();
	protected $_joinedModels = array();
	
	protected $_column_info = array();
	protected $_auto_inc = null;
	
	protected $_isNew = true;
	
	protected $_validator = null;
	
	protected $_diffs = array();
	
	protected static $_filtersEnabled = true;
	
	/**
	 *Return an array of default values
	 **/
	public function defaultValues() {
		return array();
	}
	
	/**
	 * A list of columns to ignore during object clones. 
	 */
	public function cloneIgnore() {
		return array();
	}
	
	/**
	 * Get a blank model instance
	 * 
	 * @return BTModel
	 */
	public static function model() {
		$class = get_called_class();
		
		$inst = new $class(null);
		
		return $inst;
	}
	
	/**
	 * Set a variable, to be saved later.
	 * 
	 * @param string $name The variable name
	 * @param mixed $val The variable value
	 */
	public function __set($name,$val) {
		$this->_data[$name] = $val;
		$this->_diffs[$name] = $val;
	}
	
	/**
	 * Returns all column info for the model's table. 
	 * 
	 * @return array
	 */
	public function columnInfo() {
		return $this->_column_info;
	}
	
	/**
	 * Returns the auto increment column name (if one exists). 
	 * 
	 * @return string
	 */
	public function autoIncName() {
		return $this->_auto_inc;
	}
	
	abstract public function tableName();
	abstract public function pk();
	
	public function filters() {
		return array();
	}
	
	public function relations() {
		return array();
	}
	
	public function rules() {
		return array();
	}
		
	public function useRuleSet($set) {
		$this->_validator->useRuleSet($set);
	}
	
	public function getRuleSet() {
		return $this->_validator->getRuleSet();
	}
	
	/**
	 * Remove all unsafe fields. A safe field is one that has validation for the current ruleset
	 * 
	 * @return array
	 */
	public function removeUnsafeFields() {
		$good = array();
		
		$data = $this->_diffs;
		
		$rules = $this->rules();
		
		foreach($rules as $rule) {
			$field = $rule[0];
			
			$extra = (isset($rule[2])) ? $rule[2] : array();
			
			$sets = (isset($extra['for'])) ? $extra['for'] : array();
				
			//no sets means always run the rule
			if($sets) {
				//check if the rule is included in the current set
				if(!$this->_validator->isRuleInCurrentSet($sets)) {
					continue;
				}
			}
			
			if(isset($data[$field])) {
				$good[$field] = $data[$field];
			}
		}
		
		$this->_diffs = $good;
	}
	
	public function validate($fields) {
		$this->beforeValidation();
		
		if(!$this->rules()) {
			$ret = true;
		}
		else {
			$this->_validator->setRules($this->rules());

			$ret = $this->_validator->validate($fields);
		}
		
		$this->afterValidation();
		
		return $ret;
	}
	
	public function getErrors() {
		return $this->_validator->errors();
	}

	public function __construct() {			
		$this->_column_info = DB::getColumns($this->tableName());
		
		foreach($this->_column_info as $col) {
			if($col['EXTRA'] == 'auto_increment') {
				$this->_auto_inc = $col['COLUMN_NAME'];
			}
		}
		
		$this->_validator = new BTValidator($this);
	}
	
	public function setData($data = array()) {		
		$use = array();
		
		if($data !== null) {
			
			if(is_array($data)) {
				$this->_isNew = false;
				
				foreach($data as $col=>$val) {
					if(isset($this->_column_info[$col])) {
						$use[$col] = $val;
					}
				}
			}
		}
		else {
			$data = array();
			$this->_isNew = true;
		}
	
		$this->_orig = $use;
		$this->_data = $use;
		
		$this->afterDataSet();
		
		return $this;
	}
	
	public function revert() {
		$this->_data = $this->_orig;
		$this->_diffs = array();
	}
	
	public function isNew() {
		return $this->_isNew;
	}
	
	public function __get($name) {		
		//properties first!
		if(isset($this->_data[$name])) {
			return $this->_data[$name];
		}
	
		//already linked?
		if(isset($this->_joinedModels[$name]) && $this->_joinedModels[$name]) {
			return $this->_joinedModels[$name];
		}
		
		//join it now?
		$rel = $this->relations();
				
		if(isset($rel[$name])) {
			$this->doJoin($name);
			
			return $this->_joinedModels[$name];
		}
		
		$default = $this->defaultValues();
		if(isset($default[$name])) {
			return $default[$name];
		}
		
		//eh, screw it. Return NULL
		return null;
	}
	
	public function doJoin($name) {
		$rel = $this->relations();
		$relationship = $rel[$name];
		
		$model = $relationship[0];
		$column = $relationship[1];
		$type = $relationship[2];
		$index = getArrayVar($relationship,3); //only for one_many
		$pre_join_callback = getArrayVar($relationship,4); //for one_many
		
		BTApp::importModel($model);
		
		if($type == self::REL_ONE_ONE) {
			$mod = new $model();
			$row = $mod->getRow(array(
				'conditions'=>array(
					$column=>$this->{$column}
				)
			));
							
			$this->addJoinedModel($name,$row);
		}
		else if($type == self::REL_ONE_MANY) { 
			$conditions = array(
				$column=>$this->{$column}
			);
			
			$mod = new $model();
			$rows = $mod->getRows(array(
				'conditions'=>$conditions
			));
			
			if($pre_join_callback) {
				$rows = call_user_func($pre_join_callback,$conditions,$rows);
			}
			
			if($index) {
				$real = array();
				
				foreach($rows as $row) {
					$real[$row->$index] = $row;
				}
				
				$rows = $real;
			}
			
			$this->addJoinedModel($name,$rows);
		}
	}
	
	public function addJoinedModel($name,$model) {
		$this->_joinedModels[$name] = $model;
	}
	
	public function id() {
		if(!is_array($this->pk())) {
			return $this->get($this->pk());
		}
		
		$ret = array();
		$pks = $this->pk();
		
		foreach($pks as $pk) {
			$ret[$pk] = $this->get($pk);
		}
		
		return $ret;
	}
	
	public function get($val) {
		return getArrayVar($this->_data,$val);
	}
	
	public function data() {
		return $this->_data;
	}
	
	public function save($validate = true,$ignore_unsafe = false) {
		if(!$this->_validator->getRuleSet()) {
			if($this->isNew()) {
				$this->useRuleSet('new');
			}
			else {
				$this->useRuleSet('edit');
			}
		}
		
		if(!$ignore_unsafe) {
			$this->removeUnsafeFields(); //remove unsafe fields before validation
		}
		
		if($validate) {		
			if(!$this->validate($this->_diffs)) {
				$this->revert();
				return false;
			}
		}
				
		$this->beforeSave();
				
		$data = $this->_diffs;
		
		if(!$data) {
			$this->revert();
			return false;
		}
				
		if($this->isNew()) {
			//BTApp::firelog("new " . get_class($this));
			
			$ret = $this->add($data);
		}
		else {
	
			$ret = $this->edit($data);
			
		}
				
		$this->afterSave();
		
		if(!$ret) {
			$this->revert();
		}
		
		return $ret;
	}
	
	private function edit($vals) {
		if($this->isNew()) {
			return false;
		}
		
		$query = "update `" . $this->tableName() . "` set ";
		
		if(!$vals) {
			return true;
		}
		
		$rawVals = array();
		
		foreach($vals as $col=>$val) {
			if(!isset($this->_column_info[$col])) continue;
						
			$query .= "`$col`=?,";
			
			$rawVals[] = $val;
			
			$this->_data[$col] = $val;
		}
		
		$query = substr($query,0,-1);
		
		$pk = $this->getPkWhere();
		
		$query .= " where " . $pk['where'];
		
		$st = DB::prepare($query);
		return $st->execute(array_merge($rawVals,$pk['vals']));
	}
	
	private function add($data) {
		if(!$this->isNew()) {
			return false;
		}
		
		if(!DB::insert($this->tableName(),$data,$this->_column_info)) {
			return false;
		}
				
		$auto_inc_col = $this->autoIncName();
		
		if($auto_inc_col) {
			$insert_id = DB::insertId();
			$data[$auto_inc_col] = $insert_id;
		}
		
		$this->setData($data);
		
		return true;
	}
	
	public function getRows($params = array()) {
		$rows = $this->getRowsAsArray($params);
		
		$classname = get_class($this);
		
		$objects = array();
		if($rows) {
			foreach($rows as $row) {
				$obj = new $classname();
				$obj->setData($row);
				
				$objects[] = $obj;
			}
		}
		
		return $objects;
	}
	
	public function getRowsAsArray($params = array()) {
		global $btDefaultParams;
		
		$params = array_merge($btDefaultParams,$params);
		
		if(self::$_filtersEnabled) {
			$params['conditions'] = array_merge($this->filters(),$params['conditions']);
		}
		
		$sel = DB::select()
			->cols($params['cols'])
			->from($this->tableName())
			->where($params['conditions'])
			->order($params['order'])
			->limit($params['limit'])
			->offset($params['offset'])
			->group($params['group']);

		$rows = $sel->run();
		
		return $rows;
	}
	
	public function getRow($params = array()) {	
		$rows = $this->getRows($params);
				
		if($rows) {
			return $rows[0];
		}
		
		return null;
	}
	
	public function getRowFromPk($id) {	
		//disallow mismatched composite & non composite
		if(is_array($this->pk()) && !is_array($id)) {
			return null;
		}
		else if(!is_array($this->pk()) && !is_array($id)) {
			$id = array($this->pk() => $id);
		}
				
		$row = $this->getRow(
			array(
				'conditions'=>$id
			)
		);
		
		return $row;
	}
	
	public function count($params = array()) {
		global $btDefaultParams;
		
		$params = array_merge($btDefaultParams,$params);
		
		if(self::$_filtersEnabled) {
			$params['conditions'] = array_merge($this->filters(),$params['conditions']);
		}
		
		$params['cols'] = array('count(*)');
		
		$sel = DB::select()
			->cols($params['cols'])
			->from($this->tableName())
			->where($params['conditions'])
			->order($params['order'])
			->group($params['group']);

		$rows = $sel->run();
		
		if($rows) {
			return array_shift($rows[0]);
		}
		
		return 0;
	}
	
	/**
	 * By default we dont want to delete stuff out of the database, unless you explicitly tell it to!
	 */
	public function delete($bit = 0) {
		if(!$this->deletedColumn()) {
			return true;
		}
		
		$query = "update " . $this->tableName() . " set `" . $this->deletedColumn() . "`=`" . $this->deletedColumn() . "` | ? ";
		
		$rawVals = array($bit);
		
		$pk = $this->getPkWhere();
		
		$query .= " where " . $pk['where'];
		
		$st = DB::prepare($query);
		return $st->execute(array_merge($rawVals,$pk['vals']));
	}
	public function deletedColumn() { return ''; }
	
	public function deleteAll($params = array(),$bit = 0) {
		if(!$this->deletedColumn()) {
			return true;
		}
		
		$query = "update " . $this->tableName() . " set `" . $this->deletedColumn() . "`=`" . $this->deletedColumn() . "` | ? ";
		
		if($params) {
			$query .= " where ";
			
			$vals = array();
			foreach($params as $name=>$val) {
				$vals[] = $val;
				
				$query .= "`$name`=? and ";
			}
			
			$query = substr($query,0,-4);
		}
		
		$vals[] = $bit;
		
		$st = DB::prepare($query);
		
		if(!$st) {
			return false;
		}
		
		return $st->execute($vals);		
	}
	
	public function clearJoin($join = '') {
		if(!$join) {
			$this->_joinedModels = array();
		}
		else {
			unset($this->_joinedModels[$join]);
		}
	}
	
	/**
	 * This REALLY deletes the row from the database. Be careful with this power!
	 * 
	 * @return boolean
	 */
	protected function _delete() {
		if($this->isNew()) {
			return false;
		}
		
		$pk = $this->getPkWhere();
		
		$st = DB::prepare("delete from " . $this->tableName() . " where " . $pk['where']);
		return $st->execute($pk['vals']);
	}
	
	/**
	 * Returns the PK conditional statements. Also supports composite PKs. 
	 * 
	 * @return string
	 */
	protected function getPkWhere() {
		$pks = $this->pk();
		$ids = $this->id();
		
		//single PK
		if(!is_array($pks)) {
			$ids = array($pks=>$ids);
			$pks = array($pks);
		}
				
		$pk_where = array();
		$rawVals = array();
		
		foreach($pks as $pk) {
			$pkval = $ids[$pk];

			$pk_where[] = sprintf(" %s=?",$pk);
			$rawVals[] = $pkval;
		}
						
		return array('where'=>join(' and ',$pk_where),'vals'=>$rawVals);
	}
	
	/**
	 * Returns a JSON string containing the model's data
	 * 
	 * @return string
	 */
	public function toJSON() {
		$data = $this->_data;
				
		return json_encode($data);
	}
	
	/**
	 * Clone object, set all NON primary data as a diff so it can be saved. Does NOT save the object. 
	 * Also, does NOT check unique columns. Only checks the primary.
	 * You may have to override validations and column checks on save for clones. Be careful!
	 */
	public function __clone() {
		$diffs = $this->_data;
				
		//remove PKs
		$pks = $this->pk();
		
		if(!is_array($pks)) {
			$pks = array($pks);
		}
		
		foreach($pks as $pk) {
			unset($diffs[$pk]);
		}
		
		foreach($this->cloneIgnore() as $col) {
			unset($diffs[$col]);
		}
		
		//finally, set data
		$this->_data = $diffs;
		$this->_orig = $diffs;
		$this->_diffs = $diffs;
		
		$this->_isNew = true;
	}
	
	/**
	 * Disable filters
	 */
	public static function disableFilters() {
		self::$_filtersEnabled = false;
	}
	
	/**
	 * Enable filters
	 */
	public static function enableFilters() {
		self::$_filtersEnabled = true;
	}
	
	public function getDiffs() {
		return $this->_diffs;
	}
	
	/*EVENTS*/
	public function beforeValidation() {}
	public function afterValidation() {}
	public function beforeSave() {}
	public function afterSave() {}
	public function afterDataSet() {}
}