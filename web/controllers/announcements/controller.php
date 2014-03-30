<?php
class announcementsController {

    private $registry;

	public function __construct(Registry $registry){
		$this->registry = $registry;
		$urlBits = $this->registry->url->getURLBits();
		if ($this->registry->auth->isLoggedIn()) {
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
			$this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_pleaseLogIn}', 'alert');
		}
	}
	
	private function listAnnouncements($offset) {
		require_once(FRAMEWORK_PATH . 'models/announcements.php');
		$announcements = new Announcements($this->registry);
		$pagination = $announcements->listAnnouncements($offset);
        $annOutput = '';
		if ($pagination->getNumRowsPage() == 0) {
			$annOutput .= '<div class="">{lang_noAnnouncements}</div>' . "\n";
		}
		else {
			while ($row = $this->registry->db->resultsFromCache($pagination->getCache())) {
                $article = '';
                $article .= '<article>' . "\n";
                $article .= '<header><h3><a href="' . $this->registry->getSetting('siteurl') . '/announcements/view/' . $row['id_announcement'] . '">' . $row['ann_title'] . '</a></h3></header>' . "\n";
                $article .= $row['ann_text'];
                $article .= '<hr />' . "\n";
                $article .= '<footer>' . "\n";
                $article .= '<small><a href="https://plus.google.com/u/1/' . $row['id_user'] . '/about" target="_blank">' . $row['usr_firstName'] . ' ' . $row['usr_lastName'] . '</a> - <time pubdate="' . $row['createdRaw'] . '">' . $row['createdFriendly'] . '</time></small>' . "\n";
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
        $tags['title'] = '{lang_announcements} - ' . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
        $tags['announcements'] = $annOutput;
        $tags['pagination'] = $pagOutput;
        $this->registry->template->buildFromTemplate('announcements/list');
        $this->registry->template->replaceTags($tags);
        echo $this->registry->template->parseOutput();
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
            $tags['currentURL'] = $this->registry->url->getCurrentURL();
            $this->registry->template->buildFromTemplate('announcements/single', false);
            $this->registry->template->replaceTags($tags);
            $tags = array();
            $tags['announcement'] = $this->registry->template->parseOutput();
            $this->registry->template->buildFromTemplate('header', false);
            $tags['header'] = $this->registry->template->parseOutput();
            $tags['title'] = $data['title'] . ' - ' . $this->registry->getSetting('sitename');
			$this->registry->template->buildFromTemplate('announcements/view');
			$this->registry->template->replaceTags($tags);
			echo $this->registry->template->parseOutput();
		}
		else {
			$this->registry->log->insertLog('SQL', 'WAR', 'AnnouncementController]', 'Pokus o otvorenie neexistujúceho oznamu');
			$redirectBits = array();
			$this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistAnnouncement}', 'alert');
		}
	}
	
	private function newAnnouncement() {
        if($this->registry->auth->getUser()->isAdmin()) {
            if (isset($_POST['newAnn_title'])) {
                require_once(FRAMEWORK_PATH . 'models/announcement.php');
                $announcement = new Announcement($this->registry);
                $announcement->setTitle($_POST['newAnn_title']);
                $announcement->setText($_POST['newAnn_text']);
                $announcement->setDeadline($_POST['newAnn_deadline']);
                if ($announcement->save()) {
                    $id = $announcement->getId();
                    require_once(FRAMEWORK_PATH . 'include/newsletterManager.php');
                    $newsletter = new newsletterManager($this->registry, 'newAnnouncement', $announcement->toArray());
                    $redirectBits = array();
                    $redirectBits[] = 'announcements';
                    $redirectBits[] = 'view';
                    $redirectBits[] = $id;
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_announcementCreated}', 'success');
                }
                else {
                    $redirectBits[] = 'announcements';
                    $redirectBits[] = 'new';
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_errorCreatingAnnouncement}', 'alert');
                }
            }
            else {
                $this->uiNew();
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Announcements', 'Edit announcement');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
        }
	}
	
	private function uiNew() {
		$tags = array();
		$tags['title'] = '{lang_newAnnouncement} - ' . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
		$this->registry->template->buildFromTemplate('announcements/new');
		$this->registry->template->replaceTags($tags);
		echo $this->registry->template->parseOutput();
	}
	
	private function editAnnouncement($id) {
        if($this->registry->auth->getUser()->isAdmin()) {
            if (isset($_POST['editAnn_title'])) {
                require_once(FRAMEWORK_PATH . 'models/announcement.php');
                $announcement = new Announcement($this->registry, $id);
                if ($announcement->isValid()) {
                    $announcement->setTitle($_POST['editAnn_title']);
                    $announcement->setText($_POST['editAnn_text']);
                    $announcement->setDeadline($_POST['editAnn_deadline']);
                    if ($announcement->save()) {
                        require_once(FRAMEWORK_PATH . 'include/newsletterManager.php');
                        $newsletter = new newsletterManager($this->registry, 'newAnnouncement', $announcement->toArray());
                        $redirectBits[] = 'announcements';
                        $redirectBits[] = 'view';
                        $redirectBits[] = $announcement->getId();
                        $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_announcementEdited}', 'success');
                    }
                    else {
                        $redirectBits[] = 'announcements';
                        $redirectBits[] = 'edit';
                        $redirectBits[] = $id;
                        $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_errorEditingAnnouncement}', 'alert');
                    }
                }
                else {
                    $this->registry->log->insertLog('SQL', 'WAR', 'Announcements',  'Pokus o upravenie neexistujúceho oznamu');
                    $redirectBits = array();
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistAnnouncement}', 'alert');
                }
            }
            else {
                $this->uiEdit($id);
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Announcements', 'Užívateľ sa pokúsil upraviť oznam.');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
        }
	}
	
	private function uiEdit($id) {
		$tags = array();
		$tags['title'] = '{lang_editAnnouncement} - ' . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
		$announcement = new Announcement($this->registry, $id);
		$data = $announcement->toArray();
		$tags['ann_title'] = $data['title'];
		$tags['ann_text'] = $data['text'];
		$tags['id_ann'] = $data['id'];
        $tags['ann_deadline'] = $data['deadline']->format("j.n.Y");
		$this->registry->template->buildFromTemplate('announcements/edit');
		$this->registry->template->replaceTags($tags);
		echo $this->registry->template->parseOutput();
	}
	
	private function removeAnnouncement($id) {
		require_once(FRAMEWORK_PATH . 'models/announcement.php');
        require_once(FRAMEWORK_PATH . 'models/likes.php');
        if ($this->registry->auth->getUser()->isAdmin()) {
            $announcement = new Announcement($this->registry, $id);
            $likes = new Likes($this->registry, $id);
            if ($announcement->isValid()) {
                if ($likes->remove() && $announcement->remove()) {
                    $redirectBits = array();
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_announcementDeleted}', 'success');
                }
                else {
                    $redirectBits[] = 'announcements';
                    $redirectBits[] = 'view';
                    $redirectBits[] = $id;
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_errorDeletingAnnouncement}', 'alert');
                }
            }
            else {
                $redirectBits[] = 'announcements';
                $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistAnnouncement}', 'alert');
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Announcements', 'Užívateľ sa pokúsil odstrániť oznam id = [' . $id . ']');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
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