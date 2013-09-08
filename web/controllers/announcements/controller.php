<?php
class announcementsController {
	
	public function __construct(Registry $registry){
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			switch(isset($urlBits[1]) ? $urlBits[1] : '') {
				case 'view':
					$this->viewAnnouncement(intval($urlBits[2]));
				break;
				case 'edit':
					$this->editAnnouncement(intval($urlBits[2]));
				break;
				case 'new':
					$this->newAnnouncement();
				break;
				case 'remove':
					$this->removeAnnouncement(intval($urlBits[2]));
				break;
				default:				
					$this->listAnnouncements(intval($urlBits[1]));
				break;
			}
		}
		else {
			$redirectBits = array();
			$redirectBits[] = 'authenticate';
			$redirectBits[] = 'login';
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Musíš byť prihlásený', 'alert');
		}
	}
	
	private function listAnnouncements($offset) {
		echo '<div class="nine columns">' . "\n";
		echo '<section>' . "\n";
		require_once(FRAMEWORK_PATH . 'models/announcements.php');
		$announcements = new Announcements($this->registry);
		$pagination = $announcements->listAnnouncements($offset);
		if ($pagination->getNumRowsPage() == 0) {
			echo '<div class="">Žiadne články</div>' . "\n";
		}
		else {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
				$comments = new Comments($this->registry, $row['id_article']);
				echo '<article>' . "\n";
				echo '<header><h2><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '">' . $row['article_title'] . '</a></h2></header>' . "\n";
				echo '<div class="row articleInfo">' . "\n";
				echo '<div class="nine columns">' . $row['user_nick'] . ', publikované dňa <time datetime="' . $row['article_date'] . '">' . $row['dateFriendly'] . '</time></div>' . "\n";
				echo '<div class="three columns right-text"><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '#comments">' . $comments->printCommentsNumber() . '</a></div>' . "\n";
				echo '</div>' . "\n";
				echo '<div class="row">' . "\n";
				echo '<div class="twelve columns mt">' . "\n";
				echo $this->getFirstPara($row['article_text']) . ' <a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '">Celý článok</a>' . "\n";
				echo '</div>' . "\n";
				echo '</div>' . "\n";
				echo '</article>' . "\n";
				echo '<hr />' . "\n";
			}
		}
		echo '</section>' . "\n";
		echo '<div class="pagination-centered">' . "\n";
		echo '<ul class="pagination">' . "\n";
		if ($pagination->isFirst()) {
			echo '<li class="arrow unavailable"><a href="">&laquo;</a></li>' . "\n";
		}
		else {
			echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($pagination->getCurrentPage()-2) . '">&laquo;</a></li>' . "\n";
		}
		for ($i = 1;$i <= $pagination->getNumPages(); $i++) {
			if ($i == $pagination->getCurrentPage()) {
				echo '<li class="current"><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
			else {
				echo '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
		}
		if ($pagination->isLast()) {
			echo '<li class="arrow unavailable"><a href="">&raquo;</a></li>' . "\n";
		}
		else {
			echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($pagination->getCurrentPage()) . '">&raquo;</a></li>' . "\n";
		}
		echo '</ul>' . "\n";
		echo '</div>' . "\n";
		echo '</div>' . "\n";
	}
	
	private function viewAnnouncement($announcementId) {
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
		$announcement = new Announcement($this->registry, $announcementId);
		if ($announcement->isValid()) {
			$data = $announcement->toArray();
			$tags = array();
			$tags['title'] = $data['title'] . ' - Infos2';
			$tags['ann_title'] = $data['title'];
			$tags['ann_text'] = $data['text'];
			$tags['ann_createdRaw'] = $data['createdRaw'];
			$tags['ann_createdFriendly'] = $data['createdFriendly'];
			$tags['author_id'] = $data['ownerId'];
			$tags['author_name'] = $data['ownerName'];
            $tags['currentURL'] = $this->registry->getObject('url')->getCurrentURL();
			$this->registry->getObject('template')->buildFromTemplate('announcement');
			$this->registry->getObject('template')->replaceTags($tags);
			$this->registry->getObject('template')->parseOutput();
		}
		else {
			$this->registry->getObject('log')->insertLog('SQL', 'WAR', '[AnnouncementController::viewAnnouncement] - Pokus o otvorenie neexistujúceho oznamu');
			$redirectBits = array();
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Oznam neexistuje!', 'alert');
		}
	}
	
	private function newAnnouncement() {
		if (isset($_POST['newAnn_title'])) {
			require_once(FRAMEWORK_PATH . 'models/announcement.php');
			$announcement = new Announcement($this->registry);
			$announcement->setTitle($_POST['newAnn_title']);
			$announcement->setText($_POST['newAnn_text']);
			if ($announcement->save()) {
				$id = $announcement->getId();
				$redirectBits = array();
				$redirectBits[] = 'announcements';
				$redirectBits[] = 'view';
				$redirectBits[] = $id;
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Oznam bol vytvorený!', 'success');
			}
			else {
				$redirectBits[] = 'announcements';
				$redirectBits[] = 'new';
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nastala chyba pri ukladaní zmien. Skúste prosím znova.', 'alert');
			}
		}
		else {
			if($this->registry->getObject('auth')->getUser()->isAdmin()) {
				$this->uiNew();
			}
			else {
				$this->registry->getObject('log')->insertLog('SQL', 'WAR', '[AnnouncementController::newAnnouncement] - Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' sa pokúsil vytvoriť oznam.');
				$redirectBits = array();
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávnenia na vytvorenie oznamu!', 'alert');
			}
		}
	}
	
	private function uiNew() {
		$tags = array();
		$tags['title'] = 'Nový oznam - Infos2';
		$this->registry->getObject('template')->buildFromTemplate('newAnnouncement');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
	
	private function editAnnouncement($id) {
		if (isset($_POST['editAnn_title'])) {
			require_once(FRAMEWORK_PATH . 'models/announcement.php');
			$announcement = new Announcement($this->registry, $id);
			if ($announcement->isValid()) {
				$announcement->setTitle($_POST['editAnn_title']);
				$announcement->setText($_POST['editAnn_text']);
				if ($announcement->save()) {
					$redirectBits[] = 'announcements';
					$redirectBits[] = 'view';
					$redirectBits[] = $announcement->getId();
					$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Oznam bol upravený!', 'success');
				}
				else {
					$redirectBits[] = 'announcements';
					$redirectBits[] = 'edit';
					$redirectBits[] = $id;
					$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nastala chyba pri ukladaní zmien. Skúste prosím znova.', 'alert');
				}
			}
			else {
				$this->registry->getObject('log')->insertLog('SQL', 'WAR', '[AnnouncementController::editAnnouncement] - Pokus o upravenie neexistujúceho oznamu');
				$redirectBits = array();
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Oznam neexistuje!', 'alert');
			}
		}
		else {
			if($this->registry->getObject('auth')->getUser()->isAdmin()) {
				$this->uiEdit($id);
			}
			else {
				$this->registry->getObject('log')->insertLog('SQL', 'WAR', '[AnnouncementController::editAnnouncement] - Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' sa pokúsil upraviť oznam.');
				$redirectBits = array();
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávnenia na vytvorenie oznamu!', 'alert');
			}
		}
	}
	
	private function uiEdit($id) {
		$tags = array();
		$tags['title'] = 'Upraviť oznam - Infos2';
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
		$announcement = new Announcement($this->registry, $id);
		$data = $announcement->toArray();
		$tags['ann_title'] = $data['title'];
		$tags['ann_text'] = $data['text'];
		$tags['id_ann'] = $data['id'];
		$this->registry->getObject('template')->buildFromTemplate('editAnnouncement');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
	
	private function removeAnnouncement($id) {
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
		$announcement = new Announcement($this->registry, $id);
		if ($announcement->remove()) {
			$redirectBits = array();
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Oznam bol odstránený', 'success');
		}
		else {
			$redirectBits[] = 'announcements';
			$redirectBits[] = 'view';
			$redirectBits[] = $id;
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nastala chyba pri odstraňovaní. Skúste prosím znova.', 'alert');
		}
	}

}
?>