<?php
class Announcement {
	
	private $registry;
	private $id;
	private $created;
	private $createdFriendly;
	private $createdRaw;
	private $updated;
	private $updatedFriendly;
	private $updatedRaw;
	private $ownerName;
	private $ownerId;
	private $title;
	private $text;
    private $deadline;
	private $valid;
	
	public function __construct(Registry $registry, $id = 0) {
		$this->registry = $registry;
		if ($id > 0) {
			$this->registry->getObject('db')->executeQuery("SELECT * FROM vwAnnouncement WHERE id_announcement = $id");
			if ($this->registry->getObject('db')->numRows() == 1) {
				$row = $this->registry->getObject('db')->getRows();
				$this->id = $id;
				$this->title = $row['ann_title'];
				$this->created = $row['ann_created'];
				$this->createdFriendly = $row['createdFriendly'];
				$this->createdRaw = $row['ann_created'];
				$this->updated = $row['ann_updated'];
				$this->updatedFriendly = $row['updatedFriendly'];
				$this->updatedRaw = $row['ann_updated'];
				$this->ownerName = $row['usr_firstName'] . ' ' . $row['usr_lastName'];
				$this->ownerId = $row['id_user'];
				$this->text = $row['ann_text'];
                $this->deadline = new DateTime($row['ann_deadline']);
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
	
	public function isUpdated() {
		$created = new Datetime($this->created);
		$updated = new Datetime($this->updated);
		return ($updated > $created);
	}
	
	public function getId() {
		return $this->id;
	}

    public function toArray() {
        $result = array();
        $forbidden = array('registry');
        foreach($this as $field => $data) {
            if(!in_array($field, $forbidden)) {
                $result[$field] = $data;
            }
        }
        return $result;
    }
	
	public function setTitle($value) {
		$this->title = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setText($value) {
		$this->text = $this->registry->getObject('db')->sanitizeData($value);
	}

    public function setDeadline($value) {
        $this->deadline = new DateTime($value);
    }
	
	public function save() {
		if($this->registry->getObject('auth')->isLoggedIn()) {
			$data = array();
			if ($this->id > 0) {
                //compatibilityMode
                if ($this->registry->getSetting('compatibilityMode')) {
                    $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('compatibilityDB'));
                    $changes = array();
                    $changes['oznam_title'] = $this->title;
                    $changes['oznam_text'] = $this->text;
                    $changes['oznam_public'] = true;
                    $this->registry->getObject('db')->updateRecords('oznamy', $changes, 'id_oznam=' . $this->id);
                    $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('mainDB'));
                }
                $data['ann_deadline'] = $this->deadline->format("Y-m-d");
				$data['ann_updated'] = date('Y-m-d H:i:s');
				$data['ann_text'] = $this->text;
				$data['ann_title'] = $this->title;
				if ($this->registry->getObject('db')->updateRecords('announcement', $data, 'id_announcement = ' . $this->id)) {
					$this->registry->getObject('log')->insertLog('SQL', 'INF', 'Announcements', 'Upravený oznam "' . $this->title . '"[' . $this->id . ']');
					return true;
				}
				else {
					$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Announcements', 'SQL chyba pri pokuse o úpravu oznamu "' . $this->title . '"[' . $this->id . ']');
					return false;
				}
			}
			else {
                //compatibilityMode
                if ($this->registry->getSetting('compatibilityMode')) {
                    $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('compatibilityDB'));
                    $insert = array();
                    $insert['oznam_date'] = date('Y-m-d H:i:s');
                    $insert['oznam_title'] = $this->title;
                    $insert['oznam_text'] = $this->text;
                    $insert['oznam_public'] = false;
                    $this->registry->getObject('db')->insertRecords('oznamy', $insert);
                    $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('mainDB'));
                }


				$data['id_user'] = $this->registry->getObject('auth')->getUser()->getId();
				$data['ann_title'] = $this->title;
				$data['ann_text'] = $this->text;
				$data['ann_created'] = date('Y-m-d H:i:s');
				$data['ann_updated'] = date('Y-m-d H:i:s');
                $data['ann_deadline'] = $this->deadline->format("Y-m-d");
				if ($this->registry->getObject('db')->insertRecords('announcement', $data)) {
					$this->id = $this->registry->getObject('db')->lastInsertID();
					$this->registry->getObject('log')->insertLog('SQL', 'INF', 'Announcements', 'Vytvorený oznam "' . $this->title . '"[' . $this->id . ']');
					return true;
				}
				else {
					$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Announcements', 'SQL chyba pri pokuse o vytvorenie oznamu "' . $this->title . '"[' . $this->id . ']');
					return false;
				}
			}
		}
		else {
			return false;
		}
	}
	
	public function remove() {
        //compatibilityMode
        if ($this->registry->getSetting('compatibilityMode')) {
            $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('compatibilityDB'));
            $this->registry->getObject('db')->deleteRecords('oznamy', 'id_oznam = ' . $this->id);
            $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('mainDB'));
        }

        if ($this->registry->getObject('db')->deleteRecords('announcement', "id_announcement = " . $this->id)) {
            $this->registry->getObject('log')->insertLog('SQL', 'INF', 'Announcements', 'Odstránený oznam "' . $this->title . '"[' . $this->id . ']');
            return true;
        }
        else {
            $this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Announcements', 'SQL chyba pri pokuse o odstránenie oznamu "' . $this->title . '"[' . $this->id . ']');
            return false;
        }
	}
}
?>