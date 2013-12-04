<?php

class DB {
	protected static $_link = null;
	protected static $_queryLog = array();
	protected static $_lastRowCount = 0;
	protected static $_columnDetails = array();
	
	public static function connect($host,$user,$pass,$schema) {

        try{

            self::$_link = new PDO("mysql:host=$host;dbname=$schema", $user, $pass);
		    //raise exception on DB errors;
		    self::$_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    //emulate prepared statements... until I can benchmark & stress test native prepares.
		    self::$_link->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);

        }catch(PDOException $e){
            // PHP Fatal Error. Second Argument Has To Be An Integer, But PDOException::getCode Returns A String.
            throw new PDOException($e);
            //throw new MyDatabaseException($e->getMessage(),$e->getCode());
        }
		//do UTC by default
		DB::query("SET time_zone='+00:00'");
		
		/*****HANDLE COLUMN CACHE AND LOOKUPS****/
		//schema cache - expire after 3600 seconds
		$cache = new BTCache('column_meta',3600);
		
		if(!$cache->isExpired()) {
			if($data = $cache->read()) {
				self::$_columnDetails = unserialize($data);
			}
		}
		
		
		if(!self::$_columnDetails) {
			self::$_columnDetails = DB::getRows("select * from information_schema.columns where table_schema='$schema'");
		
			$cache->write(serialize(self::$_columnDetails));
		}
		
		$cache->close();
		/*******END CACHE & LOOKUPS****/
	}
	
	/*
	Gets rows and returns then as associative arrays. If you 
	set $index then the main array will have its index set by
	the column name specified
	*/	
	public static function getRows($sql,$index = '') {
		$ret = DB::query($sql);
		
		if(is_bool($ret)) {
			return array($ret);
		}
		
		$rows = array();
		while($row = $ret->fetch(PDO::FETCH_ASSOC)) {
			if($index) {
				$rows[$row[$index]] = $row;
			}
			else {
				$rows[] = $row;
			}
		}
		
		return $rows;
	}
	
	/*
	Get a single row, return as associative array
	*/
	public static function getRow($sql) {
		$rows = DB::getRows($sql);
		
		if(!$rows) {
			return null;
		}
		
		return $rows[0];
	}
	
	/*
	Returns  the first column in a single row. Useful for count(*) queries
	*/
	public static function getVar($sql,$default = '') {
		$row = DB::getRow($sql);
		
		if(!$row) {
			return $default;
		}
		
		return array_shift($row);
	}
	
	/*
	Securely quote a string for the database
	*/
	public static function quote($str) {
		$quoted = self::$_link->quote($str);
		
		if(substr($quoted,0,1) == "'") {
			return substr($quoted,1,-1);
		}
	
		return self::$_link->quote($str);
	}
	
	/*
	Execute MySQL query. If it fails it will display the error page & log the error, automatically
	*/
	public static function query($sql) {			
		if(!($ret = self::$_link->query($sql))) {
			record_mysql_error($sql);
			
			return false;
		}
		
		self::$_lastRowCount = $ret->rowCount();
		
		self::$_queryLog[] = array('query'=>$sql,'rows'=>$ret->rowCount());
		
		return $ret;
	}
	
	/*
	Get last insert auto-increment ID
	*/
	public static function insertId() {
		return self::$_link->lastInsertId();
	}
	
	/*
	Returns rows affected by last update/delete/insert query
	*/
	public static function affectedRows() {
		return self::$_lastRowCount;
	}
	
	/*
	Returns last MySQL error. Should only be used by the logging system, and not in the main code
	*/
	public static function lastError() {
		$error = self::$_link->errorInfo();
				
		if($error && isset($error[2])) {
			return $error[2];
		}
		
		return '';
	}
	
	/*
	Starts a MySQL transaction
	*/
	public static function startTransaction() {
		self::query("start transaction");
	}
	
	/*
	Rollback the current transaction
	*/
	public static function rollback() {
		self::query("rollback");
	}
	
	/*
	Commits the current transaction
	*/
	public static function commit() {
		self::query("commit");
	}
	
	/*get query log*/
	public static function queryLog() {
		return self::$_queryLog;
	}
	
	/*Start select query builder*/
	public static function select() {
		return new DB_Select_Query();
	}
	
	/*Prepare a SQL statement*/
	public static function prepare($query) {
		self::$_queryLog[] = array('query'=>$query,'rows'=>0);
		
		return self::$_link->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	}
	
	/*Get columns for table*/
	public static function getColumns($table) {
		$cols = array();
		
		foreach(self::$_columnDetails as $col) {
			if($col['TABLE_NAME'] == $table) {
				$cols[$col['COLUMN_NAME']] = $col;
			}
		}
		
		return $cols;
	}
	
	public static function insert($table_name,$data,$limit_columns = array()) {
		if(!$data) {
			return false;
		}
		
		$query = '';
		
		$rawVals = array();
		
		foreach($data as $col=>$val) {		
			if($limit_columns && !isset($limit_columns[$col])) continue;
						
			$query .= "`$col`=?,";
			
			$rawVals[] = $val;
		}
		
		if(!$query) {
			return false;
		}
		
		$query = substr($query,0,-1);
		
		$sql = "insert into `" . $table_name . "` set $query";
		
		$st = DB::prepare($sql);
		
		if(!$st->execute($rawVals)) {
			return false;
		}
		
		return true;
	}
}

//Jeremy: Redo this
function record_mysql_error($sql)
{							 
	//record the mysql error
	$clean['mysql_error_text'] = DB::lastError(); 	
	
	BTApp::log("MySQL: SQL: $sql \n\nReported Error: " . $clean['mysql_error_text'],'db',BT_SYSLOG_ERROR);
}

