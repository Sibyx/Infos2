<?php
class defaultController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
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
	
	private function uiIndex() {
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$tags = array();
			$tags['title'] = 'Dashboard - ' . $this->registry->getSetting('sitename');
			$tags = array_merge(
				$tags,
				$this->createUserboard(),
				$this->createAnnouncements(),
				$this->createSuplo(),
				$this->createEvents(),
				$this->createSuploHistory()
			);
			$this->registry->getObject('template')->buildFromTemplate('dashboard');
			$this->registry->getObject('template')->replaceTags($tags);
			echo $this->registry->getObject('template')->parseOutput();
		}
		else {
			$this->registry->getObject('template')->buildFromTemplate('index');
			$tags = array();
			$tags['title'] = $this->registry->getSetting('sitename');
			$tags['loginUrl'] = filter_var($this->registry->getObject('google')->getGoogleClient()->createAuthUrl());

			$this->registry->getObject('template')->replaceTags($tags);
			echo $this->registry->getObject('template')->parseOutput();
		}
	}
	
	private function createAnnouncements() {
		require_once(FRAMEWORK_PATH . 'models/announcements.php');
        require_once(FRAMEWORK_PATH . 'models/likes.php');
		$announcements = new Announcements($this->registry);
		$pagination = $announcements->listActualAnnouncements();
		$output = '';
		if ($pagination->getNumRowsPage() == 0) {
			$output .= '<article class="text-center">{lang_noAnnouncements}</article>' . "\n";
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
                $tags['userName'] = $row['usr_firstName'] . ' ' . $row['usr_lastName'];
                $tags['createdFriendly'] = $row['createdFriendly'];
                $tags['createdRaw'] = $row['ann_created'];
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
            $output .= '<tr><th colspan="5" class="text-center">{lang_today}</th></tr>' . "\n";
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-url="' . $this->registry->getSetting('siteurl') . '/suplo/record/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['hour'] . '.</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['classroom'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><th colspan="5" class="text-center">{lang_noSubstitutionForYouToday}</th></tr>' . "\n";
        }
        $tags = array();
        $tags['suploToday'] = $output;

        //Zajtra
        $cache = $suploRecords->getCurrentUser(new DateTime(date('Y-m-d', time()+86400)));
        $output = '';
        if ($this->registry->getObject('db')->numRowsFromCache($cache) > 0) {
            $output .= '<tr><th colspan="5" class="text-center">{lang_tomorrow}</th></tr>' . "\n";
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-url="' . $this->registry->getSetting('siteurl') . '/suplo/record/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['hour'] . '.</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['classroom'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><th colspan="5" class="text-center">{lang_noSubstitutionForYouTomorrow}</th></tr>' . "\n";
        }
        $tags['suploTomorow'] = $output;
        return $tags;
    }

    public function createEvents() {
        require_once(FRAMEWORK_PATH . 'models/events.php');
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $events = new Events($this->registry);
        $items = $events->getLastEvents();
        $output = '';
        foreach ($items as $item) {
            if ($item->isValid()) {
                $data = $item->toArray();
                $output .= '<tr data-url="' . $this->registry->getSetting('siteurl') . '/events/view/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['title'] . '</td>';
                $output .= '<td>' . $data['startDate']->format("j. n. Y G:i") . '</td>';
                $output .= '<td>' . $data['location'] . '</td>';
                $output .= '</tr>' . "\n";
            }
        }
        $tags['events'] = $output;
        return $tags;
    }

    private function createSuploHistory() {
        require_once(FRAMEWORK_PATH . 'models/suploRecords.php');
        require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
        $suploRecords = new suploRecords($this->registry);

        $output = '';
        $cache = $suploRecords->getCurrentUserHistory();
        if ($this->registry->getObject('db')->numRowsFromCache($cache) > 0) {
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-url="' . $this->registry->getSetting('siteurl') . '/suplo/record/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['dateFriendly'] . '.</td>' . "\n";
                $output .= '<td>' . $data['hour'] . '.</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><th colspan="5" class="text-center">{noSuploForThisMonth}</th></tr>' . "\n";
        }
        $tags = array();
        $tags['suploHistory'] = $output;
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