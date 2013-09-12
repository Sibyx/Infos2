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
}
?>