function printQueryLog() {
	if(!BTAuth::user()->isAdmin()) {
		return;
	}
	
	if(IS_AJAX) {
		printQueryLogFirePhp();
		return;
	}

	echo '<table id="bt_query_log" cellpadding="0" cellspacing="0">';
	
	echo '<tr><th colspan="2"><h2>Query Log</h2></th></tr>';
	echo '<tr><th>Query</th><th>Affected Rows</th></tr>';
	
	$log = DB::queryLog();
	foreach($log as $entry) {
		echo '<tr><td>' . $entry['query'] . '</td><td>' . $entry['rows']  . '</td></tr>';
	}
	echo '</table>';
}

function printQueryLogFirePhp() {
	if(LIVE_SITE) {
		return;
	}
		
	$log = DB::queryLog();
	foreach($log as $entry) {
		BTApp::firelog($entry['query'] . ' - ' . $entry['rows']);
	}
}

class DB_Select_Query {
	protected $_cols = array('*');
	protected $_table = '';
	protected $_conditions = array();
	protected $_joins = array();
	protected $_group = '';
    protected $_like = '';
	protected $_order = '';
	protected $_offset = 0;
	protected $_limit = 10;
	
	public function cols($cols = array()) {
		$this->_cols = $cols;
		
		return $this;
	}
	
	public function from($table) {
		$this->_table = $table;
		
		return $this;
	}
	
	public function where($conditions = array()) {
		$this->_conditions = $conditions;
		
		return $this;
	}
	
	public function join($table,$onConditions = '') {
		$this->_joins[] = array('join',$table,$onConditions);
		
		return $this;
	}
	
	public function leftJoin($table,$onConditions = '') {
		$this->_joins[] = array('left join',$table,$onConditions);
		
		return $this;
	}
	
	public function rightJoin($table,$onConditions = '') {
		$this->_joins[] = array('right join',$table,$onConditions);
		
		return $this;
	}
	
	public function group($group) {
		$this->_group = $group;
		
		return $this;
	}
	
	public function order($order) {
		$this->_order = $order;
		
		return $this;
	}
	
	public function offset($offset) {
		$this->_offset = $offset;
		
		return $this;
	}
	
	public function limit($limit) {
		$this->_limit = $limit;

		return $this;
	}

    public function like($like) {
        $this->_like = $like;

        return $this;
    }
	
	public function run() {
		if(!$this->_cols || !$this->_table) {
			return null;
		}
		
		$query = "select ";
		
		//columns
		foreach($this->_cols as $sel) {
			if(strpos($sel,'*') === false) {
				$ex = explode('.',$sel);
				
				if(count($ex) == 1) {
					array_unshift($ex, 't');
				}
				
				$sel = '`' . join('`.`',$ex) . '`';
			}
			
			$query .= sprintf("%s,",$sel);
		}
		
		$query = substr($query,0,-1) . ' from `' . $this->_table . '` `t` ';
		
		//joins
		foreach($this->_joins as $join) {
			$ex = explode(' ',$join[1]);
			$join[1] = '`' . join('` `',$ex) . '`';
		
			$query .= ' ' . $join[0] . ' ' . $join[1];
			
			$query .= " on " . $join[2] . '';
		}
		
		$rawVals = array();
		
		//conditions
		if($this->_conditions) {
			$query .= " where ";
			
			if(is_array($this->_conditions)) {
				foreach($this->_conditions as $col=>$val) {			
					$ex = explode('.',$col);
					
					if(count($ex) == 1) {
						array_unshift($ex, 't');
					}
					
					$col = '`' . join('`.`',$ex) . '`';
						
					$query .= "$col=? and ";
					
					$rawVals[] = $val;
				}
				
				$query = substr($query,0,-4);

                if($this->_like) {
                    $query .= 'AND '.$this->_like;
                }
			}
			else {
				$query .= $this->_conditions . ' ';
			}
		}
		
		if($this->_group) {
			$query .= ' group by ' . $this->_group . ' ';
		}
		
		if($this->_order) {
			$query .= ' order by ' . $this->_order . ' ';
		}
		
		if($this->_limit) {
			$query .= sprintf(" limit %s,%s ",$this->_offset,$this->_limit);
		}
		
		//echo $query . "<br><br>";
		
		$st = DB::prepare($query);
		
		if(!($ret = $st->execute($rawVals))) {
			return array(false);
		}
		
		$rows = array();
		while($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}
		
		return $rows;
	}
}

class DB_Bulk_Insert {
	protected $_data = array();
	protected $_table = '';
	protected $_cols = array();
	
	public function __construct($table,$cols) {
		$this->_table = $table;
		$this->_cols = $cols;
	}
	
	//should be an indexed, NOT associative array. 
	//This also assumes everything is already quoted!!
	public function insert($data) {
		$this->_data[] = $data;
	}
	
	public function execute($max_per = 20) {
		$queue = array();
		$i = 0;
	
		while($i < count($this->_data)) {
			for(;$i < count($this->_data);$i++) {
				$queue[] = $this->_data[$i];
				
				if(count($queue) == $max_per) {
					break;
				}
			}
			
			$sql = "insert into " . $this->_table . " (";
			$sql .= implode(",",$this->_cols);
			$sql .= ") values ";
			
			$datas  = array();
			foreach($queue as $data) {
				$tmp = "(" . implode(",",$data) . ")";
				
				$datas[] = $tmp;
			}
			$sql .= implode(",",$datas);
			
			DB::query($sql);
			
			$queue = array();
			$i++;
		}
	}
}