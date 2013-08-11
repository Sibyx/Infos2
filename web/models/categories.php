<?php
class Categories {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function listCategories() {
		$query = "SELECT * FROM categories";
		$cache = $this->registry->getObject('db')->cacheQuery($query);
		return $cache;
	}
}
?>