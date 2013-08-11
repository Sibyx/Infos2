<?php
class Profile {
	
	private $registry;
	private $id;
	private $firstName;
	private $lastName;
	private $email;
	private $nick;
	private $valid;
	
	public function __construct(Registry $registry, $id) {
		$this->registry = $registry;
		$this->registry->getObject('db')->executeQuery("SELECT * id_user = $id");
		if ($this->registry->getObject('db')->numRows() == 1) {
			$this->valid = true;
			$data = $this->registry->getObject('db')->getRows();
			$this->id = $id;
			$this->firstName = $data['user_firstName'];
			$this->lastName = $data['user_lastName'];
			$this->email = $data['user_email'];
			$this->nick = $data['user_nick'];
		}
		else {
			$this->valid = false;
		}
	}
	
	public function isValid() {
		return $this->valid;
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
	
	public function getFullName() {
		return $this->firstName . ' ' . $this->lastName;
	}
	
	public function getID() {
		return $this->id;
	}	
}
?>