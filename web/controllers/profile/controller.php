<?php
class profileController {
	
	public function __construct($registry, $directCall=true){
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		switch (isset($urlBits[1] ) ? $urlBits[1] : '') {
			case 'view':
				$this->showProfile(intval($urlBits[2]));
			break;
			case 'edit':
				$this->editProfile((intval($urlBits[2]) != 0) ? intval($urlBits[2]) : $this->registry->getObject('auth')->getUser()->getID());
			break;
			case 'setAvatar':
				$this->editAvatar(intval($urlBits[2]));
			break;
			default:				
				$this->showProfile($this->registry->getObject('auth')->getUser()->getID());
			break;
		}
	}
	
	private function showProfile($idUser) {
		require_once(FRAMEWORK_PATH . 'models/profile.php');
		$profile = new Profile($this->registry, $idUser);
		if ($profile->isValid()) {
			echo '<div class="four columns">' . "\n";
				//fotka - meno zobrazit ako pri albumoch
			echo '</div>' . "\n";
			echo '<div class="eight columns">' . "\n";
			echo '<section>' . "\n";
			echo '<header><h2></h2></header>' . "\n";
			echo '</section>' . "\n";
			echo '</div>' . "\n";
		}
		else {
			echo '<div class="twelve columns">' . "\n";
			$redirectBits = array();
			$redirectBits[] = 'about';
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Žiadaný profil neexistuje!', 'alert-box alert columns six centered text-centered');	
			echo '</div>' . "\n";
		}
	}
	
	private function editProfile($idUser) {
		require_once(FRAMEWORK_PATH . 'models/profile.php');
		$profile = new Profile($this->registry, $idUser);
		if ($profile->isValid()) {
			if ($this->registry->getObject('auth')->isLoggedIn()) {
				echo '<div class="four columns">' . "\n";
					//fotka - aj po kliknuta dialog na zmenu
				echo '</div>' . "\n";
				echo '<div class="eight columns">' . "\n";
				echo '<section>' . "\n";
				echo '<header><h2></h2></header>' . "\n";
				echo '</section>' . "\n";
				echo '</div>' . "\n";
			}
			else {
				echo '<div class="twelve columns">' . "\n";
				$redirectBits = array();
				$redirectBits[] = 'about';
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Neoprávnená operácia!', 'alert-box alert columns six centered text-centered');	
				echo '</div>' . "\n";
			}
		}
		else {
			echo '<div class="twelve columns">' . "\n";
			$redirectBits = array();
			$redirectBits[] = 'about';
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Žiadaný profil neexistuje!', 'alert-box alert columns six centered text-centered');	
			echo '</div>' . "\n";
		}
	}
}
?>