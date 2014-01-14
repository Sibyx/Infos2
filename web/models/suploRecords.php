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
        $cache = $this->registry->getObject('db')->cacheQuery("SELECT id_suplo FROM suplo WHERE sup_date = '$dateFormated' AND id_user = '" . $this->registry->getObject('auth')->getUser()->getId() . "'");
        return $cache;
    }

    public function getUserSuploHistory($season) {
        $cache = $this->registry->getObject('db')->cacheQuery("SELECT id_suplo FROM suplo WHERE DATE_FORMAT(sup_date, '%m-%Y') = '$season' AND id_user = '" . $this->registry->getObject('auth')->getUser()->getId() . "'");
        return $cache;
    }

	public function avaibleSuploSeasons() {
		$cache = $this->registry->getObject('db')->cacheQuery("SELECT DISTINCT DATE_FORMAT(sup_date, '%m-%Y') AS season_value, DATE_FORMAT(sup_date, '%M / %Y') AS season_title FROM suplo WHERE id_user = '" . $this->registry->getObject('auth')->getUser()->getId() . "' ORDER BY sup_date DESC LIMIT 9");
		return $cache;
	}

    /**
     * @return int
     */
    public function getAll() {
        $cache = $this->registry->getObject('db')->cacheQuery("SELECT id_suplo, DATE_FORMAT(sup_date, '%d. %m. %Y') FROM suplo");
        return $cache;
    }
}