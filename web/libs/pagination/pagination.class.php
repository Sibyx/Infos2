<?php class Pagination {
	
	private $query = '';
	private $executedQuery = "";
	private $limit;
	private $offset = 0;
	private $cache;
	private $results;
	private $numRows;
	private $numRowsPage;
	private $numPages;
	private $isFirst;
	private $isLast;
	private $currentPage;
	private $method = 'query';
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function setQuery($sql) {
		$this->query = $sql;
	}
	
	public function setLimit($limit) {
		$this->limit = $limit;
	}
	
	public function setOffset($offset) {
		$this->offset = $offset;
	}
	//$method = cache|do
	public function setMethod($method) {
		$this->method = $method;
	}
	
	public function generatePagination() {
		$this->registry->getObject('db')->executeQuery($this->query);
		$this->numRows = $this->registry->getObject('db')->numRows();
		$limit = " LIMIT " . ($this->offset * $this->limit) . ", " . $this->limit;
		if ($this->limit == 0 || $this->limit == '') {
			$this->executedQuery = $this->query;
		}
		else {
			$this->executedQuery = $this->query . $limit;
		}
		$this->registry->firephp->log("[pagination::generatePagiation]: executedQuery:" . $this->executedQuery); //DEBUG
		if ($this->method == 'cache') {
			$this->cache = $this->registry->getObject('db')->cacheQuery($this->executedQuery);
		}
		elseif ($this->method == 'do') {
			$this->registry->getObject('db')->executeQuery($this->executedQuery);
			$this->results = $this->registry->getObject('db')->getRows();
		}
		if ($this->limit != 0) {
			$this->numPages = ceil($this->numRows / $this->limit);
		}
		else {
			$this->numPages = 1;
		}
		$this->isFirst = ($this->offset == 0) ? true : false;
		$this->isLast = (($this->offset + 1) == $this->numPages) ? true : false;
		$this->currentPage = ($this->numPages == 0) ? 0 : $this->offset + 1;
		$this->numRowsPage = $this->registry->getObject('db')->numRows();
		if ($this->numRowsPage == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function getCache() {
		return $this->cache;
	}
	
	public function getResults() {
		return $this->results;
	}
	
	public function getNumPages() {
		return $this->numPages;
	}
	
	public function isFirst() {
		return $this->isFirst;
	}
	
	public function isLast() {
		return $this->isLast;
	}
	
	public function getCurrentPage() {
		return $this->currentPage;
	}
	
	public function getNumRowsPage() {
		return $this->numRowsPage;
	}
}
?>