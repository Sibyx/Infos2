<?php
class suploController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		if ($this->registry->getObject('auth')->isLoggedIn()) {
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
				
				default:
					$this->viewSuplo(date('Y-m-d'));
					break;
			}
		}
        else {
            $redirectBits[] = 'authenticate';
            $redirectBits[] = 'login';
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Musíš byť prihlásený', 'alert');
        }
	}

	private function viewSuplo($date) {
        $date = new DateTime($date);
		$tags = array();
		$tags['title'] = "Suplovanie na " . $date->format("j. n. Y") . " - Infos2";
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $tags['dateFormated'] = $date->format("j. n. Y");
        $tags['dateRaw'] = $date->format("Y-m-d");
        $tags['dateInput'] = $date->format("d.m.Y");
		$this->registry->getObject('template')->buildFromTemplate('viewSuplo');

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		$suploRecords = $suploTable->getRecords();
		$output = "";

        foreach ($suploRecords as $record) {
            $data = $record->toArray();
            $this->registry->firephp->log($data);
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
            $result['header'] = "Suplovanie na " . $date->format("j. n. Y");
            echo json_encode($result);
        }
        else {
            $tags['suploTable'] = $output;
            $this->registry->getObject('template')->replaceTags($tags);
            echo $this->registry->getObject('template')->parseOutput();
        }
	}

	private function newSuplo() {
        if ($this->registry->getObject('auth')->getUser()->isAdmin()) {
            if (isset($_POST['newSuplo_data'])) {
                $date = new DateTime($_POST['newSuplo_date']);
                require_once(FRAMEWORK_PATH . 'models/suploTable.php');
                $suploTable = new suploTable($this->registry, $date);
                $input = $_POST['newSuplo_data'];

                //compatibilityMode
                if ($this->registry->getSetting('compatibilityMode')) {
                    $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('compatibilityDB'));
                    require_once(FRAMEWORK_PATH . 'models/suploCompatibility.php');
                    $suploCompatibility = new suploCompatibility($this->registry, $date);
                    $suploCompatibility->setText($input);
                    $suploCompatibility->save();
                    $this->registry->getObject('db')->setActiveConnection($this->registry->getSetting('mainDB'));
                }

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
                $this->registry->getObject('log')->insertLog('SQL', 'INF', 'Suplo', 'Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' vytvoril/upravil suplovanie na ' . $date->format("Y-m-d"));
                $redirectBits = array();
                $redirectBits[] = 'suplo';
                $redirectBits[] = 'view';
                $redirectBits[] = $date->format("Y-m-d");
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Suplovanie bolo úspešne vytvorené!', 'success');
            }
            else {
                $this->uiNew();
            }
        }
        else {
            $this->registry->getObject('log')->insertLog('SQL', 'WAR', 'Suplo', 'Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' sa pokúsil vytvoriť/upraviť suplovanie.');
            $redirectBits = array();
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávnenia na vytvorenie suplovania!', 'alert');
        }
	}

    private function uiNew() {
        $date = new DateTime();
		$tags = array();

		$tags['title'] = "Nové suplovanie - Infos2";
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
		$this->registry->getObject('template')->buildFromTemplate('newSuplo');

		$tags['dateFormated'] = $date->format("d.m.Y");
		$tags['dateRaw'] = $date->format("Y-m-d");

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		if ($suploTable->numRecords() > 0) {
			$tags['suploExists'] = '<a class="alert label" href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" style="margin: 5px 0;">Suplovanie na ' . $date->format("d.m.Y") . ' už existuje  - prepisujem</a>' . "\n";
		}
		else {
			$tags['suploExists'] = "";
		}

		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}

    private function viewRecord($id) {
        require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
        $suploRecord = new suploRecord($this->registry, $id);
        $this->registry->getObject('template')->buildFromTemplate('viewRecord', false);
        $data = $suploRecord->toArray();
        $tags = array();
        $tags['hour'] = $data['hour'];
        $tags['missingName'] = $data['missing']->name;
        $tags['classroom'] = $data['classroom'];
        $tags['classes'] = $data['classes'];
        $tags['subject'] = $data['subject'];
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
    }

    private function suploExists($date) {
        $date = new DateTime($date);
        $result = array();
        $dateFormated = $date->format("Y-m-d");
        $this->registry->getObject('db')->executeQuery("SELECT id_suplo FROM suplo WHERE suplo_date = '$dateFormated'");
        if ($this->registry->getObject('db')->numRows() > 0) {
            $result['exists'] = true;
            $result['text'] = '<a class="alert label" href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" style="margin: 5px 0;">Suplovanie na ' . $date->format("d. m. Y") . ' už existuje  - prepisujem</a>' . "\n";
        }
        else {
            $result['exists'] = false;
            $result['text'] = '';
        }
        echo json_encode($result);
    }
}
?>