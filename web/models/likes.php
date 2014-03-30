<?php
/**
 * User: admin
 * Date: 15.9.2013
 * Time: 10:37
 */

class Likes {

    private $registry;
    private $announcementId;
    private $numLikes = 0;
    private $numDislikes = 0;
    private $likers = "{lang_noLikes}";
    private $dislikers = "{lang_noDislikes}";

    public function __construct(Registry $registry, $announcementId) {
        $this->registry = $registry;
        $this->announcementId = $announcementId;
        $this->registry->db->executeQuery("SELECT * FROM vwLikes WHERE id_announcement = $announcementId");
        if ($this->registry->db->numRows() > 0) {
            while ($row = $this->registry->db->getRows()) {
                if ($row['lik_status']) {
                    if ($this->numLikes > 0) {
                        $this->likers .= $row['userFullName'] . ", ";
                    }
                    else {
                        $this->likers = $row['userFullName'] . ", ";
                    }
                    $this->numLikes++;
                }
                else {
                    if ($this->numDislikes > 0) {
                        $this->dislikers .= $row['userFullName'] . ", ";
                    }
                    else {
                        $this->dislikers = $row['userFullName'] . ", ";
                    }
                    $this->numDislikes++;
                }
            }
            $this->likers = rtrim($this->likers, ", ");
            $this->dislikers = rtrim($this->dislikers, ", ");
        }
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

    public function remove() {
        if ($this->registry->db->executeQuery("DELETE FROM likes WHERE id_announcement = " . $this->announcementId)) {
            return true;
        }
        else {
            return false;
        }
    }
}