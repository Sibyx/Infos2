<?php
class Comments {

	private $registry;
	private $numComments;
	private $articleId;
	
	public function __construct(Registry $registry, $articleId) {
		$this->registry = $registry;
		$this->articleId = $articleId;
		$this->registry->getObject('db')->executeQuery("SELECT COUNT(id_comment) AS commentCount FROM comments WHERE id_article = $articleId");
		if ($this->registry->getObject('db')->numRows() > 0) {
			$row = $this->registry->getObject('db')->getRows();
			$this->numComments = $row['commentCount'];
		}
	}
	
	public function listComments($offset = 0) {
		require_once(FRAMEWORK_PATH . 'libs/pagination/pagination.class.php');
		$paginatedMembers = new Pagination($this->registry);
		$paginatedMembers->setLimit(100);
		$paginatedMembers->setOffset($offset);
		$query = "SELECT comments.*, DATE_FORMAT(comments.comment_date, '%d.%m.%Y %H:%i') AS dateFriendly FROM comments WHERE comments.id_article = " . $this->articleId . " ORDER BY comment_date DESC";
		$paginatedMembers->setQuery($query);
		$paginatedMembers->setMethod('cache');
		$paginatedMembers->generatePagination();
		return $paginatedMembers;
	}
	
	public function numComments() {
		return $this->numComments;
	}
	
	public function printCommentsNumber() {
		if ($this->numComments > 1 || $this->numComments == 0) {
			return $this->numComments . ' comments';
		}
		else {
			return $this->numComments . ' comment';
		}
	}
}
?>