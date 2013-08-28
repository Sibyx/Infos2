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
					$this->newSuplo();
				
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
			$row .= "<td>" . 
		}

		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
}
?>