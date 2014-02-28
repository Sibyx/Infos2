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
		$urlBits = $this->registry->getObject('url')->getURLBits();
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
		if (isset($_GET['code'])) {
			$this->registry->getObject('google')->getGoogleClient()->authenticate($_GET['code']);
			$_SESSION['token'] = $this->registry->getObject('google')->getGoogleClient()->getAccessToken();
			header('Location: ' . filter_var($this->registry->getObject('url')->buildURL(array('authenticate', 'login')), FILTER_SANITIZE_URL));
		}
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$this->registry->getObject('log')->insertLog('SQL', 'INF', 'Authenticate', 'Prihlásenie používateľa');
			$this->registry->redirectURL($this->registry->buildURL(array()), '{lang_successfulLogin}', 'success');
		}
		else {
			header('Location: ' . filter_var($this->registry->getObject('google')->getGoogleClient()->createAuthUrl()), FILTER_SANITIZE_URL);
		}
	}
	private function logout() {
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$this->registry->getObject('auth')->logout();
			//$this->registry->redirectURL($this->registry->buildURL(array()), '{lang_successfulLogout}', 'success');
		}
		else {
			$this->registry->redirectURL($this->registry->buildURL(array('authenticate', 'login')), '{lang_noLoggedIn}', 'alert');
		}
	}
}
?>