<?php
class suploRecord {
	
	private $registry;
	private $id;
    private $date;
	private $dateFriendly;
	private $owner;
	private $missing;
	private $hour;
	private $classes;
	private $classroom;
	private $note;
	private $subject;
	private $eventId;
	private $event;
	private $googleCalendarService;
	private $valid;

	public function __construct(Registry $registry, $id = 0) {
		$this->registry = $registry;
        $this->googleCalendarService = new Google_CalendarService($this->registry->getObject('google')->getGoogleClient());

        require_once(FRAMEWORK_PATH . 'libs/person/person.php');
		$this->owner = new Person;
		$this->missing = new Person;

		if ($id > 0) {
			$this->registry->getObject('db')->executeQuery("SELECT * FROM vwSuploRecord WHERE id_suplo = $id");
			if ($this->registry->getObject('db')->numRows() == 1) {
				$row = $this->registry->getObject('db')->getRows();
				
				$this->id = $id;
				$this->date = $row['sup_date'];
				$this->dateFriendly = $row['dateFriendly'];
				
				$this->owner->id = $row['id_user'];
				$this->owner->name = $row['usr_firstName'] . ' ' . $row['usr_lastName'];
				$this->owner->email = $row['usr_email'];
				$this->owner->calendarId = $row['usr_calendarSuplo'];
				
				$this->missing->nick = $row['sup_nick'];
				
				$this->hour = $row['sup_hour'];
				$this->classes = $row['sup_classes'];
				$this->classroom = $row['sup_classroom'];
				$this->subject = $row['sup_subject'];
                $this->note = $row['sup_note'];

				$this->eventId = $row['sup_eventId'];
				
				$nick = $this->missing->nick;
				$this->registry->getObject('db')->executeQuery("SELECT usr_email, usr_firstName, usr_lastName, id_user FROM user WHERE usr_nick = '$nick'");
				if ($this->registry->getObject('db')->numRows() == 1) {
					$row = $this->registry->getObject('db')->getRows();
					$this->missing->id = $row['id_user'];
					$this->missing->name = $row['usr_firstName'] . ' ' . $row['usr_lastName'];
					$this->missing->email = $row['usr_email'];
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
        $forbidden = array('registry', 'event', 'googleCalendarService');
		foreach($this as $field => $data) {
			if(!in_array($field, $forbidden)) {
				$result[$field] = $data;
			}
		}
		return $result;
	}

    public function setId($value) {
        $this->id = $value;
    }

    public function setOwner($value) {
        $value = $this->registry->getObject('db')->sanitizeData($value);
        $this->registry->getObject("db")->executeQuery("SELECT * FROM user WHERE usr_viewName = '$value'");
        if ($this->registry->getObject("db")->numRows() == 1) {
            $row = $this->registry->getObject("db")->getRows();
            $this->owner->id = $row['id_user'];
            $this->owner->name = $row['usr_firstName'] . ' ' . $row['usr_lastName'];
            $this->owner->email = $row['usr_email'];
            $this->owner->calendarId = $row['usr_calendarSuplo'];
        }
        else {
            $this->error = true;
        }
    }
	
	public function setMissing($value) {
		$this->missing->nick = $this->registry->getObject('db')->sanitizeData($value);
        $value = $this->registry->getObject('db')->sanitizeData($value);
		$this->registry->getObject("db")->executeQuery("SELECT * FROM user WHERE usr_nick = '$value'");
		if ($this->registry->getObject("db")->numRows() == 1) {
			$row = $this->registry->getObject("db")->getRows();
			$this->missing->id = $row['id_user'];
			$this->missing->name = $row['usr_firstName'] . ' ' . $row['usr_lastName'];
			$this->missing->email = $row['usr_email'];
			$this->missing->calendarId = $row['usr_calendarSuplo'];
		}
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
		$this->date = $value;
	}
	
	public function getClassesShort() {
        $result = substr($this->classes, 0, 3);
		if (strlen($this->classes) > 3) {
            return $result . ' ...';
        }
        else {
            return $result;
        }
	}
	
	
	public function save() {
        if ($this->id == 0) {

            $event = new Google_Event();
            $event->setSummary($this->hour . ". hodina - " .  $this->subject);
            $event->setLocation($this->classroom);

            $this->registry->getObject('db')->executeQuery("SELECT * FROM vwTimeRecord WHERE tmt_lesson = " . $this->hour);
            if ($this->registry->getObject('db')->numRows() == 1) {
                $row = $this->registry->getObject('db')->getRows();

                $startTime = new DateTime($this->date->format("Y-m-d"));
                $startTime->setTime($row['startHour'], $row['startMinute'], $row['startSecond']);

                $endTime = new DateTime($this->date->format("Y-m-d"));
                $endTime->setTime($row['endHour'], $row['endMinute'], $row['endSecond']);

                $start = new Google_EventDateTime();
                $start->setDateTime($startTime->format("c"));
                $event->setStart($start);

                $end = new Google_EventDateTime();
                $end->setDateTime($endTime->format("c"));
                $event->setEnd($end);

                $event->setDescription($this->classes . " namiesto " . $this->missing->nick);

                try {
                    $this->event = $this->googleCalendarService->events->insert($this->owner->calendarId, $event);
                    if ($this->event->getId() != '') {
                        $row = array();
                        $row['id_user'] = $this->owner->id;
                        $row['sup_nick'] = $this->missing->nick;
                        $row['sup_date'] = $this->date->format("Y-m-d");
                        $row['sup_hour'] = $this->hour;
                        $row['sup_classes'] = $this->classes;
                        $row['sup_note'] = $this->note;
                        $row['sup_classroom'] = $this->classroom;
                        $row['sup_subject'] = $this->subject;
                        $row['sup_eventId'] = $this->event->getId();
                        if ($this->registry->getObject('db')->insertRecords("suplo", $row)) {
                            $this->id = $this->registry->getObject('db')->lastInsertID();
                            return true;
                        }
                        else {
                            return false;
                        }
                    }
                    else {
                        $this->valid = false;
                        return false;
                    }
                }
                catch (Google_ServiceException $e) {
                    $this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Suplo', "Google Error " . $e->getCode() . ":" . $e->getMessage() . " pri suplovaní: " . $this->owner->name . " za " . $this->missing->name);
                }
                catch(Google_Exception $e) {
                    $this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Suplo', "Google Error " . $e->getCode() . ":" . $e->getMessage() . " pri suplovaní: " . $this->owner->name . " za " . $this->missing->name);
                }

            }
            else {
                $this->valid = false;
                return false;
            }
        }
        else {
            $update = array();
            $update['sup_classes'] = $this->classes;
            $update['sup_classroom'] = $this->classroom;
            $update['sup_note'] = $this->note;
            $update['sup_subject'] = $this->subject;
            if ($this->registry->getObject('db')->updateRecords("suplo", $update, 'id_suplo = ' . $this->id)) {
                return true;
            }
            else {
                return false;
            }
        }
	}

	public function remove() {
		if ($this->registry->getObject('db')->deleteRecords('suplo', "id_suplo = " . $this->id)) {
			$this->googleCalendarService->events->delete($this->owner->calendarId, $this->eventId);
			return true;
		}
		else {
			$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Suplo', 'SQL chyba pri pokuse o odstránenie suploRecord [' . $this->id . '] používateľom ' . $this->registry->getObject('auth')->getUser()->getFullName());
			return false;
		}
	}
}
?>