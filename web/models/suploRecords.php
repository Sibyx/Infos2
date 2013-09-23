<?php
/**
 * User: admin
 * Date: 12.9.2013
 * Time: 20:54
 */

class suploRecords {
    /**
     * @var Registry registry
     */
    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    /**
     * @param DateTime $date
     * @return int
     */
    public function getCurrentUser($date) {
        $dateFormated = $date->format("Y-m-d");
        $cache = $this->registry->getObject('db')->cacheQuery("SELECT id_suplo FROM suplo WHERE suplo_date = '$dateFormated' AND id_user = " . $this->registry->getObject('auth')->getUser()->getId());
        return $cache;
    }

    /**
     * @return int
     */
    public function getAll() {
        $cache = $this->registry->getObject('db')->cacheQuery("SELECT id_suplo, DATE_FORMAT(suplo_date, '%d. %m. %Y') FROM suplo");
        return $cache;
    }
}