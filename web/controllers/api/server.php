<?php
/**
 * User: admin
 * Date: 28.2.2014
 * Time: 23:18
 */
class serverAPIDelegate {

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
			case 'time':
				$this->getServerTime();
				break;
		}
	}

	private function getServerTime() {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$result = array();
			$serverTime = new DateTime();
			$result['serverTimeFormated'] = $serverTime->format("d. m. Y - H:i");
			$result['serverTime'] = $serverTime->format("c");
			require_once(FRAMEWORK_PATH . 'models/timetable.php');
			$timetable = new Timetable($this->registry);
			$result['current'] = $timetable->getCurrent();
			$result['next'] = $timetable->getNext();
			header('HTTP/1.0 200 OK');
			echo json_encode($result);
			exit();
		}
		else {
			header('HTTP/1.0 405 Method Not Allowed');
			exit();
		}
	}
}
?>