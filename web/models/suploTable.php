<?php
class suploTable {

	private $registry;

	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}

	public function getTableByDay($date) {
		$this->registry->getObject('db')->executeQuery("SELECT id_suplo FROM suplo WHERE suplo_date = $date");
		if ($this->registry->getObject('db')->numRows() > 0) {
			require_once(FRAMEWORK_PATH . "models/suploRecord.php");
			$records = array();
			while ($row = $this->registry->getObject('db')->getRows()) {
				$suploRecord = new suploRecord($this->registry, $row['id_suplo']);
				$records[] = $suploRecord;
			}
			return $records;
		}
		else {
			return false;
		}
	}
}
?>