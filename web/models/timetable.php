<?php
class Timetable {
	
	private $registry;
	private $current;
	private $next;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$this->registry->getObject('db')->executeQuery("SELECT * FROM getCurrentTimetable");
		$row = $this->registry->getObject('db')->getRows();
		$this->current = $row['label'];
		$currentId = $row['id_timetable'];
		$this->registry->getObject('db')->executeQuery("SELECT id_timetable FROM timetable ORDER BY id_timetable DESC");
		$row = $this->registry->getObject('db')->getRows();
		if ($currentId == $row['id_timetable']) {
			$nextId = 1;
		}
		else {
			$nextId = $currentId + 1;
		}
		$this->registry->getObject('db')->executeQuery("SELECT * FROM timetable WHERE id_timetable = $nextId");
		$row = $this->registry->getObject('db')->getRows();
		$this->next = $row['label'];
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
	
	public function getNext() {
		return $this->next;
	}
	
	public function getCurrent() {
		return $this->current;
	}	
}
?>