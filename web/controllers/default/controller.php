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
            $this->createSuplo()
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
            $output .= '<tr><td colspan="5" class="text-center" style="font-weight: bold">Dnes</td></tr>' . "\n";
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-suplo-id="' . $data['id'] . '">' . "\n";
                $output .= '<td style="font-weight: bold;">' . $data['hour'] . '. hodina:</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['classroom'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><td colspan="5" class="text-center" style="font-weight: bold">Dnes nesupluješ!</td></tr>' . "\n";
        }
        $tags = array();
        $tags['suploToday'] = $output;

        //Zajtra
        $cache = $suploRecords->getCurrentUser(new DateTime(date('Y-m-d', time()+86400)));
        $output = '';
        if ($this->registry->getObject('db')->numRowsFromCache($cache) > 0) {
            $output .= '<tr><td colspan="5" class="text-center" style="font-weight: bold">Zajtra</td></tr>' . "\n";
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr data-suplo-id="' . $data['id'] . '">' . "\n";
                $output .= '<td style="font-weight: bold;">' . $data['hour'] . '. hodina:</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['classroom'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><td colspan="5" class="text-center" style="font-weight: bold">Zajtra nesupluješ!</td></tr>' . "\n";
        }
        $tags['suploTomorow'] = $output;
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