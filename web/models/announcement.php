<?php
class Announcement extends Model{
	
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
	
	public function __construct(Registry $registry, $id = 0) {
		parent::__construct($registry);
		if ($id > 0) {
			$this->registry->db->executeQuery("SELECT * FROM vwAnnouncement WHERE id_announcement = $id");
			if ($this->registry->db->numRows() == 1) {
				$row = $this->registry->db->getRows();
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
	
	public function isUpdated() {
		$created = new Datetime($this->created);
		$updated = new Datetime($this->updated);
		return ($updated > $created);
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setTitle($value) {
		$this->title = $this->registry->db->sanitizeData($value);
	}
	
	public function setText($value) {
		$this->text = $this->registry->db->sanitizeData($value);
	}

    public function setDeadline($value) {
        $this->deadline = new DateTime($value);
    }
	
	public function save() {
		if($this->registry->auth->isLoggedIn()) {
			$data = array();
			if ($this->id > 0) {
                $data['ann_deadline'] = $this->deadline->format("Y-m-d");
				$data['ann_updated'] = date('Y-m-d H:i:s');
				$data['ann_text'] = $this->text;
				$data['ann_title'] = $this->title;
				if ($this->registry->db->updateRecords('announcement', $data, 'id_announcement = ' . $this->id)) {
					$this->registry->log->insertLog('SQL', 'INF', 'Announcements', 'Upravený oznam "' . $this->title . '"[' . $this->id . ']');
					return true;
				}
				else {
					$this->registry->log->insertLog('SQL', 'ERR', 'Announcements', 'SQL chyba pri pokuse o úpravu oznamu "' . $this->title . '"[' . $this->id . ']');
					return false;
				}
			}
			else {
				$data['id_user'] = $this->registry->auth->getUser()->getId();
				$data['ann_title'] = $this->title;
				$data['ann_text'] = $this->text;
				$data['ann_created'] = date('Y-m-d H:i:s');
				$data['ann_updated'] = date('Y-m-d H:i:s');
                $data['ann_deadline'] = $this->deadline->format("Y-m-d");
				if ($this->registry->db->insertRecords('announcement', $data)) {
					$this->id = $this->registry->db->lastInsertID();
					$this->registry->log->insertLog('SQL', 'INF', 'Announcements', 'Vytvorený oznam "' . $this->title . '"[' . $this->id . ']');
					return true;
				}
				else {
					$this->registry->log->insertLog('SQL', 'ERR', 'Announcements', 'SQL chyba pri pokuse o vytvorenie oznamu "' . $this->title . '"[' . $this->id . ']');
					return false;
				}
			}
		}
		else {
			return false;
		}
	}
	
	public function remove() {
        if ($this->registry->db->deleteRecords('announcement', "id_announcement = " . $this->id)) {
            $this->registry->log->insertLog('SQL', 'INF', 'Announcements', 'Odstránený oznam "' . $this->title . '"[' . $this->id . ']');
            return true;
        }
        else {
            $this->registry->log->insertLog('SQL', 'ERR', 'Announcements', 'SQL chyba pri pokuse o odstránenie oznamu "' . $this->title . '"[' . $this->id . ']');
            return false;
        }
	}
}
?>