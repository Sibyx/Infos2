<?php
/**
 * User: admin
 * Date: 15.9.2013
 * Time: 11:22
 */

class Like {

    private $registry;
    private $id;
    private $announcementId;
    private $status;
    private $valid;

    public function __construct(Registry $registry, $announcementId) {
        $this->registry = $registry;
        $this->announcementId = $announcementId;
        $userId = $this->registry->auth->getUser()->getId();
        $this->registry->db->executeQuery("SELECT * FROM likes WHERE id_user = '$userId' AND id_announcement = $announcementId");
        if ($this->registry->db->numRows() > 0) {
            $row = $this->registry->db->getRows();
            $this->id = $row['id_like'];
            $this->status = $row['lik_status'];
            $this->valid = true;
        }
        else {
            $this->valid = false;
            $this->id = 0;
        }
    }

    public function isValid() {
        return $this->valid;
    }

    public function setStatus($value) {
        $this->status = $value;
    }

    public function save() {
        if ($this->id > 0) {
            $update = array();
            $update['lik_status'] = $this->status;
            $this->registry->db->updateRecords('likes', $update, 'id_like = ' . $this->id);
        }
        else {
            $insert = array();
            $insert['id_announcement'] = $this->announcementId;
            $insert['id_user'] = $this->registry->auth->getUser()->getId();
            $insert['lik_status'] = $this->status;
            $this->registry->db->insertRecords('likes', $insert);
            $this->id = $this->registry->db->lastInsertID();
        }
    }

}
