<?php
/*
 * 05.06.2013
 * Class User v2.0
 * Objekt prihlaseneho uzivatela prisposobeny Google API. Pouzivam Google OAuth v2. 
 * CHANGELOG:
 * 	- v1.0 [16.10.2012]: createTime
 *	- v1.1 [02.03.2013]: drobne upravy
 *	- v2.0 [05.06.2013]: prerobene pre Google Auth
*/
class User {
	private $id;
	private $firstName;
	private $lastName;
	private $email;
	private $registry;
	private $admin;
	private $calendarSuplo;
	private $nick;
	private $valid = false;

	public function __construct(Registry $registry, Google_UserinfoServiceResource $googleUserInfo) {
		$this->registry = $registry;
        $googleUserInfo->useObjects(true);
		$data = $googleUserInfo->get();
		$email = $data->getEmail();
		$sql = "SELECT * FROM user WHERE usr_email = '$email'";
		$this->registry->getObject('db')->executeQuery($sql);
		if ($this->registry->getObject('db')->numRows() == 1) {
			$row = $this->registry->getObject('db')->getRows();
			$this->id = $data->getId();
			$this->firstName = $data->getGiven_name();
			$this->lastName = $data->getFamily_name();
			$this->email = $data->getEmail();
			$this->admin = $row['usr_admin'];
			$this->calendarSuplo = $row['usr_calendarSuplo'];
			$this->nick = $row['usr_nick'];
			$this->valid = true;
			$_SESSION['token'] = $this->registry->getObject('google')->getGoogleClient()->getAccessToken();
		}
		else {
			$this->valid = false;
		}
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getFirstName() {
		return $this->firstName;
	}
	
	public function getFullName() {
		return $this->firstName . ' ' . $this->lastName;
	}
	
	public function getEmail() {
		return $this->email;
	}

	public function isValid() {
		return $this->valid;
	}
	
	public function isAdmin() {
		return $this->admin;
	}
}
?>