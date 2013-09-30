<?php
/**
 * User: admin
 * Date: 20.9.2013
 * Time: 7:42
 */

class Event {
    /**
     * @var Registry
     */
    private $registry;
    private $id;
    /**
     * @var DateTime
     */
    private $startDate;
    /**
     * @var DateTime
     */
    private $endDate;
    private $title;
    private $text;
    private $location;
    private $googleCalendarService;
    /**
     * @var Google_Event
     */
    private $event;
    private $valid;

    public function __construct(Registry $registry, $id = 0) {
        $this->registry = $registry;
        $this->googleCalendarService = new Google_CalendarService($this->registry->getObject('google')->getGoogleClient());
        if ($id != 0 ) {
            $this->event = $this->googleCalendarService->events->get($this->registry->getSetting('googleEventCalendar'), $id);
            $this->registry->firephp->log($this->event->getStart());
            $this->id = $this->event->getId();
            $this->startDate = new DateTime($this->event->getStart()->dateTime);
            $this->endDate = new DateTime($this->event->getEnd()->dateTime);
            $this->text = $this->event->getDescription();
            $this->title = $this->event->getSummary();
            $this->location = $this->event->getLocation();
            $this->valid = true;
        }
        else {
            $this->valid = false;
        }
    }

    public function isValid() {
        return $this->valid;
    }

    public function toArray() {
        $result = array();
        $forbidden = array('registry', 'event', 'googleCalendarService');
        foreach($this as $field => $data) {
            if(!in_array($field, $forbidden)) {
                $result[$field] = $data;
            }
        }
        return $result;
    }

    /**
     * @param $date
     * @param $time
     */
    public function setStartDate($date, $time)
    {
        $this->startDate = new DateTime($date);
        $data = explode(':', $time);
        $this->startDate->setTime($data[0], $data[1]);
    }

    /**
     * @param $date
     * @param $time
     */
    public function setEndDate($date, $time)
    {
        $this->endDate = new DateTime($date);
        $data = explode(':', $time);
        $this->endDate->setTime($data[0], $data[1]);
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function save() {
        if ($this->id == 0) {
            $event = new Google_Event();
            $event->setSummary($this->title);
            $event->setLocation($this->location);

            $start = new Google_EventDateTime();
            $start->setDateTime($this->startDate->format("c"));
            $event->setStart($start);

            $end = new Google_EventDateTime();
            $end->setDateTime($this->endDate->format('c'));
            $event->setEnd($end);

            $event->setDescription($this->text);

            $this->event = $this->googleCalendarService->events->insert($this->registry->getSetting('googleEventCalendar'), $event);

            if ($this->event->getId() != '') {
                $this->id = $this->event->getId();
                $this->valid = true;
                return true;
            }
            else {
                $this->valid = false;
                return false;
            }
        }
        else {
            $this->event->setDescription($this->text);

            $this->event->setSummary($this->title);
            $this->event->setLocation($this->location);

            $start = new Google_EventDateTime();
            $start->setDateTime($this->startDate->format("c"));
            $this->event->setStart($start);

            $end = new Google_EventDateTime();
            $end->setDateTime($this->endDate->format('c'));
            $this->event->setEnd($end);

            $updatedEvent = $this->event = $this->googleCalendarService->events->update($this->registry->getSetting('googleEventCalendar'), $this->event, $this->id);

            if ($updatedEvent->getUpdated() != $this->event->getUpdated()) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function remove() {
        $this->googleCalendarService->events->delete($this->registry->getSetting('googleEventCalendar'), $this->id);
        $this->valid = false;
    }
}