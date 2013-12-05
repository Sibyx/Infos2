<?php
/**
 * User: admin
 * Date: 27.11.2013
 * Time: 19:41
 */

class newsletterController {

    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        if ($this->registry->getObject('auth')->isLoggedIn()) {
            switch(isset($urlBits[1]) ? $urlBits[1] : '') {
                case 'new':
                    $this->newNewsletterRecord();
                    break;
                case 'edit':
                    $this->editNewsletterRecord(intval($urlBits[2]));
                    break;
                case 'remove':
                    $this->removeNewsletterRecord(intval($urlBits[2]));
                    break;
            }
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'authenticate';
            $redirectBits[] = 'login';
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_pleaseLogIn}', 'alert');
        }
    }

    private function newNewsletterRecord() {
        if (isset($_POST['newNewsletterRecord_email'])) {
            require_once(FRAMEWORK_PATH . '/models/newsletterRecord.php');
            $newsletterRecord = new NewsletterRecord($this->registry);
            $newsletterRecord->setAnnouncements($_POST['newNewsletterRecord_announcements']);
            $newsletterRecord->setEmail($_POST['newNewsletterRecord_email']);
            $newsletterRecord->setEvents($_POST['newNewsletterRecord_events']);
            $newsletterRecord->setSuploAll($_POST['newNewsletterRecord_suploAll']);
            $newsletterRecord->setSuploMy($_POST['newNewsletterRecord_suploMy']);
            if ($newsletterRecord->save()) {
                $redirectBits = array();
                $redirectBits[] = 'profile';
                $redirectBits[] = 'settings';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_newsletterRecordAdded}', 'success');
            }
            else {
                $redirectBits = array();
                $redirectBits[] = 'profile';
                $redirectBits[] = 'settings';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_newsletterRecordAddError}', 'alert');
            }
        }
        else {
            $this->uiNew();
        }
    }

    private function uiNew() {
        $tags = array();
        $tags['title'] = "{lang_newsletterSubscribe} - " . $this->registry->getSetting('sitename');
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $this->registry->getObject('template')->buildFromTemplate('newNewsletterRecord');
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
    }

    private function editNewsletterRecord($id) {
        if (isset($_POST['editNewsletterRecord_email'])) {
            require_once(FRAMEWORK_PATH . '/models/newsletterRecord.php');
            $newsletterRecord = new NewsletterRecord($this->registry, $id);
            if ($newsletterRecord->isValid()) {
                $newsletterRecord->setAnnouncements($_POST['editNewsletterRecord_announcements']);
                $newsletterRecord->setEmail($_POST['editNewsletterRecord_email']);
                $newsletterRecord->setEvents($_POST['editNewsletterRecord_events']);
                $newsletterRecord->setSuploAll($_POST['editNewsletterRecord_suploAll']);
                $newsletterRecord->setSuploMy($_POST['editNewsletterRecord_suploMy']);
                if ($newsletterRecord->save()) {
                    $redirectBits = array();
                    $redirectBits[] = 'profile';
                    $redirectBits[] = 'settings';
                    $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_newsletterRecordEdited}', 'success');
                }
                else {
                    $redirectBits = array();
                    $redirectBits[] = 'profile';
                    $redirectBits[] = 'settings';
                    $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_newsletterRecordEditError}', 'alert');
                }
            }
            else {
                $redirectBits = array();
                $redirectBits[] = 'profile';
                $redirectBits[] = 'settings';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_nonexistNewsletterRecord}', 'alert');
            }
        }
        else {
            $this->uiEdit($id);
        }
    }

    private function uiEdit($id) {
        require_once(FRAMEWORK_PATH . '/models/newsletterRecord.php');
        $newsletterRecord = new NewsletterRecord($this->registry, $id);
        $data = $newsletterRecord->toArray();
        $tags = array();
        $tags['title'] = "{lang_newsletterSubscribtionEdit} - " . $this->registry->getSetting('sitename');
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $this->registry->getObject('template')->buildFromTemplate('editNewsletterRecord');
        if ($data['suploMy']) {
            $tags['suploMy'] = 'checked';
        }
        else {
            $tags['suploMy'] = '';
        }

        if ($data['suploAll']) {
            $tags['suploAll'] = 'checked';
        }
        else {
            $tags['suploAll'] = '';
        }

        if ($data['announcements']) {
            $tags['announcements'] = 'checked';
        }
        else {
            $tags['announcements'] = '';
        }

        if ($data['events']) {
            $tags['events'] = 'checked';
        }
        else {
            $tags['events'] = '';
        }
        $tags['email'] = $data['email'];
        $tags['id'] = $id;
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
    }

    private function removeNewsletterRecord($id) {
        require_once(FRAMEWORK_PATH . '/models/newsletterRecord.php');
        $newsletterRecord = new NewsletterRecord($this->registry, $id);
        if ($newsletterRecord->isValid()) {
            if ($newsletterRecord->remove()) {
                $redirectBits = array();
                $redirectBits[] = 'profile';
                $redirectBits[] = 'settings';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_newsletterRecordDeleted}', 'success');
            }
            else {
                $redirectBits = array();
                $redirectBits[] = 'profile';
                $redirectBits[] = 'settings';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_newsletterRecordDeleteError}', 'alert');
            }
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'profile';
            $redirectBits[] = 'settings';
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), '{lang_nonexistNewsletterRecord}', 'alert');
        }
    }
}
?>