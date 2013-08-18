<?php
/*
 * 04.06.2013
 * Class GoogleAPI v1.0
 * Objekt sprostredkujuci pristup k GoogleAPI
 * CHANGELOG:
 * 	- v1.0 [04.06.2013]: createTime
*/

require_once FRAMEWORK_PATH . '/libs/GoogleClientApi/src/Google_Client.php';
require_once FRAMEWORK_PATH . '/libs/GoogleClientApi/src/contrib/Google_CalendarService.php';
require_once FRAMEWORK_PATH . '/libs/GoogleClientApi/src/contrib/Google_Oauth2Service.php';

class googleApi {

	private $client;
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$this->client = new Google_Client();
		$this->client->setApplicationName($this->registry->getSetting('googleApplicationName'));
		$this->client->setClientId($this->registry->getSetting('googleClientId'));
		$this->client->setClientSecret($this->registry->getSetting('googleClientSecret'));
		$this->client->setRedirectUri($this->registry->getObject('url')->buildURL(array('authenticate', 'login')));
		$scopes = array (
			'https://www.googleapis.com/auth/calendar',
			'https://www.googleapis.com/auth/userinfo.profile',
			'https://www.googleapis.com/auth/userinfo.email'
		);
		$this->client->setScopes($scopes);
	}
	
	public function getGoogleClient() {
		return $this->client;
	}
}
?>