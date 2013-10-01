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

    public function getEvents($limit = 5) {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $param = array(
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'maxResults' => $limit
        );
        $events = $this->googleCalendarService->events->listEvents($this->registry->getSetting('googleEventCalendar'), $param);
        $result = array();
        foreach ($events->getItems() as $event) {
            $result[] = new Event($this->registry, $event->getId());
        }
        return $result;
    }
}
?>