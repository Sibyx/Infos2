<?php
class suploController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$urlBits = $this->registry->url->getURLBits();
		if ($this->registry->auth->isLoggedIn()) {
			switch (isset($urlBits[1]) ? $urlBits[1] : '') {
				case 'view':
					$this->viewSuplo($urlBits[2]);
					break;
				case 'new':
					$this->newSuplo();
                    break;
                case 'record':
                    $this->viewRecord(intval($urlBits[2]));
                    break;
                case 'suploExists':
                    $this->suploExists($urlBits[2]);
                    break;
				case 'suploSummary':
					$this->suploSummary($urlBits[2]);
					break;
				
				default:
					$this->viewSuplo(date('Y-m-d'));
					break;
			}
		}
        else {
            $redirectBits[] = 'authenticate';
            $redirectBits[] = 'login';
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_pleaseLogIn}', 'alert');
        }
	}

	private function viewSuplo($date) {
        $date = new DateTime($date);
		$tags = array();
		$tags['title'] = "{lang_suplo} " . $date->format("j. n. Y") . " - " . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
        $tags['dateFormated'] = $date->format("j. n. Y");
        $tags['dateRaw'] = $date->format("Y-m-d");
        $tags['dateInput'] = $date->format("d.m.Y");
		$this->registry->template->buildFromTemplate('suplo/view');

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		$suploRecords = $suploTable->getRecords();
		$output = "";

        foreach ($suploRecords as $record) {
            $data = $record->toArray();
            $row = '<tr data-url="' . $this->registry->getSetting('siteurl') . '/suplo/record/' . $data['id'] . '">' . "\n";
			$row .= "<td>" . $data['hour'] . "</td>";
			$row .= "<td>" . $data['missing']->name . "</td>";
			$row .= "<td>" . $record->getClassesShort() . "</td>";
			$row .= "<td>" . $data['subject'] . "</td>";
			$row .= "<td>" . $data['classroom'] . "</td>";
			$row .= "<td>" . $data['owner']->name . "</td> \n";
            $row .= "<td>" . $data['note'] . "</td> \n";
			$row .= "</tr>" . "\n";
			$output .= $row;
		}
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            $result = array();
            $result['text'] = $output;
            $result['header'] = $this->registry->template->getLocaleValue("lang_suplo") . " " . $date->format("j. n. Y");
            echo json_encode($result);
        }
        else {
            $tags['suploTable'] = $output;
            $this->registry->template->replaceTags($tags);
            echo $this->registry->template->parseOutput();
        }
	}

	private function newSuplo() {
        if ($this->registry->auth->getUser()->isAdmin()) {
            if (isset($_POST['newSuplo_data'])) {
                $date = new DateTime($_POST['newSuplo_date']);
                require_once(FRAMEWORK_PATH . 'models/suploTable.php');
                $suploTable = new suploTable($this->registry, $date);
                $input = $_POST['newSuplo_data'];
                $suploTable->deleteRrecords();
                foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $key => $line){
                    if ($key != 0) {
                        $record = array();
                        foreach (explode("\t", $line) as $cell) {
                            $record[] = $cell;
                        }
                        if (!empty($record[0])) {
                            $suploTable->addRecord($record);
                        }
                    }
                }
                require_once(FRAMEWORK_PATH . 'include/newsletterManager.php');
                $newsletter = new newsletterManager($this->registry, 'newSuplo', $date);
                $this->registry->log->insertLog('SQL', 'INF', 'Suplo', 'Užívateľ vytvoril/upravil suplovanie na ' . $date->format("Y-m-d"));
                $redirectBits = array();
                $redirectBits[] = 'suplo';
                $redirectBits[] = 'view';
                $redirectBits[] = $date->format("Y-m-d");
                $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_suploCreated}', 'success');
            }
            else {
                $this->uiNew();
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Suplo', 'Užívateľ sa pokúsil vytvoriť/upraviť suplovanie.');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
        }
	}

    private function uiNew() {
        $date = new DateTime();
		$tags = array();

		$tags['title'] = "{lang_newSuplo} - " . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
		$this->registry->template->buildFromTemplate('suplo/new');

		$tags['dateFormated'] = $date->format("d.m.Y");
		$tags['dateRaw'] = $date->format("Y-m-d");

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		if ($suploTable->numRecords() > 0) {
			$tags['suploExists'] = '<a class="alert label" href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" style="margin: 5px 0;">{lang_suploExists}</a>' . "\n";
		}
		else {
			$tags['suploExists'] = "";
		}

		$this->registry->template->replaceTags($tags);
		echo $this->registry->template->parseOutput();
	}

    private function viewRecord($id) {
        require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
        $suploRecord = new suploRecord($this->registry, $id);
        $this->registry->template->buildFromTemplate('suplo/records', false);
        $data = $suploRecord->toArray();
        $tags = array();
        $tags['hour'] = $data['hour'];
        $tags['missingName'] = $data['missing']->name;
        $tags['classroom'] = $data['classroom'];
        $tags['classes'] = $data['classes'];
        $tags['subject'] = $data['subject'];
        $this->registry->template->replaceTags($tags);
        echo $this->registry->template->parseOutput();
    }

    private function suploExists($date) {
        $date = new DateTime($date);
        $result = array();
        $dateFormated = $date->format("Y-m-d");
        $this->registry->db->executeQuery("SELECT id_suplo FROM suplo WHERE sup_date = '$dateFormated'");
        if ($this->registry->db->numRows() > 0) {
            $result['exists'] = true;
            $result['text'] = '<a class="alert label" href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" style="margin: 5px 0;">{lang_suploExists}</a>' . "\n";
        }
        else {
            $result['exists'] = false;
            $result['text'] = '';
        }
        echo json_encode($result);
    }

	private function suploSummary($season) {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			require_once(FRAMEWORK_PATH . 'models/suploRecords.php');
			require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
			$suploRecords = new suploRecords($this->registry);

			$output = '';
			$cache = $suploRecords->getUserSuploHistory($season);
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

			echo $output;
		}
		else {
			$this->printSummary($season);
		}
	}

    private function printSummary($season) {
        require_once(FRAMEWORK_PATH . 'models/suploRecords.php');
        require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
        $suploRecords = new suploRecords($this->registry);
        $output = '';
        $cache = $suploRecords->getUserSuploHistory($season);
        if ($this->registry->db->numRowsFromCache($cache) > 0) {
            while ($row = $this->registry->db->resultsFromCache($cache)) {
                $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
                $data = $suploRecord->toArray();
                $output .= '<tr>' . "\n";
                $output .= '<td>' . $data['dateFriendly'] . '.</td>' . "\n";
                $output .= '<td>' . $suploRecord->getClassesShort() . '</td>' . "\n";
                $output .= '<td>' . $data['subject'] . '</td>' . "\n";
                $output .= '<td>' . $data['missing']->name . '</td>' . "\n";
                $output .= '<td></td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output .= '<tr><th colspan="5" class="text-center">{lang_noSuploForThisMonth}</th></tr>' . "\n";
        }
        $tags = array();
        $tags['suploHistory'] = $output;
		$date_explode = explode('-', $season);
		$month = new DateTime();
		$month->setDate($date_explode[1], $date_explode[0], 1);
        $tags['month'] = $month->format("F Y");
        $tags['teacherName'] = $this->registry->auth->getUser()->getFullName();
        $tags['title'] = "{lang_suploHistoryHeader} - " . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('suplo/summary', false);
        $this->registry->template->replaceTags($tags);
        echo $this->registry->template->parseOutput();
    }
}
?>