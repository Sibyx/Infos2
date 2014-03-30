<?php
class Announcements {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function listAnnouncements($offset = 0) {
		require_once(FRAMEWORK_PATH . 'include/pagination.php');
		$paginatedMembers = new Pagination($this->registry);
		$paginatedMembers->setLimit(5);
		$paginatedMembers->setOffset($offset);
		$query = "SELECT * FROM vwAnnouncements";
		$paginatedMembers->setQuery($query);
		$paginatedMembers->setMethod('cache');
		$paginatedMembers->generatePagination();
		return $paginatedMembers;
	}

    public function listActualAnnouncements() {
        require_once(FRAMEWORK_PATH . 'include/pagination.php');
        $paginatedMembers = new Pagination($this->registry);
        $paginatedMembers->setLimit(5);
        $paginatedMembers->setOffset(0);
        $query = "SELECT * FROM vwAnnouncements WHERE ann_deadline > '" . date("Y-m-d") . "'";
        $paginatedMembers->setQuery($query);
        $paginatedMembers->setMethod('cache');
        $paginatedMembers->generatePagination();
        return $paginatedMembers;
    }
}
?>