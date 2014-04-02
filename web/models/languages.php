<?php
/**
 * User: admin
 * Date: 2.4.2014
 * Time: 18:10
 */

class Languages {

	private $registry;

	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}

	public function listAvaibleLanguages() {
		$cache = $this->registry->db->cacheQuery("SELECT * FROM lang WHERE lng_enabled = 1");
		return $cache;
	}
}