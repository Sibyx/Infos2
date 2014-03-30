<?php
/*
 * 05.06.2013
 * Authentization controller v2.0
 * Autorizacny controller
 * CHANGELOG:
 * 	- v1.0 [21.09.2012]: createTime
 *	- v2.0 [05.06.2013]: GoogleAPI OAuth
*/
class AuthenticateController {

	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$urlBits = $this->registry->url->getURLBits();
		if (isset($urlBits[1])) {
			switch($urlBits[1]) {
				case 'login':
					$this->login();
					break;
				case 'logout':
					$this->logout();
					break;
			}
		}
	}

	private function login() {
		if ($this->registry->auth->isLoggedIn()) {
			$this->registry->log->insertLog('SQL', 'INF', 'Authenticate', 'Prihlásenie používateľa');
			$this->registry->url->redirectURL($this->registry->url->buildURL(array()), '{lang_successfulLogin}', 'success');
		}
		else {
			header('Location: ' . filter_var($this->registry->google->getGoogleClient()->createAuthUrl()), FILTER_SANITIZE_URL);
		}
		if (isset($_GET['code'])) {
			$this->registry->google->getGoogleClient()->authenticate($_GET['code']);
			$_SESSION['token'] = $this->registry->google->getGoogleClient()->getAccessToken();
			header('Location: ' . filter_var($this->registry->url->buildURL(array('authenticate', 'login')), FILTER_SANITIZE_URL));
		}
	}
	private function logout() {
		if ($this->registry->auth->isLoggedIn()) {
			$this->registry->auth->logout();
		}
		else {
			$this->registry->url->redirectURL($this->registry->url->buildURL(array('authenticate', 'login')), '{lang_noLoggedIn}', 'alert');
		}
	}
}
?>