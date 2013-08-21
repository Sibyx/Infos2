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
			$this->registry->redirectURL($this->registry->buildURL(array('authenticate', 'login')), 'Prosím najprv sa autorizuj!', 'alert');
		}
	}
	
	private function uiIndex() {
		$tags = array();
		$tags['title'] = 'Infos Dashboard';
		$tags = array_merge(
			$tags,
			$this->createUserboard(),
			$this->createAnnouncements()
		);
		$this->registry->getObject('template')->buildFromTemplate('index');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
	
	private function createAnnouncements() {
		require_once(FRAMEWORK_PATH . 'models/announcements.php');
		$announcements = new Announcements($this->registry);
		$pagination = $announcements->listAnnouncements(0);
		$output = '';
		if ($pagination->getNumRowsPage() == 0) {
			$output .= '<article class="text-center">Žiadne oznamy</article>' . "\n";
		}
		else {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
				$article = '';
				$article .= '<article>' . "\n";
				$article .= '<header><h3><a href="' . $this->registry->getSetting('siteurl') . '/announcements/view/' . $row['id_announcement'] . '">' . $row['ann_title'] . '</a></h3></header>' . "\n";
				$article .= $row['ann_text'];
				$article .= '<hr />' . "\n";
				$article .= '<footer>' . "\n";
				$article .= '<small><a href="https://plus.google.com/u/1/' . $row['id_user'] . '/about" target="_blank">' . $row['user_firstName'] . ' ' . $row['user_lastName'] . '</a> - <time pubdate="' . $row['createdRaw'] . '">' . $row['createdFriendly'] . '</time></small>' . "\n";
				$article .= '</footer>' . "\n";
				$article .= '</article>' . "\n";
				$output .= $article;
			}
		}
		$tags = array();
		$tags['announcements'] = $output;
		return $tags;
	}
	
	private function createUserboard() {
		$tags['userFullName'] = $this->registry->getObject('auth')->getUser()->getFullName();
		$serverTime = new DateTime();
		$tags['serverTimeFormated'] = $serverTime->format("d. m. Y - H:i");
		$tags['serverTime'] = $serverTime->format("c");
		require_once(FRAMEWORK_PATH . 'models/timetable.php');
		$timetable = new Timetable($this->registry);
		$tags['current'] = $timetable->getCurrent();
		$tags['next'] = $timetable->getNext();
		return $tags;
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