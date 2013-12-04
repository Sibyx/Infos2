<?php
	/*
	 * 14.09.2013
	 * Class Template v1.1
	 * Objekt na generovanie obsahu
	 * CHANGELOG:
	 * 	- v1.0 [19.05.2013]: createTime
	 *  - v1.1 [14.09.2013]: $useMainTemplate
	*/
class Template {

	private $registry;
	private $page = "";
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function buildFromTemplate($templateName, $useMainTemplate = true) {
		$mainTemplate = FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/main.tpl.php';
		$templatePath = FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/' . $templateName . '.tpl.php';
		if(file_exists($templatePath) && file_exists($mainTemplate)) {
            if ($useMainTemplate) {
                $this->page = str_replace('{content}', file_get_contents($templatePath), file_get_contents($mainTemplate));
            }
			else {
                $this->page = file_get_contents($templatePath);
            }
		}
	}
	
	public function replaceTags($tags) {
		$tags['siteurl'] = $this->registry->getSetting('siteurl');
		$tags['defaultView'] = $this->registry->getSetting('view');
		$tags['sitename'] = $this->registry->getSetting('sitename');
		$tags['currentURL'] = $this->registry->getObject('url')->getCurrentURL();
        if (file_exists(FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/userreport.tpl.php')) {
            $tags['userreport'] = file_get_contents(FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/templates/userreport.tpl.php');
        }
        else {
            $tags['userreport'] = '';
        }
		if(sizeof($tags) > 0) {
			foreach($tags as $tag => $data) {
				if(!is_array($data)) {
					$this->page = str_replace('{' . $tag . '}', $data, $this->page);
				}
			}
		}
        $this->replaceLangTags();
	}

    private function replaceLangTags() {
        $langTags = parse_ini_file(FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/lang/' . $this->registry->getSetting('lang') . '.lang.ini', false);
        $this->registry->firephp->log($langTags);
        foreach($langTags as $tag => $data) {
            if(!is_array($data)) {
                $this->page = str_replace('{' . $tag . '}', $data, $this->page);
            }
        }
    }
	
	public function parseOutput() {
		return $this->page;
	}
}
?>