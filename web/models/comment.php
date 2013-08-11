<?php
class Comment {
	
	private $registry;
	private $id;
	private $articleId;
	private $date;
	private $dateFriendly;
	private $ownerName;
	private $ownerEmail;
	private $ownerWebsite;
	private $ownerId;
	private $text;
	private $valid;
	
	public function __construct(Registry $registry, $id = 0) {
		$this->registry = $registry;
		if ($id > 0) {
			$this->registry->getObject('db')->executeQuery("SELECT comments.*, DATE_FORMAT(comments.comment_date, '%d. %m. %Y %H:%i') AS dateFriendly FROM comments WHERE comments.id_comment = $id");
			if ($this->registry->getObject('db')->numRows() == 1) {
				$this->valid = true;
				$data = $this->registry->getObject('db')->getRows();
				$this->id = $id;
				$this->articleId = $data['id_article'];
				$this->ownerId = $data['id_user'];
				$this->date = $data['comment_date'];
				$this->dateFriendly = $data['dateFriendly'];
				$this->ownerName = $data['comment_author'];
				$this->ownerEmail = $data['comment_email'];
				$this->text = $data['comment_message'];
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
	
	public function setOwnerName($value) {
		$this->ownerName = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setOwnerEmail($value) {
		$this->ownerEmail = $this->registry->getObject('db')->sanitizeData($value);
	}

	public function setOwnerWebsite($value) {
		$this->ownerWebsite = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setText($value) {
		$this->text = $this->registry->getObject('db')->sanitizeData($value);
	}
	
	public function setArticleId($value) {
		$this->articleId = intval($value);
	}
	
	public function save() {
		$data = array();
		if ($this->id == 0) {
			$data[] = $this->articleId;
			if ($this->registry->getObject('auth')->isLoggedIn()) {
				$data[] = $this->registry->getObject('auth')->getUser()->getId();
			}
			else {
				$data[] = 0;
			}
			$data[] = $this->ownerNick;
			$data[] = $this->ownerEmail;
			$data[] = $this->ownerWebsite;
			$data[] = $this->text;
			$this->registry->getObject('db')->callRutine("addComment", $data);
			$this->id = $this->registry->getObject('db')->lastInsertID();
		}
	}
}
?>