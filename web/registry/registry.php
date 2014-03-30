<?php
/**
 * Class Registry
 */
class Registry {

	private $settings;

	/**
	 * @var Database
	 */
	public $db;

	/**
	 * @var Template
	 */
	public $template;

	/**
	 * @var Authenticate
	 */
	public $auth;

	/**
	 * @var Logger
	 */
	public $log;

	/**
	 * @var UrlProcessor
	 */
	public $url;

	/**
	 * @var FirePHP
	 */
	public $firephp;

	/**
	 * @var GoogleApi
	 */
	public $google;

    public function __construct(){
		$this->firephp = FirePHP::getInstance(true);
	}

    /**
     * @param string $object
     * @param string $key
     */
    public function createAndStoreObject($object, $key){
		require_once($object . '.class.php');
		$this->$key = new $object($this);
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
     * @param bool $value
     */
    public function setDebugging($value) {
		$this->firephp->setEnabled($value);
	}
}
?>