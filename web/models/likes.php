<?php
/**
 * User: admin
 * Date: 15.9.2013
 * Time: 10:37
 */

class Likes {

    private $registry;
    private $numLikes = 0;
    private $numDislikes = 0;
    private $likers = "Príspevok zatiaľ nedostal kladné hodnotenie.";
    private $dislikers = "Príspevok zatiaľ nedostal záporne hodnotenie.";

    public function __construct(Registry $registry, $announcementId) {
        $this->registry = $registry;
        $this->registry->getObject('db')->executeQuery("SELECT * FROM listLikes WHERE id_announcement = $announcementId");
        if ($this->registry->getObject('db')->numRows() > 0) {
            while ($row = $this->registry->getObject('db')->getRows()) {
                if ($row['like_status']) {
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
}