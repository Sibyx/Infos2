<?php
	/*
	 * 14.04.2013
	 * Class Database v2
	 * Objekt ktory sprostredkovava pristup k DB
	 * CHANGELOG:
	 * 	- v1.0 [22.08.2012]: createTime
	 * 	- v1.1 [16.03.2013]: callRutine(), zmena detekcie cisiel cez preg_match
	 *	- v1.1 [28.03.2013]: lastInsertId(), teraz volanie cez MySQL
	 *	- v1.2 [14.04.2013]: Logger Class
	 *	- v1.2 [18.05.2013]: navratove hodnoty funkcii
	 *  - v2.0 [31.03.2014]: PDO
	*/
class Database {
	/**
	 * @var PDO
	 */
	private $connection;
	/**
	 * @var PDOStatement[]
	 */
	private $queryCache = array();
	private $dataCache = array();
	private $queryCounter = 0;
	/**
	 * @var PDOStatement
	 */
	private $last;
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function newConnection($host, $user, $password, $database) {
		try {
			$this->connection = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			$this->registry->log->insertLog('FILE', 'ERR', 'Database', $e->getCode() . ':' . $e->getMessage());
			return false;
		}
		return true;
	}
	
	public function executeQuery($query) {
		$this->registry->firephp->log("[mysqldb::executeQuery]: query: " . $query); //DEBUG
		try {
			$this->last = $this->connection->query($query);
		}
		catch (PDOException $e) {
			$this->registry->log->insertLog('FILE', 'ERR', 'Database', $e->getCode() . ':' . $e->getMessage());
			$this->registry->firephp->log("[mysqldb::executeQuery]: error!");
			return false;
		}
		$this->registry->firephp->log("[mysqldb::executeQuery]: executed!"); //DEBUG
		$this->queryCounter++;
		return true;
	}

	public function getRows() {
		return $this->last->fetch(PDO::FETCH_ASSOC);
	}
	
	public function deleteRecords($table, $condition, $limit = '') {
		$limit = ($limit == '') ? '' : ' LIMIT ' . $limit;
		$delete = "DELETE FROM $table WHERE $condition $limit";
		return $this->executeQuery($delete);
	}
	
	public function updateRecords($table, $changes, $condition) {
		$update = "UPDATE " . $table . " SET";
		foreach ($changes as $field => $value) {
			$value = $this->connection->quote($value);
			$update .= " " . $field . "=$value,";
		}
		$update = rtrim($update, ',');
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
			$values .= $this->connection->quote($v). ",";
		}
		
		$fields = rtrim($fields, ',');
		$values = rtrim($values, ',');
		$insert = "INSERT INTO $table ($fields) VALUES ($values)";
		return $this->executeQuery($insert);
	}
	
	public function callRutine($rutine, $data) {
        $values = "";
		foreach ($data as $v) {
			$v = $this->connection->quote($v);
			$values .= $v. ",";
		}
		$values = rtrim($values, ',');
		$call = "CALL $rutine($values)";
		return $this->executeQuery($call);
	}
	
	public function sanitizeData($value) {
		//return $this->connection->quote($value);
		return $value;
	}
	
	public function numRows() {
		return $this->last->rowCount();
	}
	
	public function cacheQuery($queryStr) {
		$this->registry->firephp->log("[mysqldb::cacheQuery]: query: " . $queryStr); //DEBUG
		try {
			$result = $this->connection->query($queryStr);
		}
		catch (PDOException $e){
			$this->registry->log->insertLog('FILE', 'ERR', 'Database', $e->getCode() . ':' . $e->getMessage());
			$this->registry->firephp->log("[mysqldb::cacheQuery]: error!");
			return false;
		}
		$this->queryCache[] = $result;
		$this->registry->firephp->log("[mysqldb::cacheQuery]: executed!"); //DEBUG
		return count($this->queryCache)-1;
    }
	
	public function numRowsFromCache($cache_id) {
		return $this->queryCache[$cache_id]->rowCount();
	}
	
	public function resultsFromCache($cache_id) {
		return $this->queryCache[$cache_id]->fetch(PDO::FETCH_ASSOC);
	}
	
	public function cacheData($data) {
		$this->dataCache[] = $data;
		return count($this->dataCache)-1;
	}
	
	public function dataFromCache($cache_id) {
		return $this->dataCache[$cache_id];
	}
	
	public function __deconstruct() {
		$this->connection = null;
	}
	
	public function lastInsertID() {
		$this->executeQuery("SELECT LAST_INSERT_ID() AS lastId");
		$row = $this->getRows();
		return $row['lastId'];
	}
}
?>