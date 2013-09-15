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
				case 'remove':
					$this->removeSuplo($urlBits[2]);
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
	}

	private function viewSuplo($date) {
        $date = new DateTime($date);

		$tags = array();
		$tags['title'] = "Suplovanie na " . $date->format("j. n. Y") . " - Infos2";
        $tags['dateFormated'] = $date->format("j. n. Y");
		$this->registry->getObject('template')->buildFromTemplate('viewSuplo');

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		$suploRecords = $suploTable->getRecords();
		$output = "";

        foreach ($suploRecords as $record) {
            $data = $record->toArray();
            $this->registry->firephp->log($data);
			$row = "<tr>" . "\n";
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

		$tags['suploTable'] = $output;
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}

	private function newSuplo() {
		if (isset($_POST['newSuplo_data'])) {
            $date = new DateTime($_POST['newSuplo_date']);
            require_once(FRAMEWORK_PATH . 'models/suploTable.php');
            $suploTable = new suploTable($this->registry, $date);
			$input = $_POST['newSuplo_data'];
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $key => $line){
                if ($key != 0) {
                    $record = array();
                    foreach (explode("\t", $line) as $cell) {
                        $record[] = $cell;
                    }
                    $suploTable->addRecord($record);
                }
            }
            $suploTable->deleteRrecords();
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

    private function uiNew() {
        $date = new DateTime();
		$tags = array();

		$tags['title'] = "Nové suplovanie - Infos2";
		$this->registry->getObject('template')->buildFromTemplate('newSuplo');
		
		$tags['dateFormated'] = $date->format("d.m.Y");
		$tags['dateRaw'] = $date->format("Y-m-d");

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry, $date);
		if ($suploTable->numRecords() > 0) {
			//TODO: nastylovat cez FoundationCSS
			$tags['suploExists'] = '<a class="alert label" href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" style="margin: 5px 0;">Suplovanie na ' . $date->format("d.m.Y") . ' už existuje  - prepisujem</a>' . "\n";
		}
		else {
			$tags['suploExists'] = "";
		}

		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}

    private function removeSuplo($date) {
        return true;
    }

    private function viewRecord($id) {
        require_once(FRAMEWORK_PATH . 'models/suploRecord.php');
        $suploRecord = new suploRecord($this->registry, $id);
        $data = $suploRecord->toArray();
        echo '<h2>' . $data['hour'] . '. hodina</h2>' . "\n";

        echo '<a class="close-reveal-modal">' . "&#215;" . '</a>' . "\n";
    }

    private function suploExists($date) {
        $date = new DateTime($date);
        $dateFormated = $date->format("Y-m-d");
        $this->registry->getObject('db')->executeQuery("SELECT id_suplo FROM suplo WHERE suplo_date = '$dateFormated'");
        if ($this->registry->getObject('db')->numRows() > 0) {
            echo '<a class="alert label" href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" style="margin: 5px 0;">Suplovanie na ' . $date->format("d. m. Y") . ' už existuje  - prepisujem</a>' . "\n";
        }
    }


}
?>