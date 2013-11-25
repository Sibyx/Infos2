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
        $urlBits = $this->registry->getObject('url')->getURLBits();
        if ($this->registry->getObject('auth')->isLoggedIn()) {
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
                    $this->listEvents(intval($urlBits));
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

    private  function newEvent() {
        if ($this->registry->getObject('auth')->getUser()->isAdmin()) {
            if (isset($_POST['newEvent_title'])) {
                require_once(FRAMEWORK_PATH . 'models/event.php');
                $event = new Event($this->registry);
                $event->setTitle($_POST['newEvent_title']);
                $event->setText($_POST['newEvent_text']);
                $event->setLocation($_POST['newEvent_location']);
                $event->setStartDate($_POST['newEvent_date'], $_POST['newEvent_startTime']);
                $event->setEndDate($_POST['newEvent_date'], $_POST['newEvent_endTime']);
                if ($event->save()) {
                    require_once(FRAMEWORK_PATH . 'libs/newsletter/newsletterManager.php');
                    $newsletter = new newsletterManager($this->registry, 'newEvent', $event->toArray());
                    $redirectBits = array();
                    $redirectBits[] = 'events';
                    $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť bola vytvorená!', 'success');
                }
                else {
                    $redirectBits = array();
                    $redirectBits[] = 'events';
                    $redirectBits[] = 'new';
                    $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nastala chyba pri vytváraní udalosti :(', 'alert');
                }
            }
            else {
                $this->uiNew();
            }
        }
        else {
            $this->registry->getObject('log')->insertLog('SQL', 'WAR', 'Events', 'Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' sa pokúsil vytvoriť udalosť.');
            $redirectBits = array();
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávenie na vytvorenie udalosti!', 'alert');
        }
    }

    private function uiNew() {
        $tags = array();
        $tags['title'] = "Nová udalosť";
        $tags['dateFormated'] = date("j.n.Y");
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $this->registry->getObject('template')->buildFromTemplate('newEvent');
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
    }

    private function removeEvent($id) {
        if ($this->registry->getObject('auth')->getUser()->isAdmin()) {
            require_once(FRAMEWORK_PATH . 'models/event.php');
            $event = new Event($this->registry, $id);
            if ($event->isValid()) {
                $event->remove();
                $redirectBits = array();
                $redirectBits[] = 'events';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť bola odstránená!', 'success');
            }
            else {
                $redirectBits = array();
                $redirectBits[] = 'events';
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť neexistuje!', 'alert');
            }
        }
        else {
            $this->registry->getObject('log')->insertLog('SQL', 'WAR', 'Events', 'Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' sa pokúsil odstrániť udalosť.');
            $redirectBits = array();
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávenie na odstránenie udalosti!', 'alert');
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
            $this->registry->getObject('template')->buildFromTemplate('viewEvent', false);
            $this->registry->getObject('template')->replaceTags($tags);
            echo $this->registry->getObject('template')->parseOutput();
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'events';
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť neexistuje!', 'alert');
        }
    }

    private function editEvent($id) {
        if ($this->registry->getObject('auth')->getUser()->isAdmin()) {
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
                        $redirectBits = array();
                        $redirectBits[] = 'events';
                        $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť bola upravená!', 'success');
                    }
                    else {
                        $redirectBits = array();
                        $redirectBits[] = 'events';
                        $redirectBits[] = 'new';
                        $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nastala chyba pri upravovaní udalosti :(', 'alert');
                    }
                }
                else {
                    $redirectBits = array();
                    $redirectBits[] = 'events';
                    $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť neexistuje!', 'alert');
                }
            }
            else {
                $this->uiEdit($id);
            }
        }
        else {
            $this->registry->getObject('log')->insertLog('SQL', 'WAR', 'Events', 'Užívateľ ' . $this->registry->getObject('auth')->getUser()->getFullName() . ' sa pokúsil upraviť udalosť.');
            $redirectBits = array();
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávenie na upravenie udalosti!', 'alert');
        }
    }

    private function uiEdit($id) {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $event = new Event($this->registry, $id);
        if ($event->isValid()) {
            $data = $event->toArray();
            $tags = array();
            $tags['title'] = "Upraviť udalosť";
            $tags['dateFormated'] = $data['startDate']->format("j.n.Y");
            $tags['eventId'] = $data['id'];
            $tags['startTime'] = $data['startDate']->format("H:i");
            $tags['endTime'] = $data['endDate']->format("H:i");
            $tags['text'] = $data['text'];
            $tags['location'] = $data['location'];
            $tags['eventTitle'] = $data['title'];
            $this->registry->getObject('template')->buildFromTemplate('header', false);
            $tags['header'] = $this->registry->getObject('template')->parseOutput();
            $this->registry->getObject('template')->buildFromTemplate('editEvent');
            $this->registry->getObject('template')->replaceTags($tags);
            echo $this->registry->getObject('template')->parseOutput();
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'events';
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Udalosť neexistuje!', 'alert');
        }
    }

    private function listEvents() {
        $tags = array();
        $tags['title'] = "Udalosti";
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $this->registry->getObject('template')->buildFromTemplate('listEvents');
        require_once(FRAMEWORK_PATH . 'models/events.php');
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $events = new Events($this->registry);
        $items = $events->getEvents();
        $output = '';
        foreach ($items as $item) {
            if ($item->isValid()) {
                $data = $item->toArray();
                $output .= '<tr data-url="' . $this->registry->getSetting('siteurl') . '/events/view/' . $data['id'] . '">' . "\n";
                $output .= '<td>' . $data['title'] . '</td>';
                $output .= '<td>' . $data['startDate']->format("j. n. Y G:i") . '</td>';
                $output .= '<td>' . $data['location'] . '</td>';
                $output .= '</tr>' . "\n";
            }
        }
        $tags['events'] = $output;
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
    }

}