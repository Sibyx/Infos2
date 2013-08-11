<?php
class Category {
	
	private $registry;
	private $id;
	private $title;
	private $numArticles;
	private $valid;
	
	public function __construct(Registry $registry, $id = 0) {
		$this->registry = $registry;
		if ($id > 0) {
			$this->registry->getObject('db')->executeQuery("SELECT * FROM categories WHERE id_category = $id");
			if ($this->registry->getObject('db')->numRows() == 1) {
				$row = $this->registry->getObject('db')->getRows();
				$this->id = $id;
				$this->title = $row['category_title'];
				$this->valid = true;
			}
			else {
				$this->valid = false;
			}
		}
		else {
			$this->id = 0;
			$this->valid = false;
		}
	}
	
	public function isValid() {
		return $this->valid;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function toArray() {
		$result = array();
		foreach($this as $field => $data) {
			if(!is_object($data) && !is_array($data)) {
				$result[$field] = $data;
			}
		}
		return $result;
	}
	
	public function setTitle($value) {
		$this->title = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function save() {
		if($this->registry->getObject('auth')->isLoggedIn()) {
			$data = array();
			if ($this->id > 0) {
				$data[] = $this->title;
				$data[] = $this->year;
				$data[] = $this->id;
				return $this->registry->getObject('db')->callRutine('editAlbum', $data);
			}
			else {
				$data[] = $this->registry->getObject('auth')->getUser()->getId();
				$data[] = $this->title;
				$data[] = $this->year;
				$result = $this->registry->getObject('db')->callRutine("insertAlbum", $data);
				$this->id = $this->registry->getObject('db')->lastInsertID();
				return $result;
			}
		}
		else {
			return false;
		}
	}
}
?>