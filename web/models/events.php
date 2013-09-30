<?php
/**
 * User: admin
 * Date: 30.9.2013
 * Time: 20:34
 */
class Events {
    /**
     * @var Registry
     */
    private $registry;
    private $googleCalendarService;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
        $this->googleCalendarService = $this->googleCalendarService = new Google_CalendarService($this->registry->getObject('google')->getGoogleClient());
    }

    public function getEvents($limit = 5, $all = false, $min = '') {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $param = array(
            'singleEvents' => $all,
            'timeMin' => $min,
            'maxResults' => $limit
        );
        $events = $this->googleCalendarService->events->listEvents($this->registry->getSetting('googleEventCalendar'));
        $result = array();
        foreach ($events->getItems() as $event) {
            $result[] = new Event($this->registry, $event->getId());
        }
        return $result;
    }
}
?>