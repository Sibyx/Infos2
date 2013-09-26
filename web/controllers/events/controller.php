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
        if (isset($_POST['newEvent_date'])) {
            //stuff
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
            //remove stuff
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