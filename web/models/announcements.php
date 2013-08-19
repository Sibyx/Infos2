<?php
class Announcements {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function listAnnouncements($offset = 0) {
		require_once(FRAMEWORK_PATH . 'libs/pagination/pagination.class.php');
		$paginatedMembers = new Pagination($this->registry);
		$paginatedMembers->setLimit(5);
		$paginatedMembers->setOffset($offset);
		$query = "SELECT * FROM listAnnouncements";
		$paginatedMembers->setQuery($query);
		$paginatedMembers->setMethod('cache');
		$paginatedMembers->generatePagination();
		return $paginatedMembers;
	}
	
	public function listArticlesByYear($year, $offset = 0) {
		require_once(FRAMEWORK_PATH . 'libs/pagination/pagination.class.php');
		$paginatedMembers = new Pagination($this->registry);
		$paginatedMembers->setLimit(5);
		$paginatedMembers->setOffset($offset);
		$query = "SELECT articles.*, users.firstName, users.lastName, users.id_user, categories.* DATE_FORMAT(articles.article_date, '%d. %m. %Y o %H:%i') AS dateFriendly FROM articles LEFT JOIN users ON users.id_user = articles.id_user LEFT JOIN categories ON categories.id_category = articles.id_category WHERE EXTRACT(YEAR FROM articles.article_date) = $year ORDER BY articles.article_date DESC";
		$paginatedMembers->setQuery($query);
		$paginatedMembers->setMethod('cache');
		$paginatedMembers->generatePagination();
		return $paginatedMembers;
	}
	
	public function getArticleYears() {
		$query = "SELECT DISTINCT EXTRACT(YEAR FROM article_date) AS articleYear FROM articles ORDER BY article_date DESC";
		$cache = $this->registry->getObject('db')->cacheQuery($query);
		return $cache;
	}
}
?>