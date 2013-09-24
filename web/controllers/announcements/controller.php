<?php
class announcementsController {

    private $registry;

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
                case 'like':
                    $this->like(intval($urlBits[2]));
                    break;
                case 'dislike':
                    $this->dislike(intval($urlBits[2]));
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
		require_once(FRAMEWORK_PATH . 'models/announcements.php');
		$announcements = new Announcements($this->registry);
		$pagination = $announcements->listAnnouncements($offset);
        $annOutput = '';
		if ($pagination->getNumRowsPage() == 0) {
			$annOutput .= '<div class="">Žiadne oznamy</div>' . "\n";
		}
		else {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
                $article = '';
                $article .= '<article>' . "\n";
                $article .= '<header><h3><a href="' . $this->registry->getSetting('siteurl') . '/announcements/view/' . $row['id_announcement'] . '">' . $row['ann_title'] . '</a></h3></header>' . "\n";
                $article .= $row['ann_text'];
                $article .= '<hr />' . "\n";
                $article .= '<footer>' . "\n";
                $article .= '<small><a href="https://plus.google.com/u/1/' . $row['id_user'] . '/about" target="_blank">' . $row['user_firstName'] . ' ' . $row['user_lastName'] . '</a> - <time pubdate="' . $row['createdRaw'] . '">' . $row['createdFriendly'] . '</time></small>' . "\n";
                $article .= '</footer>' . "\n";
                $article .= '</article>' . "\n";
                $annOutput .= $article;
			}
		}
        $pagOutput = '';
		if ($pagination->isFirst()) {
            $pagOutput .= '<li class="arrow unavailable"><a href="">&laquo;</a></li>' . "\n";
		}
		else {
            $pagOutput .= '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/announcements/' . ($pagination->getCurrentPage()-2) . '">&laquo;</a></li>' . "\n";
		}
		for ($i = 1;$i <= $pagination->getNumPages(); $i++) {
			if ($i == $pagination->getCurrentPage()) {
                $pagOutput .= '<li class="current"><a href="' . $this->registry->getSetting('siteurl') . '/announcements/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
			else {
                $pagOutput .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/announcements/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
		}
		if ($pagination->isLast()) {
            $pagOutput .= '<li class="arrow unavailable"><a href="">&raquo;</a></li>' . "\n";
		}
		else {
            $pagOutput .= '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/announcements/' . ($pagination->getCurrentPage()) . '">&raquo;</a></li>' . "\n";
		}
        $tags = array();
        $tags['title'] = 'Oznamy - Infos2';
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $tags['announcements'] = $annOutput;
        $tags['pagination'] = $pagOutput;
        $this->registry->getObject('template')->buildFromTemplate('listAnnouncements');
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
	}
	
	private function viewAnnouncement($announcementId) {
        require_once(FRAMEWORK_PATH . 'models/likes.php');
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
		$announcement = new Announcement($this->registry, $announcementId);
        $likes = new Likes($this->registry, $announcementId);
		if ($announcement->isValid()) {
			$data = $announcement->toArray();
            $likesData = $likes->toArray();
			$tags = array();
			$tags['annTitle'] = $data['title'];
            $tags['announcementId'] = $announcementId;
			$tags['annText'] = $data['text'];
			$tags['createdRaw'] = $data['createdRaw'];
			$tags['createdFriendly'] = $data['createdFriendly'];
			$tags['userId'] = $data['ownerId'];
			$tags['userName'] = $data['ownerName'];
            $tags['likes'] = $likesData['numLikes'];
            $tags['dislikes'] = $likesData['numDislikes'];
            $tags['likers'] = $likesData['likers'];
            $tags['dislikers'] = $likesData['dislikers'];
            $tags['currentURL'] = $this->registry->getObject('url')->getCurrentURL();
            $this->registry->getObject('template')->buildFromTemplate('announcement', false);
            $this->registry->getObject('template')->replaceTags($tags);
            $tags = array();
            $tags['announcement'] = $this->registry->getObject('template')->parseOutput();
            $this->registry->getObject('template')->buildFromTemplate('header', false);
            $tags['header'] = $this->registry->getObject('template')->parseOutput();
            $tags['title'] = $data['title'] . ' - Infos2';
			$this->registry->getObject('template')->buildFromTemplate('viewAnnouncement');
			$this->registry->getObject('template')->replaceTags($tags);
			echo $this->registry->getObject('template')->parseOutput();
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
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
		$this->registry->getObject('template')->buildFromTemplate('newAnnouncement');
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
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
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
		$announcement = new Announcement($this->registry, $id);
		$data = $announcement->toArray();
		$tags['ann_title'] = $data['title'];
		$tags['ann_text'] = $data['text'];
		$tags['id_ann'] = $data['id'];
		$this->registry->getObject('template')->buildFromTemplate('editAnnouncement');
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
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

    private function like($announcementId) {
        require_once(FRAMEWORK_PATH . 'models/like.php');
        require_once(FRAMEWORK_PATH . 'models/likes.php');
        $like = new Like($this->registry, $announcementId);
        $like->setStatus(true);
        $like->save();
        $likes = new Likes($this->registry, $announcementId);
        $data = $likes->toArray();
        $result = array();
        $result['likers'] = $data['likers'];
        $result['numLikes'] = $data['numLikes'];
        echo json_encode($result);
    }

    private function dislike($announcementId) {
        require_once(FRAMEWORK_PATH . 'models/like.php');
        require_once(FRAMEWORK_PATH . 'models/likes.php');
        $like = new Like($this->registry, $announcementId);
        $like->setStatus(false);
        $like->save();
        $likes = new Likes($this->registry, $announcementId);
        $data = $likes->toArray();
        $result = array();
        $result['likers'] = $data['dislikers'];
        $result['numLikes'] = $data['numDislikes'];
        echo json_encode($result);
    }

}
?>