<?php
/**
 * User: admin
 * Date: 27.11.2013
 * Time: 19:14
 */

class NewsletterRecord extends Model{

    private $userId;
    private $email;
    private $announcements;
    private $events;
    private $suploMy;
    private $suploAll;

    public function __construct(Registry $registry, $id = 0) {
		parent::__construct($registry);
        if ($id > 0) {
            $this->registry->db->executeQuery("SELECT * FROM newsletter WHERE id_newsletter = $id");
            if ($this->registry->db->numRows() > 0) {
                $row = $this->registry->db->getRows();
                $this->id = $id;
                $this->userId = $row['id_user'];
                $this->email = $row['nwt_email'];
                $this->announcements = $row['nwt_announcements'];
                $this->events = $row['nwt_events'];
                $this->suploMy = $row['nwt_suploMy'];
                $this->suploAll = $row['nwt_suploAll'];
                $this->valid = true;
            }
            else {
                $this->valid = false;
            }
        }
        else {
            $this->id = 0;
            $this->valid = false;
        }
    }

    public function setEmail($value) {
        $this->email = $this->registry->db->sanitizeData($value);
    }

    public function setAnnouncements($value) {
        $this->announcements = intval($value);
    }

    public function setEvents($value) {
        $this->events = intval($value);
    }

    public function setSuploMy($value) {
        $this->suploMy = intval($value);
    }

    public function setSuploAll($value) {
        $this->suploAll = intval($value);
    }

    public function save() {
        if ($this->id > 0) {
            $update = array();
            $update['nwt_email'] = $this->email;
            $update['nwt_announcements'] = $this->announcements;
            $update['nwt_events'] = $this->events;
            $update['nwt_suploMy'] = $this->suploMy;
            $update['nwt_suploAll'] = $this->suploAll;
            if ($this->registry->db->updateRecords('newsletter', $update, 'id_newsletter = ' . $this->id)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            $insert = array();
            $insert['id_user'] = $this->registry->auth->getUser()->getId();
            $insert['nwt_email'] = $this->email;
            $insert['nwt_announcements'] = $this->announcements;
            $insert['nwt_events'] = $this->events;
            $insert['nwt_suploMy'] = $this->suploMy;
            $insert['nwt_suploAll'] = $this->suploAll;
            if ($this->registry->db->insertRecords('newsletter', $insert)) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function remove() {
        if ($this->registry->db->deleteRecords('newsletter', 'id_newsletter = ' . $this->id)) {
            return true;
        }
        else {
            return false;
        }
    }
} 