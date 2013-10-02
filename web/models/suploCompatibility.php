<?php
/**
 * User: admin
 * Date: 2.10.2013
 * Time: 21:02
 */

class suploCompatibility {
    private $registry;
    private $id;
    private $text;
    private $date;
    private $dateFriendly;
    private $valid;

    /**
     * @param Registry $registry
     * @param DateTime $suploDate
     */
    public function __construct(Registry $registry, $suploDate) {
        $this->registry = $registry;
        $suploDate = $suploDate->format("Y-m-d");
        $this->registry->getObject('db')->executeQuery("SELECT suplovanie.*, DATE_FORMAT(suplovanie.suplovanie_date, '%d.%m.%Y') AS date_friendly FROM suplovanie WHERE suplovanie_date = '$suploDate'");
        if ($this->registry->getObject('db')->numRows() > 0) {
            $row = $this->registry->getObject('db')->getRows();
            $this->id = $row['id_suplovanie'];
            $this->date = $suploDate;
            $this->text = $row['suplovanie_text'];
            $this->dateFriendly = $row['date_friendly'];
            $this->valid = true;
        }
        else {
            $this->date = $suploDate;
            $this->valid = false;
        }
    }

    public function isValid() {
        return $this->valid;
    }

    public function toArray() {
        $result = array();
        foreach($this as $field => $data) {
            if(!is_object($data) && !is_array($data)) {
                $result[$field] = $data;
            }
        }
        return $result;
    }

    public function setText($value) {
        $this->text = $value;
    }

    public function save() {
        if ($this->isValid()) {
            $changes = array();
            $changes['suplovanie_text'] = $this->registry->getObject('db')->sanitizeData($this->text);
            $this->registry->getObject('db')->updateRecords('suplovanie', $changes, 'id_suplovanie = ' . $this->id);
            return true;
        }
        else {
            $insert = array();
            $insert['suplovanie_date'] = $this->date;
            $insert['suplovanie_text'] = $this->registry->getObject('db')->sanitizeData($this->text);
            $this->registry->getObject('db')->insertRecords('suplovanie', $insert);
            return true;
        }
    }

    public function remove() {
        $this->registry->getObject('db')->deleteRecords('suplovanie', 'id_suplovanie = ' . $this->id);
    }
}