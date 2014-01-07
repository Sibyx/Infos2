<?php
class aboutController {
	
	public function __construct(Registry $registry){
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		switch(isset($urlBits[1]) ? $urlBits[1] : '') {
			case 'blog':
				$this->aboutBlog();
			break;
			case 'bug':
				$this->reportBug();
			break;
			default:				
				$this->aboutProject();
			break;
		}
	}
	
	private function aboutProject() {
		$tags = array();
		$tags['title'] = '{lang_aboutProject} - ' . $this->registry->getSetting('sitename');
		$this->registry->getObject('template')->buildFromTemplate('aboutProject');
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}
	
	private function aboutBlog() {
		header("Location: http://infos2-news.blogspot.sk/");
	}
	
	private function reportBug() {
		if (isset($_POST['reportBug_title'])) {
			require_once(FRAMEWORK_PATH . 'models/email.php');
			$email = new Email($this->registry);
			$email->setSender($_POST['reportBug_email'], 'Kabinet: ' . $_POST['reportBug_room']);
			$email->setSubject("Bug Report");
			$email->buildFromTemplate('reportBug.html');
			$tags = array();
			$tags['bugSummary'] = htmlspecialchars($_POST['reportBug_description']);
			$tags['bugTitle'] = htmlspecialchars($_POST['reportBug_title']);
			$tags['bugRoom'] = htmlspecialchars($_POST['reportBug_room']);
			$tags['bugEmail'] = htmlspecialchars($_POST['reportBug_email']);
			$tags['siteurl'] = $this->registry->getSetting('siteurl');
			$tags['defaultView'] = $this->registry->getSetting('view');
			$email->replaceTags($tags);
			$email->setRecipient($this->registry->getSetting('supportEmail'));
			if ($email->send()) {
				$redirectBits = array();
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Chyba bola úspešne ohlásená! Čoskoro Vás kontaktujeme.', 'success');
			}
			else {
				$redirectBits = array();
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Chybu sa nepodarilo nahlásiť. Skúste to prosím neskôr.', 'alert');
			}

		}
		else {
			$this->uiBug();
		}
	}

	private function uiBug() {
		$tags = array();
		$tags['title'] = '{lang_reportBug} - ' . $this->registry->getSetting('sitename');
		$this->registry->getObject('template')->buildFromTemplate('reportBug');
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}
}
?>