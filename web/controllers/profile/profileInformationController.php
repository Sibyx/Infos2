<?php
class profileInformationController {

	private $registry;
	
	public function __construct(Registry $registry, $directCall = true, $user) {
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getUrlBits();
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			if (isset($urlBits[1])) {
				switch ($urlBits[1]) {
					case 'edit': 
						$this->editProfile($user);
					break;
					case 'setAvatar':
						$this->setAvatar($user);
					break;
					default:
						$this->viewProfile($user);
					break;
				}
			}
			else {
				$this->viewProfile($user);
			}
		}
	}
	
	private function viewProfile($user) {
		require_once(FRAMEWORK_PATH . 'models/profile.php');
		$profile = new Profile($this->registry, $user);
		if ($profile->isValid()) {
			require_once(FRAMEWORK_PATH . 'libs/images/imageManager.php');
			$img = new imageManager();
			$data = $profile->toArray();	
			echo '<nav id="microMenu" style="height: 60px;">'; 
			$img->loadFromFile($this->registry->getSetting('siteurl') . '/resources/icons/leftarrow.png');
			echo '<div class="left">' . "\n";
			$urlBits = array();
			$urlBits[] = $this->registry->getObject('url')->getUrlBit(0);
			echo '<a href="' . $this->registry->getObject('url')->buildUrl($urlBits, '') . '" class="button-link classic-button-link">' . $img->getImgTag('icon') . '</a>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="right">' . "\n";
			//$img->loadFromFile($this->registry->getSetting('siteurl') . '/resources/icons/printer.png');
			//echo '<a href="' . $this->registry->getSetting('siteurl') . '/print/task/' . $id . '" class="button-link classic-button-link" target="_blank">' . $img->getImgTag('icon') . '</a>' . "\n";
			echo '<a href="' .$this->registry->getSetting('siteurl') . '/messages/create/' . $data['id'] . '" class="button-link green-button-link">Poslať správu</a>' . "\n";
			echo '</div>' . "\n";
			echo '</nav>' . "\n";
			
			echo '<section id="profileAvatar">' . "\n";
				$img->loadFromFile($this->registry->getSetting('siteurl') . '/' . $data['avatar']);
				echo $img->getImgTag('avatar');
			echo '</section>' . "\n";
			echo '<section id="profileData">' . "\n";
			echo "<header><h1>" . $profile->getFullName() . "</h1></header>";
			echo '<table class="list">' . "\n";
			echo '<thead>' . "\n";
			echo '<tr>' . "\n";
			echo '<th>Rola</th>' . "\n";
			echo '<th>E-mail</th>' . "\n";
			echo '<th>Trieda</th>' . "\n";
			echo '<th>Status</th>' . "\n";
			echo '<th>Posledná aktivita</th>' . "\n";
			echo '</tr>' . "\n";
			echo '</thead>' . "\n";
			echo '<tbody>' . "\n";
			echo '<tr>' . "\n";
			echo '<td>' . $data['role'] . "</td>";
			echo '<td>' . $data['email'] . "</td>";
			echo '<td>' . $data['classTitle'] . "</td>";
			if ($data['status'] == 'active') {
				echo '<td class="active">' . $data['status'] . '</td>' . "\n";
			}
			else {
				echo '<td class="inactive">' . $data['status'] . '</td>' . "\n";
			}
			echo '<td>' . $data['lastActivity'] . "</td>";
			echo '</tr>' . "\n";
			echo '</tbody>' . "\n";
			echo '</table>' . "\n";	
			echo '</section>' . "\n";
			echo '<div class="break"></div>' . "\n";
			echo '<section id="profileDashboard">' . "\n";
			
			echo '<article>' . "\n";
			echo '<header><h2>Skupiny</h2></header>' . "\n";
			//listovanie skupin uzivatela
			require_once(FRAMEWORK_PATH . 'models/groups.php');
			$groups = new Groups($this->registry);
			$pagination = $groups->listMembersGroups($offset, $user);
			if ($pagination->getNumRowsPage() == 0) {
				echo '<div id="result" class="success">Nie si pridelený k žiadnej skupine </div>' . "\n";
			}
			else {
				echo '<table class="list">' . "\n";
				echo '<thead>' . "\n";
				echo '<tr>' . "\n";
				echo '<th>Skupina</th>' . "\n";
				echo '<th>Predmet</th>' . "\n";
				echo '<th>Správca</th>' . "\n";
				echo '</tr>' . "\n";
				echo '</thead>' . "\n";
				echo '<tbody>' . "\n";
				while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
					echo '<tr data-redirect-url="' . $this->registry->getSetting('siteurl') . '/groups/view/' . $row['id_group'] . '">' . "\n";
					echo '<td>' . $row['group_title'] . '</td>' . "\n";
					echo '<td>' . $row['sub_name'] . '</td>' . "\n";
					echo '<td>' . $row['usr_first_name'] . " " . $row['usr_last_name'] . '</td>' . "\n";
					echo '</tr>' . "\n";
				}
				echo '</tbody>' . "\n";
				echo '</table>' . "\n";
			}
			echo '</article>' . "\n";
			
			echo '</section>' . "\n";
		}
		else {
			echo '<div class="error">Neznámy profil!</div>' . "\n";
		}
	}
	
	private function editProfile($id) {
		require_once(FRAMEWORK_PATH . 'models/profile.php');
		$profile = new Profile($this->registry, $id);
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && isset($_POST)) {
			$result = array();
			if ($this->registry->getObject('auth')->getUser()->getId() == $id || $this->registry->getObject('auth')->getUser()->getRole() != 'administrator') {
				require_once(FRAMEWORK_PATH . 'libs/images/imageManager.php');
				$img = new imageManager();
				$img->loadFromPost('editProfile_avatar', FRAMEWORK_PATH . 'uploads/profilePics/', $id);
				$profile->setAvatar('uploads/profilePics/' . $img->getName() . $img->getExt());
				$profile->save();
			}
			elseif ($this->registry->getObject('auth')->getUser()->getRole() == 'administrator') {
				require_once(FRAMEWORK_PATH . 'libs/images/imageManager.php');
				$img = new imageManager();
				$img->loadFromPost('editProfile_avatar', FRAMEWORK_PATH . 'uploads/profilePics/', $id);
				$profile->setAvatar('uploads/profilePics/' . $img->getName() . $img->getExt());
				$profile->save();
				/*$profile->setStatus($_POST['editProfile_status']);
				$profile->setClass($_POST['editProfile_class']);
				$profile->save();
				$result['error'] = false;*/
			}
			elseif ($this->registry->getObject('auth')->getUser()->getRole() == 'teacher') {
				$profile->setStatus($_POST['editProfile_status']);
				$profile->setClass($_POST['editProfile_class']);
				$profile->save();
				$result['error'] = false;
			}
			else {
				$result['error'] = true;
				$result['message'] = 'Nemate opravnenia na upravu!';
			}
			echo json_encode($result);
		}
		else {
			$this->uiEdit($id);
		}
	}
	
	private function uiEdit($id) {
		require_once(FRAMEWORK_PATH . 'models/profile.php');
		$profile = new Profile($this->registry, $id);
		echo '<article>' . "\n";
		echo '<header><h1>' . $profile->getFullName() . '</h1></header>' . "\n";
		if ($this->registry->getObject('auth')->getUser()->getId() == $id || $this->registry->getObject('auth')->getUser()->getRole() == 'administrator') {
			echo '<form enctype="multipart/form-data" id="formEditAvatar" name="formEditAvatar" method="post" data-script-url="' . $this->registry->getSetting('siteurl') . '/profile/setAvatar/' . $id . '">' . "\n";
			//avatar
			echo '<div class="center">' . "\n";
			echo '<div class="center"><img class="avatar" src="' . $this->registry->getSetting('siteurl') . '/' . $profile->getAvatar() . '" alt="avatar" id="avatarPreview"/></div>' . "\n";
			echo '<div class="field">' . "\n";
			echo '<input type="file" name="editAvatar_file" id="editAvatar_file"/>' . "\n";
			echo '</div>' . "\n";
			echo '<div id="fileinfo">' . "\n";
			echo '<div id="filename"></div>' . "\n";
			echo '<div id="filesize"></div>' . "\n";
			echo '<div id="filetype"></div>' . "\n";
			echo '<div id="filedim"></div>' . "\n";
			echo '<div id="result" class="error invisible">Zadany subor nie je obrazok</div>' . "\n";
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</form>' . "\n";
		}
		elseif ($this->registry->getObject('auth')->getUser()->getRole() == 'teacher') {

		}
		else {
			echo '<div class="error">Nemate upravnenie na upravu tohto profilu!</div>' . "\n";
		}
		echo '</article>' . "\n";
	}
	
	private function setAvatar($id) {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && isset($_POST)) {
			$result = array();
			if ($this->registry->getObject('auth')->getUser()->getId() == $id || $this->registry->getObject('auth')->getUser()->getRole() == 'administrator') {
				require_once(FRAMEWORK_PATH . 'models/profile.php');
				$profile = new Profile($this->registry, $id);
				require_once(FRAMEWORK_PATH . 'libs/images/imageManager.php');
				$img = new imageManager();
				$img->loadFromPost('editAvatar_file', FRAMEWORK_PATH . 'uploads/profilePics/', $id);
				$profile->setAvatar('uploads/profilePics/' . $img->getName() . $img->getExt());
				$profile->save();
				$result['error'] = false;
			}
			else {
				$result['error'] = true;
				$result['message'] = 'Nemate opravnenia na upravu!';
			}
			echo json_encode($result);
		}
		else {
			echo '<div id="result" class="error">Nesprávne volanie funkcie!</div>' . "\n";
		}
	}
}
?>