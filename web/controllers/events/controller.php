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
                    $this->editEvent(intval($urlBits[2]));
                    break;
                case 'remove':
                    $this->removeEvent(intval($urlBits[2]));
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
        if (isset($_POST['newEvent_title'])) {
            if ($this->registry->getObject('auth')->getUser()->isAdmin()) {
                require_once(FRAMEWORK_PATH . 'models/event.php');
                $event = new Event($this->registry);
                $event->setTitle($_POST['newEvent_title']);
                $event->setText($_POST['newEvent_text']);
                $event->setLocation($_POST['newEvent_location']);
                $event->setStartDate($_POST['newEvent_date'], $_POST['newEvent_startTime']);
                $event->setEndDate($_POST['newEvent_date'], $_POST['newEvent_endTime']);

                if ($event->save()) {
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
                $redirectBits = array();
                $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávenie na vytvorenie udalosti!', 'alert');
            }
        }
        else {
            $this->uiNew();
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
        }
        else {
            $redirectBits = array();
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Nemáš oprávenie na odstránenie udalosti!', 'alert');
        }
    }

    private function editEvent($id) {
        //editStuff
    }

    private function listEvents($offset) {
        //funny list stuff
    }

}