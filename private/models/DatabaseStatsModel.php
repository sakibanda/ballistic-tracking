<?php
class DatabaseStatsModel {
	public function getDatabaseRowCount() {
		global $dbname;
	
		return DB::getVar("SELECT SUM(TABLE_ROWS) 
						     FROM INFORMATION_SCHEMA.TABLES 
						     WHERE TABLE_SCHEMA = '" . $dbname . "'");
	}
	
	public function getDatabaseSize() {
		global $dbname;
	
		return DB::getVar("SELECT ((SUM(DATA_LENGTH+INDEX_LENGTH) / 1024) / 1024)
						     FROM INFORMATION_SCHEMA.TABLES 
						     WHERE TABLE_SCHEMA = '" . $dbname . "'");
	}
}