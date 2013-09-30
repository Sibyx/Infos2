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
			$this->createAnnouncements(),
            $this->createSuplo(),
            $this->createEvents()
		);
		$this->registry->getObject('template')->buildFromTemplate('index');
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}
	
	private function createAnnouncements() {
		require_once(FRAMEWORK_PATH . 'models/announcements.php');
        require_once(FRAMEWORK_PATH . 'models/likes.php');
		$announcements = new Announcements($this->registry);
		$pagination = $announcements->listAnnouncements(0);
		$output = '';
		if ($pagination->getNumRowsPage() == 0) {
			$output .= '<article class="text-center">Žiadne oznamy</article>' . "\n";
		}
		else {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
                $likes = new Likes($this->registry, $row['id_announcement']);
                $data = $likes->toArray();
                $tags = array();
                $tags['annTitle'] = $row['ann_title'];
                $tags['announcementId'] = $row['id_announcement'];
                $tags['annText'] = $row['ann_text'];
                $tags['userId'] = $row['id_user'];
                $tags['userName'] = $row['user_firstName'] . ' ' . $row['user_lastName'];
                $tags['createdFriendly'] = $row['createdFriendly'];
                $tags['createdRaw'] = $row['createdRaw'];
                $tags['likes'] = $data['numLikes'];
                $tags['dislikes'] = $data['numDislikes'];
                $tags['likers'] = $data['likers'];
                $tags['dislikers'] = $data['dislikers'];
                $this->registry->getObject('template')->buildFromTemplate('announcement', false);
                $this->registry->getObject('template')->replaceTags($tags);
				$output .= $this->registry->getObject('template')->parseOutput();
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

    /**
     * @return string
     */
    private function createSuplo() {
        require_once(FRAMEWORK_PATH . 'models/suploRecords.php');
        require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
        $suploRecords = new suploRecords($this->registry);

        //Dnes
        $cache = $suploRecords->getCurrentUser(new DateTime);
        $output = '';
        if ($this->registry->getObject('db')->numRowsFromCache($cache) > 0) {
            $output .= '<tr><th colspan="5" class="text-center">Dnes</th></tr>' . "\n";
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-suplo-url="' . $this->registry->getSetting('siteurl') . '/suplo/record/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['hour'] . '.</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['classroom'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><th colspan="5" class="text-center">Dnes nesupluješ!</th></tr>' . "\n";
        }
        $tags = array();
        $tags['suploToday'] = $output;

        //Zajtra
        $cache = $suploRecords->getCurrentUser(new DateTime(date('Y-m-d', time()+86400)));
        $output = '';
        if ($this->registry->getObject('db')->numRowsFromCache($cache) > 0) {
            $output .= '<tr><th colspan="5" class="text-center">Zajtra</th></tr>' . "\n";
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-suplo-url="' . $this->registry->getSetting('siteurl') . '/suplo/record/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['hour'] . '.</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['classroom'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><th colspan="5" class="text-center">Zajtra nesupluješ!</th></tr>' . "\n";
        }
        $tags['suploTomorow'] = $output;
        return $tags;
    }

    public function createEvents() {
        require_once(FRAMEWORK_PATH . 'models/events.php');
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $events = new Events($this->registry);
        $date = new DateTime('now');
        $items = $events->getEvents(5, false, $date->format("c"));
        $output = '';
        foreach ($items as $item) {
            if ($item->isValid()) {
                $data = $item->toArray();
                $output .= "<tr>";
                $output .= '<td>' . $data['title'] . '</td>';
                $output .= '<td>' . $data['startDate']->format("j. n. Y G:i") . '</td>';
                $output .= '<td>' . $data['location'] . '</td>';
                $output .= '</tr>' . "\n";
            }
        }
        $tags['events'] = $output;
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