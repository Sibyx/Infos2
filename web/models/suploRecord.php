<?php
class suploRecord {
	
	private $registry;
	private $id;
	private $date;
	private $dateFriendly;
	private $dateRaw;
	private $owner;
	private $missing;
	private $hour;
	private $classes;
	private $classroom;
	private $note;
	private $subject;
	private $valid;
	
	public function __construct(Registry $registry, $id = 0) {
		$this->registry = $registry;
		if ($id > 0) {
			$this->registry->getObject('db')->executeQuery("SELECT * FROM getSuploRecord WHERE id_suplo = $id");
			if ($this->registry->getObject('db')->numRows() == 1) {
				$row = $this->registry->getObject('db')->getRows();
				
				$this->id = $id;
				$this->date = $row['suplo_date'];
				$this->dateFriendly = $row['dateFriendly'];
				
				require_once(FRAMEWORK_PATH . 'libs/person/person.php');
				$this->owner = new Person;
				$this->owner->id = $row['id_user'];
				$this->owner->name = $row['user_firstName'] . ' ' . $row['user_lastName'];
				$this->owner->email = $row['user_email'];
				
				$this->missing = new Person;
				$this->missing->nick = $row['suplo_nick'];
				
				$this->hour = $row['suplo_hour'];
				$this->classes = $row['suplo_classes'];
				$this->classroom = $row['suplo_classroom'];
				$this->subject = $row['suplo_subject'];
				
				$nick = $this->missing->nick;
				$this->registry->getObject('db')->executeQuery("SELECT user_email, user_firstName, user_lastName, id_user FROM users WHERE user_nick = $nick");
				if ($this->registry->getObject('db')->numRows() == 1) {
					$row = $this->registry->getObject('db')->getRows();
					$this->missing->id = $row['id_user'];
					$this->missing->name = $row['user_firstName'] . ' ' . $row['user_lastName'];
					$this->missing->email = $row['user_email'];
					$this->valid = true;
				}
				else {
					$this->valid = false;
				}
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
	
	public function toArray() {
		$result = array();
		foreach($this as $field => $data) {
			if(!is_object($data) && !is_array($data)) {
				$result[$field] = $data;
			}
		}
		return $result;
	}
	
	public function setOwner($value) {
		$this->owner->id = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setMissing($value) {
		$this->missing->nick = $this->registry->getObject('db')->sanitizeData($value);
	}

	public function setHour($value) {
		$this->hour = intval($value);
	}
	
	public function setClassroom($value) {
		$this->classroom = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setClasses($value) {
		$this->classes = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setNote($value) {
		$this->note = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setSubject($value) {
		$this->subject = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setDate($value) {
		$time = strtotime($value);
		$this->date = date('Y-m-d',$time);
	}
	
	public function getClassesShort() {
		return substr($this->classes, 0, 3);
	}
	
	
	public function save() {
		$row = array();
		if ($this->id == 0) {
			$row['id_user'] = $this->owner->id;
			$row['suplo_nick'] = $this->missing->nick;
			$row['suplo_date'] = $this->date;
			$row['suplo_hour'] = $this->hour;
			$row['suplo_classes'] = $this->classes;
			$row['suplo_note'] = $this->note;
			$row['suplo_classroom'] = $this->classroom;
			$row['suplo_subject'] = $this->subject;
			if ($this->registry->getObject('db')->insertRecords("suplo", $row)) {
				$this->id = $this->registry->getObject('db')->lastInsertID();
				return true;
			}
			else {
				return false;
			}
		}
	}
}
?>