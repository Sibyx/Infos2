<?php
/**
 * User: admin
 * Date: 18.12.2013
 * Time: 21:10
 */

class APIDelegate {

	private $registry;
	/**
	 * @var apiController
	 */
	private $caller;

	public function __construct(Registry $registry, $caller) {
		$this->caller = $caller;
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getUrlBits();
		if (isset($urlBits[2])) {
			switch ($urlBits[2]) {
				case 'record':
					$this->aRecord();
					break;
				case 'actualSuplo':
					$this->actualSuplo();
					break;
				default:
					$this->caller->notFound();
					break;
			}
		}
		else {
			$this->aSuplo();
		}
	}

	private function aSuplo() {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->getSuplo($_GET['date']);
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
			$row['classes'] = $record->getClassesShort();
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
		$this->registry->getObject('db')->executeQuery("SELECT DISTINCT DATE_FORMAT(sup_date,'%d. %m. %Y') AS `dateFriendly` FROM suplo WHERE sup_date >= NOW()");
		if ($this->registry->getObject('db')->numRows() > 0) {
			$result['empty'] = false;
			$result['days'] = array();
			while ($row = $this->registry->getObject('db')->getRows()) {
				$result['days'][] = $row['dateFriendly'];
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