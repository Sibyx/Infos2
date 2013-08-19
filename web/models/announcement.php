<?php
class Announcement {
	
	private $registry;
	private $id;
	private $created;
	private $createdFriendly;
	private $updated;
	private $updatedFriendly;
	private $ownerName;
	private $ownerId;
	private $title;
	private $text;
	private $valid;
	
	public function __construct(Registry $registry, $id = 0) {
		$this->registry = $registry;
		if ($id > 0) {
			$this->registry->getObject('db')->executeQuery("SELECT * FROM selectAnnouncement WHERE id_announcement = $id");
			if ($this->registry->getObject('db')->numRows() == 1) {
				$row = $this->registry->getObject('db')->getRows();
				$this->id = $id;
				$this->title = $row['ann_title'];
				$this->created = $row['ann_created'];
				$this->createdFriendly = $row['createdFriendly'];
				$this->updated = $row['ann_updated'];
				$this->updatedFriendly = $row['updatedFriendly'];
				$this->ownerName = $row['user_firstName'] . ' ' . $row['user_lastName'];
				$this->ownerId = $row['id_user'];
				$this->text = $row['ann_text'];
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
	
	public function setText($value) {
		$this->text = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function save() {
		if($this->registry->getObject('auth')->isLoggedIn()) {
			$data = array();
			if ($this->id > 0) {
				$data['ann_updated'] = date('Y-m-d H:i:s');
				$data['ann_text'] = $this->text;
				$data['ann_title'] = $this->title;
				return $this->registry->getObject('db')->updateRecords('announcements', $data, 'id_announcement = ' . $this->id);
			}
			else {
				$data['id_user'] = $this->registry->getObject('auth')->getUser()->getId();
				$data['ann_title'] = $this->title;
				$data['ann_text'] = $this->text;
				$data['ann_created'] = date('Y-m-d H:i:s');
				$data['article_updated'] = date('Y-m-d H:i:s');
				$result = $this->registry->getObject('db')->insertRecords('announcements', $data);
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