<?php
class suploTable {

    /**
     * @var Registry Pointer to Registry
     */
    private $registry;

    /**
     * @var suploRecord[] Array of suplo records
     */
    private $suploRecords;

    /**
     * @var suploRecord[] Array of new records for suplo
     */
    private $newSuploRecords;

    /**
     * @var int Number of records in $suploRecords
     */
    private $numRecords;

    /**
     * @var string String value for date of suplo day
     */
    private $date;

    /**
     * @param Registry $registry
     * @param datetime $date
     */
    public function __construct(Registry $registry, $date) {
		$this->registry = $registry;
        $this->date = $date;
        $cache = $this->registry->db->cacheQuery("SELECT id_suplo FROM suplo WHERE sup_date = '" . $this->date->format("Y-m-d") . "'");
        $this->numRecords = $this->registry->db->numRowsFromCache($cache);
        require_once(FRAMEWORK_PATH . "models/suploRecord.php");
        if ($this->numRecords > 0) {
            $this->suploRecords = array();
            while ($row = $this->registry->db->resultsFromCache($cache)) {
                $this->suploRecords[] = new suploRecord($this->registry, $row['id_suplo']);
            }
        }
	}


    /**
     * @return array|suploRecord[]
     */
    public function getRecords() {
        return $this->suploRecords;
	}

    /**
     * @return int
     */
    public function numRecords() {
        return $this->numRecords;
    }

    public function deleteRrecords() {
        foreach ($this->suploRecords as $record) {
            $record->remove();
        }
    }

    public function addRecord($data) {
        $this->registry->db->executeQuery('SELECT id_suplo FROM suplo WHERE sup_nick = "' . $data[1] . '" AND sup_hour = ' . $data[0] . ' AND sup_date = "' . $this->date->format("Y-m-d") . '"');
        if ($this->registry->db->numRows() > 0) {
            $row = $this->registry->db->getRows();
            $suploRecord = new suploRecord($this->registry, $row['id_suplo']);
            $recordData = $suploRecord->toArray();
            $suploRecord->setClasses($data[2]);
            $suploRecord->setSubject($data[3]);
            $suploRecord->setClassroom($data[4]);
            $suploRecord->setDate($this->date);
            if ($recordData['owner']->nick != $data[5]) {
                $suploRecord->setOwner($data[5]);
                $suploRecord->setId(0);
            }
            $suploRecord->setNote($data[6]);
            $suploRecord->save();
            $this->suploRecords[] = $suploRecord;
        }
        else {
            $suploRecord = new suploRecord($this->registry);
            $suploRecord->setHour($data[0]);
            $suploRecord->setMissing($data[1]);
            $suploRecord->setClasses($data[2]);
            $suploRecord->setSubject($data[3]);
            $suploRecord->setClassroom($data[4]);
            $suploRecord->setOwner($data[5]);
            $suploRecord->setNote($data[6]);
            $suploRecord->setDate($this->date);
            $suploRecord->save();
            $this->suploRecords[] = $suploRecord;
        }
    }
}
?>