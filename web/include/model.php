<?php
/**
 * User: admin
 * Date: 28.3.2014
 * Time: 17:28
 */

class Model {

	protected $registry;

	protected $id;
	protected $valid;

	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}

	public function isValid() {
		return $this->valid;
	}

	public function toArray() {
		$reflection = new ReflectionClass($this);
		$result = array();
		$forbidden = array('registry', 'event', 'googleCalendarService');
		$properites = $reflection->getProperties();
		foreach($properites as $var) {
			$var->setAccessible(true);
			if(!in_array($var->getName(), $forbidden)) {
				$result[$var->getName()] = $var->getValue($this);
			}
		}
		return $result;
	}
}