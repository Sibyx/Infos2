<?php
/**
 * User: admin
 * Date: 19.9.2013
 * Time: 22:50
 */

class eventsController {

    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
        $urlBits = $this->registry->url->getURLBits();
        if ($this->registry->auth->isLoggedIn()) {
            switch(isset($urlBits[1]) ? $urlBits[1] : '') {
                case 'new':
                    $this->newEvent();
                    break;
                case 'edit':
                    $this->editEvent($urlBits[2]);
                    break;
                case 'remove':
                    $this->removeEvent($urlBits[2]);
                    break;
                case 'view':
                    $this->viewEvent($urlBits[2]);
                    break;
                default:
                    $this->listEvents(intval($urlBits[1]));
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

    private  function newEvent() {
        if ($this->registry->auth->getUser()->isAdmin()) {
            if (isset($_POST['newEvent_title'])) {
                require_once(FRAMEWORK_PATH . 'models/event.php');
                $event = new Event($this->registry);
                $event->setTitle($_POST['newEvent_title']);
                $event->setText($_POST['newEvent_text']);
                $event->setLocation($_POST['newEvent_location']);
                $event->setStartDate($_POST['newEvent_date'], $_POST['newEvent_startTime']);
                $event->setEndDate($_POST['newEvent_date'], $_POST['newEvent_endTime']);
                if ($event->save()) {
                    require_once(FRAMEWORK_PATH . 'include/newsletterManager.php');
                    $newsletter = new newsletterManager($this->registry, 'newEvent', $event->toArray());
                    $redirectBits = array();
                    $redirectBits[] = 'events';
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_eventCreated}', 'success');
                }
                else {
                    $redirectBits = array();
                    $redirectBits[] = 'events';
                    $redirectBits[] = 'new';
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_errorCreatingEvent}', 'alert');
                }
            }
            else {
                $this->uiNew();
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Events', 'Užívateľ sa pokúsil vytvoriť udalosť.');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
        }
    }

    private function uiNew() {
        $tags = array();
        $tags['title'] = "{lang_newEvent} - " . $this->registry->getSetting('sitename');
        $tags['dateFormated'] = date("j.n.Y");
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
        $this->registry->template->buildFromTemplate('events/new');
        $this->registry->template->replaceTags($tags);
        echo $this->registry->template->parseOutput();
    }

    private function removeEvent($id) {
        if ($this->registry->auth->getUser()->isAdmin()) {
            require_once(FRAMEWORK_PATH . 'models/event.php');
            $event = new Event($this->registry, $id);
            if ($event->isValid()) {
                $event->remove();
                $redirectBits = array();
                $redirectBits[] = 'events';
                $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_eventDeleted}', 'success');
            }
            else {
                $redirectBits = array();
                $redirectBits[] = 'events';
                $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistEvent}', 'alert');
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Events', 'Užívateľ sa pokúsil odstrániť udalosť.');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
        }
    }

    private function viewEvent($id) {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $event = new Event($this->registry, $id);
        if ($event->isValid()) {
            $data = $event->toArray();
            $tags = array();
            $tags['title'] = $data['title'];
            $tags['eventId'] = $data['id'];
            $tags['description'] = $data['text'];
            $tags['startDate'] = $data['startDate']->format("j. n. Y - H:i");
            $tags['endDate'] = $data['endDate']->format("j. n. Y - H:i");
            $tags['location'] = $data['location'];
            $this->registry->template->buildFromTemplate('events/view', false);
            $this->registry->template->replaceTags($tags);
            echo $this->registry->template->parseOutput();
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'events';
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistEvent}', 'alert');
        }
    }

    private function editEvent($id) {
        if ($this->registry->auth->getUser()->isAdmin()) {
            if (isset($_POST['editEvent_title'])) {
                require_once(FRAMEWORK_PATH . 'models/event.php');
                $event = new Event($this->registry, $id);
                if ($event->isValid()) {
                    $event->setTitle($_POST['editEvent_title']);
                    $event->setText($_POST['editEvent_text']);
                    $event->setLocation($_POST['editEvent_location']);
                    $event->setStartDate($_POST['editEvent_date'], $_POST['editEvent_startTime']);
                    $event->setEndDate($_POST['editEvent_date'], $_POST['editEvent_endTime']);

                    if ($event->save()) {
                        require_once(FRAMEWORK_PATH . 'include/newsletterManager.php');
                        $newsletter = new newsletterManager($this->registry, 'newEvent', $event->toArray());
                        $redirectBits = array();
                        $redirectBits[] = 'events';
                        $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_eventEdited}', 'success');
                    }
                    else {
                        $redirectBits = array();
                        $redirectBits[] = 'events';
                        $redirectBits[] = 'new';
                        $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_errorEditingEvent}', 'alert');
                    }
                }
                else {
                    $redirectBits = array();
                    $redirectBits[] = 'events';
                    $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistEvent}', 'alert');
                }
            }
            else {
                $this->uiEdit($id);
            }
        }
        else {
            $this->registry->log->insertLog('SQL', 'WAR', 'Events', 'Užívateľ sa pokúsil upraviť udalosť.');
            $redirectBits = array();
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_noPermission}', 'alert');
        }
    }

    private function uiEdit($id) {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $event = new Event($this->registry, $id);
        if ($event->isValid()) {
            $data = $event->toArray();
            $tags = array();
            $tags['title'] = "{lang_editEvent}" . $this->registry->getSetting('sitename');
            $tags['dateFormated'] = $data['startDate']->format("j.n.Y");
            $tags['eventId'] = $data['id'];
            $tags['startTime'] = $data['startDate']->format("H:i");
            $tags['endTime'] = $data['endDate']->format("H:i");
            $tags['text'] = $data['text'];
            $tags['location'] = $data['location'];
            $tags['eventTitle'] = $data['title'];
            $this->registry->template->buildFromTemplate('header', false);
            $tags['header'] = $this->registry->template->parseOutput();
            $this->registry->template->buildFromTemplate('editEvent');
            $this->registry->template->replaceTags($tags);
            echo $this->registry->template->parseOutput();
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'events';
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_nonexistEvent}', 'alert');
        }
    }

    private function listEvents() {
        $tags = array();
        $tags['title'] = "{lang_events} - " . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
        $this->registry->template->buildFromTemplate('events/list');
        require_once(FRAMEWORK_PATH . 'models/events.php');
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $events = new Events($this->registry);
        $items = $events->getEvents();
        $output = '';
        foreach ($items as $item) {
            if ($item->isValid()) {
                $data = $item->toArray();
				$this->registry->firephp->log($data);
                $output .= '<tr data-url="' . $this->registry->getSetting('siteurl') . '/events/view/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['title'] . '</td>';
                $output .= '<td>' . $data['startDate']->format("j. n. Y G:i") . '</td>';
                $output .= '<td>' . $data['location'] . '</td>';
                $output .= '</tr>' . "\n";
            }
        }
        $tags['events'] = $output;
        $this->registry->template->replaceTags($tags);
        echo $this->registry->template->parseOutput();
    }

}