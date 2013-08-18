<?php
class defaultController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$this->uiIndex();
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
		$this->registry->getObject('template')->buildFromTemplate('index');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
}
?>