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
				case 'edit':
					$this->editSuplo($urlBits[2]);
					break;
				case 'remove':
					$this->removeSuplo($urlBits[2]);
					break;
				case 'new':
					$this->newSuplo($urlBits[2]);
				
				default:
					$this->viewSuplo(date('Y-m-d'));
					break;
			}
		}
	}

	private function viewSuplo($date) {
		$tags = array();
		$tags['title'] = "Suplovanie na " . $date . " - Infos2";
		$this->registry->getObject('template')->buildFromTemplate('viewSuplo');

		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry);
		$suploRecords = $suploTable->getSuploByDay($date);
		$output = "";
		foreach ($suploRecords as $record) {
			$data = $record->toArray();
			$row = "<tr>" . "\n";
			$row .= "<td>" . $data['hour'] . "</td>";
			$row .= "<td>" . $data['missing']->name . "</td>";
			$row .= "<td>" . $record->getClaseesShort() . "</td>";
			$row .= "<td>" . $data['subject'] . "</td>";
			$row .= "<td>" . $data['classroom'] . "</td>";
			$row .= "<td>" . $data['owner']->name . "</td> /n";
			$row .= "</tr>" . "/n";
			$output .= $row;
		}

		$tags['suploTable'] = $output;
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}

	private function newSuplo($date) {
		if ($_POST['newSuplo_data']) {
			$input = $_POST['newSuplo_data'];
		}
		else {
			$this->uiNew($date);
		}
	}

	private function uiNew($date) {
		//TODO: JavaScript pre newSuplo.tpl.php, ktory zabezpeci update pri zmene datumu pre form:action
		$tags = array();
		$tags['title'] = "Nové suplovanie - Infos2";
		$this=>registry->getObject('template')->buildFromTemplate('newSuplo');
		
		$date = new DatetTime(strtodate($date));
		$tags['dateFormated'] = $date->format("d.m.Y");
		$tags['dateRaw'] = $date->format("Y-m-d");

		$output = "";
		require_once(FRAMEWORK_PATH . 'models/suploTable.php');
		$suploTable = new suploTable($this->registry);
		if ($suploTable->getTableByDay($date)) {
			//TODO: nastylovat cez FoundationCSS
			$tags['suploExists'] = '<a href="' . $this->registry->getSetting('siteurl') . '/suplo/view/' . $date->format("Y-m-d") . '" class="">Suplovanie na ' . $date->format("d.m.Y") . ' už existuje  - prepisujem</a>' . "\n";
		}
		else {
			$tags['suploExists'] = "";
		}

		$tags['suploData'] = $output;

		$this->registry->replaceTags($tags);
		$this->registry->parseOutput();
	}
}
?>