<?php
/**
 * User: admin
 * Date: 18.12.2013
 * Time: 21:10
 */

class suploAPIDelegate {

	private $registry;
	/**
	 * @var apiController
	 */
	private $caller;

	public function __construct(Registry $registry, $caller) {
		$this->caller = $caller;
		$this->registry = $registry;
		$urlBits = $this->registry->url->getUrlBits();
		switch (isset($urlBits[2]) ? $urlBits[2] : '') {
			case 'record':
				$this->aRecord();
				break;
			case 'actualSuplo':
				$this->actualSuplo();
				break;
			default:
				$this->aSuplo($urlBits['2']);
				break;
		}
	}

	private function validateDate($date, $format = 'Y-m-d') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	private function aSuplo($date) {
		if ($_SERVER['REQUEST_METHOD'] == 'GET' && $this->validateDate($date)) {
			$this->getSuplo($date);
		}
		else {
			header('HTTP/1.0 405 Method Not Allowed');
			exit();
		}
	}

	private function getSuplo($date) {
		$date = new DateTime($date);
		$result = array();
		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		$suploRecords = $suploTable->getRecords();
		$result['suploRecords'] = array();
		foreach($suploRecords as $record) {
			$data = $record->toArray();
			$row = array();
			$row['hour'] = $data['hour'];
			$row['missing'] = $data['missing']->name;
			$row['classes'] = $data['classes'];
			$row['subject'] = $data['subject'];
			$row['classroom'] = $data['classroom'];
			$row['owner'] = $data['owner']->name;
			$row['note'] = $data['note'];
			$result['suploRecords'][] = $row;
		}
		$result['suploDate'] = $date->format('Y-m-d');
		header('HTTP/1.0 200 OK');
		echo json_encode($result);
		exit();
	}

	private function actualSuplo() {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->getActualSuplo();
		}
		else {
			header('HTTP/1.0 405 Method Not Allowed');
			exit();
		}
	}

	private function getActualSuplo() {
		$result = array();
		$this->registry->db->executeQuery("SELECT DISTINCT sup_date FROM suplo WHERE sup_date >= CURDATE()");
		if ($this->registry->db->numRows() > 0) {
			$result['empty'] = false;
			$result['days'] = array();
			while ($row = $this->registry->db->getRows()) {
				$result['days'][] = $row['sup_date'];
			}
		}
		else {
			$result['empty'] = true;
			$result['days'] = null;
		}
		header('HTTP/1.0 200 OK');
		echo json_encode($result);
		exit();
	}
}
?>