<?php
class Registry {
	
	private $objects;
	private $settings;
	public $firephp;
	
	public function __construct(){
		$this->firephp = FirePHP::getInstance(true);
	}
	
	
	public function createAndStoreObject($object, $key){
		require_once($object . '.class.php');
		$this->objects[$key] = new $object($this);
	}
	
	public function storeSetting($setting, $key){
		$this->settings[$key] = $setting;
	}
	
	public function getSetting($key){
		return $this->settings[$key];
	}
	
	public function getObject($key){
		return $this->objects[$key];
	}
	
	public function buildURL($urlBits, $queryString='') {
		return $this->getObject('url')->buildURL($urlBits, $queryString, false);
	}
	
	public function redirectURL($url, $message = '', $class = '') {
		$tags = array();
		$tags['class'] = $class;
		$tags['message'] = $message;
		$tags['url'] = $url;
		$tags['title'] = 'Redirect';
		$tags['meta-description'] = "Redirect";
		$this->getObject('template')->buildFromTemplate('redirect');
		$this->getObject('template')->replaceTags($tags);
		$this->getObject('template')->parseOutput();
	}
	
	public function setDebugging($value) {
		$this->firephp->setEnabled($value);
	}
}
?>