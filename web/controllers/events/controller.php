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
}