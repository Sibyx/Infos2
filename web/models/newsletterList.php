<?php
/**
 * User: admin
 * Date: 23.11.2013
 * Time: 12:35
 */

class NewsletterList {

    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    public function getAnnMails() {
        $cache = $this->registry->db->cacheQuery("SELECT nwt_email FROM newsletter WHERE nwt_announcements = 1");
        return $cache;
    }

    public function getEventMails() {
        $cache = $this->registry->db->cacheQuery("SELECT nwt_email FROM newsletter WHERE nwt_events = 1");
        return $cache;
    }

    public function getSuploMails() {
        $cache = $this->registry->db->cacheQuery("SELECT nwt_email FROM newsletter WHERE nwt_suploAll = 1");
        return $cache;
    }

    public function getSoloSuploMails() {
        $cache = $this->registry->db->cacheQuery("SELECT nwt_email, id_user FROM newsletter WHERE nwt_suploMy = 1");
        return $cache;
    }

    public function getNewsletterForUser($userId) {
        $cache = $this->registry->db->cacheQuery("SELECT * FROM newsletter WHERE id_user = '$userId'");
        return $cache;
    }
} 