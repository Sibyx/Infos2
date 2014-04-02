<?php
class defaultController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$urlBits = $this->registry->url->getURLBits();
		switch(isset($urlBits[1]) ? $urlBits[1] : '') {
			default:
				$this->uiIndex();
				break;
		}
	}
	
	private function uiIndex() {
		if ($this->registry->auth->isLoggedIn()) {
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
			$this->registry->template->buildFromTemplate('dashboard');
			$this->registry->template->replaceTags($tags);
			echo $this->registry->template->parseOutput();
		}
		else {
			$this->registry->template->buildFromTemplate('index');
			$tags = array();
			$tags['title'] = $this->registry->getSetting('sitename');
			$tags['loginUrl'] = filter_var($this->registry->google->getGoogleClient()->createAuthUrl());

			$this->registry->template->replaceTags($tags);
			echo $this->registry->template->parseOutput();
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
			while ($row = $this->registry->db->resultsFromCache($pagination->getCache())) {
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
                $this->registry->template->buildFromTemplate('announcements/single', false);
                $this->registry->template->replaceTags($tags);
				$output .= $this->registry->template->parseOutput();
			}
		}
		$tags = array();
		$tags['announcements'] = $output;
		return $tags;
	}
	
	private function createUserboard() {
		$tags['userFullName'] = $this->registry->auth->getUser()->getFullName();
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
        if ($this->registry->db->numRowsFromCache($cache) > 0) {
            $output .= '<tr><th colspan="5" class="text-center">{lang_today}</th></tr>' . "\n";
            while ($row = $this->registry->db->resultsFromCache($cache)) {
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
        if ($this->registry->db->numRowsFromCache($cache) > 0) {
            $output .= '<tr><th colspan="5" class="text-center">{lang_tomorrow}</th></tr>' . "\n";
            while ($row = $this->registry->db->resultsFromCache($cache)) {
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
        $cache = $suploRecords->getUserSuploHistory(date("m-Y"));
        if ($this->registry->db->numRowsFromCache($cache) > 0) {
            while ($row = $this->registry->db->resultsFromCache($cache)) {
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
            $output .= '<tr><th colspan="5" class="text-center">{lang_noSuploForThisMonth}</th></tr>' . "\n";
        }

		$suploHistory_seasons = '';
		$suploHistory_seasons .= '<option value="' . date("m-Y") . '" selected>{lang_currentPeriod}</option>' . "\n";
		$suploHistory_seasons .= '<option disabled>---</option>' . "\n";
		$cache = $suploRecords->avaibleSuploSeasons();
		if ($this->registry->db->numRowsFromCache($cache) > 0) {
			while ($row = $this->registry->db->resultsFromCache($cache)) {
				$suploHistory_seasons .= '<option value="' . $row['season_value'] . '">' . $row['season_title'] . '</option>' . "\n";
			}
		}

        $tags = array();
        $tags['suploHistory'] = $output;
		$tags['suploHistory_seasons'] = $suploHistory_seasons;
		$tags['actualSeason'] = date("m-Y");
        return $tags;
    }
}
?>