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
        $this->googleCalendarService  = new Google_Service_Calendar($this->registry->google->getGoogleClient());
	}

    public function getLastEvents($limit = 5) {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $param = array(
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'maxResults' => $limit
        );
		try {
			$events = $this->googleCalendarService->events->listEvents($this->registry->getSetting('googleEventCalendar'), $param);
			$result = array();
			while(true) {
				/**
				 * @var Google_Service_Calendar_Event $event
				 */
				foreach ($events->getItems() as $event) {
					if (new DateTime($event->getEnd()->getDateTime()) > new DateTime) {
						$result[] = new Event($this->registry, $event->getId());
					}
				}
				$pageToken = $events->getNextPageToken();
				if ($pageToken) {
					$optParams = array(
						'pageToken' => $pageToken,
						'orderBy' => 'startTime',
						'singleEvents' => true,
						'maxResults' => $limit
					);
					$events = $this->googleCalendarService->events->listEvents($this->registry->getSetting('googleEventCalendar'), $optParams);
				} else {
					break;
				}
			}
			return $result;
		}
		catch (Google_Service_Exception $e) {
			$this->registry->log->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(setAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			return false;
		}
		catch(Google_Exception $e) {
			$this->registry->log->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(setAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			return false;
		}
    }

    public function getEvents() {
        require_once(FRAMEWORK_PATH . 'models/event.php');
        $param = array(
            'orderBy' => 'startTime',
            'singleEvents' => true
        );
		try {
			$events = $this->googleCalendarService->events->listEvents($this->registry->getSetting('googleEventCalendar'), $param);
			$result = array();
			while(true) {
				/**
				 * @var Google_Service_Calendar_Event $event
				 */
				foreach ($events->getItems() as $event) {
					$result[] = new Event($this->registry, $event->getId());
				}
				$pageToken = $events->getNextPageToken();
				if ($pageToken) {
					$optParams = array(
						'pageToken' => $pageToken,
						'orderBy' => 'startTime',
						'singleEvents' => true
					);
					$events = $this->googleCalendarService->events->listEvents($this->registry->getSetting('googleEventCalendar'), $optParams);
				} else {
					break;
				}
			}
			return $result;
		}
		catch (Google_Service_Exception $e) {
			$this->registry->log->insertLog('SQL', 'ERR', 'Events', "[Events(getEvents)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			return false;
		}
		catch(Google_Exception $e) {
			$this->registry->log->insertLog('SQL', 'ERR', 'Events', "[Events(getEvents)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			return false;
		}
    }
}
?>