<?php
/**
 * User: admin
 * Date: 20.9.2013
 * Time: 7:42
 */

class Event extends Model{
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
     * @var Google_Service_Calendar_Event
     */
    private $event;

    public function __construct(Registry $registry, $id = 0) {
        parent::__construct($registry);
        $this->googleCalendarService = new Google_Service_Calendar($this->registry->google->getGoogleClient());
        if (!empty($id)) {
            $this->event = $this->googleCalendarService->events->get($this->registry->getSetting('googleEventCalendar'), $id);
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
    public function setLocation($location) {
        $this->location = $location;
    }

    public function save() {
        if ($this->id == 0) {
            $event = new Google_Service_Calendar_Event();
            $event->setSummary($this->title);
            $event->setLocation($this->location);

            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($this->startDate->format("c"));
            $event->setStart($start);

            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($this->endDate->format('c'));
            $event->setEnd($end);

            $event->setDescription($this->text);

			try {
				$this->event = $this->googleCalendarService->events->insert($this->registry->getSetting('googleEventCalendar'), $event);
			}
			catch (Google_Service_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Event', "[Event(insert)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}
			catch(Google_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Event', "[Event(insert)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}


			if ($this->event->getId() != '') {
                $this->id = $this->event->getId();
                $this->valid = true;
                $this->registry->log->insertLog('SQL', 'WAR', 'Events', 'Užívateľ vytvoril udalosť ID = ' . $this->id . ' - ' . $this->title);
                return true;
            }
            else {
                $this->registry->log->insertLog('SQL', 'ERR', 'Events', 'GoogleAPI chyba pri pokuse o vytvorenie udalosti "' . $this->title . '"[' . $this->id . ']');
                $this->valid = false;
                return false;
            }
        }
        else {
            $this->event = new Google_Service_Calendar_Event();

            $this->event->setDescription($this->text);

            $this->event->setSummary($this->title);
            $this->event->setLocation($this->location);

            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($this->startDate->format("c"));
            $this->event->setStart($start);

            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($this->endDate->format('c'));
            $this->event->setEnd($end);

            try {
				$updatedEvent = $this->googleCalendarService->events->update($this->registry->getSetting('googleEventCalendar'), $this->id, $this->event);
			}
			catch (Google_Service_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Event', "[Event(update)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
				$updatedEvent = $this->event->getUpdated();
			}
			catch(Google_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Event', "[Event(update)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
				$updatedEvent = $this->event->getUpdated();
			}

			if ($updatedEvent->getUpdated() != $this->event->getUpdated()) {
                $this->registry->log->insertLog('SQL', 'WAR', 'Events', 'Užívateľ upravil udalosť ID = ' . $this->id . ' - ' . $this->title);
                return true;
            }
            else {
                $this->registry->log->insertLog('SQL', 'ERR', 'Events', 'GoogleAPI chyba pri pokuse o upravenie udalosti "' . $this->title . '"[' . $this->id . ']');
                return false;
            }
        }
    }

    public function remove() {
        $this->googleCalendarService->events->delete($this->registry->getSetting('googleEventCalendar'), $this->id);
        $this->registry->log->insertLog('SQL', 'WAR', 'Events', 'Užívateľ odstránil udalosť ID = ' . $this->id . ' - ' . $this->title);
        $this->valid = false;
    }
}