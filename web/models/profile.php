<?php
/**
 * User: admin
 * Date: 18.11.2013
 * Time: 19:34
 */

class Profile {

    private $registry;
    private $fullName;
    private $suploCalendar;
    private $valid;

    public function __construct(Registry $registry, $id) {
        $this->registry = $registry;
    }
} 