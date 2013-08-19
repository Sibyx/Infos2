<?php
class defaultController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$urlBits = $this->registry->getObject('url')->getURLBits();
			switch(isset($urlBits[1]) ? $urlBits[1] : '') {
				case 'time':
					$this->getCurrentTime();
				break;
				default:				
					$this->uiIndex();
				break;
			}
		}
		else {
			$this->registry->redirectURL($this->registry->buildURL(array('authenticate', 'login')), 'Please log in', 'error');
		}
	}
	
	private function uiIndex() {
		$tags = array();
		$tags['title'] = 'Infos Dashboard';
		$tags['userFullName'] = $this->registry->getObject('auth')->getUser()->getFullName();
		$serverTime = new DateTime();
		$tags['serverTimeFormated'] = $serverTime->format("d. m. Y - H:i");
		$tags['serverTime'] = $serverTime->format("c");
		require_once(FRAMEWORK_PATH . 'models/timetable.php');
		$timetable = new Timetable($this->registry);
		$tags['current'] = $timetable->getCurrent();
		$tags['next'] = $timetable->getNext();
		$this->registry->getObject('template')->buildFromTemplate('index');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
	
	private function getCurrentTime() {
		$result = array();
		$serverTime = new DateTime();
		$result['serverTimeFormated'] = $serverTime->format("d. m. Y - H:i");
		$result['serverTime'] = $serverTime->format("c");
		require_once(FRAMEWORK_PATH . 'models/timetable.php');
		$timetable = new Timetable($this->registry);
		$result['current'] = $timetable->getCurrent();
		$result['next'] = $timetable->getNext();
		echo json_encode($result);
	}
}
?>