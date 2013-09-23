<?php
/**
 * User: admin
 * Date: 20.9.2013
 * Time: 7:42
 */

class Event {

    private $registry;
    private $id;
    private $date;
    private $dateFormated;
    private $dateRaw;
    private $text;
    private $author;
    private $valid;

    public function __construct(Registry $registry, $id = 0) {
        $this->registry = $registry;

    }


}