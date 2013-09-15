<?php
/**
 * Class Registry
 */
class Registry {

    private $objects;
	private $settings;
	public $firephp;

    public function __construct(){
		$this->firephp = FirePHP::getInstance(true);
	}

    /**
     * @param string $object
     * @param string $key
     */
    public function createAndStoreObject($object, $key){
		require_once($object . '.class.php');
		$this->objects[$key] = new $object($this);
	}

    /**
     * @param $setting
     * @param $key
     */
    public function storeSetting($setting, $key){
		$this->settings[$key] = $setting;
	}

    /**
     * @param $key
     * @return mixed
     */
    public function getSetting($key){
		return $this->settings[$key];
	}

    /**
     * @param $key
     * @return mixed
     */
    public function getObject($key){
		return $this->objects[$key];
	}

    /**
     * @param array $urlBits
     * @param string $queryString
     * @return mixed
     */
    public function buildURL($urlBits, $queryString='') {
		return $this->getObject('url')->buildURL($urlBits, $queryString, false);
	}

    /**
     * @param string $url
     * @param string $message
     * @param string $class
     */
    public function redirectURL($url, $message = '', $class = '') {
		$tags = array();
		$tags['class'] = $class;
		$tags['message'] = $message;
		$tags['url'] = $url;
		$tags['title'] = 'Redirect';
		$tags['meta-description'] = "Redirect";
		$this->getObject('template')->buildFromTemplate('redirect');
		$this->getObject('template')->replaceTags($tags);
		echo $this->getObject('template')->parseOutput();
	}

    /**
     * @param bool $value
     */
    public function setDebugging($value) {
		$this->firephp->setEnabled($value);
	}
}
?>