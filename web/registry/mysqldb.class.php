<?php
	/*
	 * 14.04.2013
	 * Class MySQLdb v1.2
	 * Objekt ktory sprostredkovava pristup k DB
	 * CHANGELOG:
	 * 	- v1.0 [22.08.2012]: createTime
	 * 	- v1.1 [16.03.2013]: callRutine(), zmena detekcie cisiel cez preg_match
	 *	- v1.1 [28.03.2013]: lastInsertId(), teraz volanie cez MySQL
	 *	- v1.2 [14.04.2013]: Logger Class
	 *	- v1.2 [18.05.2013]: navratove hodnoty funkcii
	*/
class MySQLdb {
	private $connections = array();
	private $activeConnection = 0;
	private $queryCache = array();
	private $dataCache = array();
	private $queryCounter = 0;
	private $last;
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function newConnection($host, $user, $password, $database) {
		$this->connections[] = new mysqli($host, $user, $password, $database);
		$connection_id = count($this->connections) - 1;
		if (mysqli_connect_errno()) {
			$this->registry->getObject('log')->insertLog('FILE', 'ERR', '[Connection error]: ' . $this->connections[$connection_id]->connect_error);
			trigger_error('Chyba pri pokuse o pripojenie: ' . $this->connections[$connection_id]->connect_error, E_USER_ERROR);
		}
		return $connection_id;
	}
	
	public function setActiveConnection($new) {
		$this->activeConnection = $new;
	}
	
	public function executeQuery($query) {
		$this->registry->firephp->log("[mysqldb::executeQuery]: query: " . $query); //DEBUG
		if (!$result = $this->connections[$this->activeConnection]->query($query)) {
			$this->registry->getObject('log')->insertLog('FILE', 'ERR', '[Query error]: ' . $this->connections[$this->activeConnection]->error);
			$this->registry->firephp->log("[mysqldb::executeQuery]: error!"); //DEBUG
			trigger_error('Chyba pri pokuse o vykonanie dotazu: ' . $query . ' - ' . $this->connections[$this->activeConnection]->error, E_USER_ERROR);
			return false;
		}
		else {
			$this->last = $result;
			$this->registry->firephp->log("[mysqldb::executeQuery]: executed!"); //DEBUG
			$this->queryCounter++;
			return true;
		}
	}

	public function getRows() {
		return $this->last->fetch_array(MYSQLI_ASSOC);
	}
	
	public function deleteRecords($table, $condition, $limit = '') {
		$limit = ($limit == '') ? '' : ' LIMIT ' . $limit;
		$delete = "DELETE FROM $table WHERE $condition $limit";
		return $this->executeQuery($delete);
	}
	
	public function updateRecords($table, $changes, $condition) {
		$update = "UPDATE " . $table . " SET";
		foreach ($changes as $field => $value) {
			if (is_numeric($value)) {
				$update .= " " . $field . "=$value,";
			}
			else {
				$update .= " " . $field . "='$value',";
			}
		}
		$update = substr($update, 0, -1);
		if ($condition != '') {
			$update .= " WHERE " . $condition;
		}
		return $this->executeQuery($update);
	}
	
	public function insertRecords($table, $data) {
		$fields = "";
		$values = "";
		
		foreach ($data as $f => $v) {
			$fields .= "$f,";
			if (preg_match('/^[1-9]*[0-9]$/', $v)) {
				$values .= $v. ",";
			}
			else {
				$values .= "'$v',";
			}
		}
		
		$fields = substr($fields, 0, -1);
		$values = substr($values, 0, -1);
		
		$insert = "INSERT INTO $table ($fields) VALUES ($values)";
		return $this->executeQuery($insert);
	}
	
	public function callRutine($rutine, $data) {
        $values = "";
		foreach ($data as $v) {
			if (preg_match('/^[1-9]*[0-9]$/', $v)) {
				$values .= $v. ",";
			}
			else {
				$values .= "'$v',";
			}
		}
		$values = substr($values, 0, -1);
		$call = "CALL $rutine($values)";
		return $this->executeQuery($call);
	}
	
	public function sanitizeData($value) {
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		
		if (version_compare(phpversion(), "4.3.0") == -1) {
			$value = $this->connections[$this->activeConnection]->escape_string($value);
		}
		else {
			$value = $this->connections[$this->activeConnection]->real_escape_string($value);
		}
		return $value;
	}
	
	public function numRows() {
		return $this->last->num_rows;
	}
	
	public function affectedRows() {
		return $this->last->affected_rows;
	}
	
	public function cacheQuery($queryStr) {
		$this->registry->firephp->log("[mysqldb::cacheQuery]: query: " . $queryStr); //DEBUG
		if( !$result = $this->connections[$this->activeConnection]->query($queryStr)) {
			$this->registry->getObject('log')->insertLog('FILE', 'ERR', '[Cache query error]: ' . $this->connections[$this->activeConnection]->error);
			$this->registry->firephp->log("[mysqldb::cacheQuery]: error: " . $this->connections[$this->activeConnection]->error); //DEBUG
			trigger_error('Chyba pri vykonani dotazu + jeho ulozeniu do cache: '.$this->connections[$this->activeConnection]->error, E_USER_ERROR);
			return -1;
		}
		else {
			$this->queryCache[] = $result;
			$this->registry->firephp->log("[mysqldb::cacheQuery]: executed!"); //DEBUG
			return count($this->queryCache)-1;
		}
    }
	
	public function numRowsFromCache($cache_id) {
		return $this->queryCache[$cache_id]->num_rows;
	}
	
	public function resultsFromCache($cache_id) {
		return $this->queryCache[$cache_id]->fetch_array(MYSQLI_ASSOC);
	}
	
	public function cacheData($data) {
		$this->dataCache[] = $data;
		return count( $this->dataCache )-1;
	}
	
	public function dataFromCache($cache_id) {
		return $this->dataCache[$cache_id];
	}
	
	public function __deconstruct() {
		foreach ($this->connections as $connection) {
			$connection->close;
		}
	}
	
	public function lastInsertID() {
		$this->executeQuery("SELECT LAST_INSERT_ID() AS lastId");
		$row = $this->getRows();
		return $row['lastId'];
	}
}
?>