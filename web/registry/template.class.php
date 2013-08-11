<?php
	/*
	 * 19.05.2013
	 * Class Template v1.0
	 * Objekt na generovanie obsahu
	 * CHANGELOG:
	 * 	- v1.0 [19.05.2013]: createTime
	*/
class Template {

	private $registry;
	private $page = "";
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function buildFromTemplate($templateName) {
		$mainTemplate = FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/main.tpl.php';
		$templatePath = FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/' . $templateName . '.tpl.php';
		if(file_exists($templatePath) && file_exists($mainTemplate)) {
			$this->page = str_replace('{content}', file_get_contents($templatePath), file_get_contents($mainTemplate));
		}
	}
	
	public function replaceTags($tags) {
		$tags['siteurl'] = $this->registry->getSetting('siteurl');
		$tags['defaultView'] = $this->registry->getSetting('view');
		$tags['sitename'] = $this->registry->getSetting('sitename');
		$tags['currentURL'] = $this->registry->getObject('url')->getCurrentURL();
		$tags['userPanel'] = $this->registry->getObject('render')->createUserPanel();
		if(sizeof($tags) > 0) {
			foreach($tags as $tag => $data) {
				if(!is_array($data)) {
					$this->page = str_replace('{' . $tag . '}', $data, $this->page);
				}
			}
		}
	}
	
	public function parseOutput() {
		echo $this->page;
	}
}
?>