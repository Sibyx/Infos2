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
	private $model;
	
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
				case 'register':
					$this->registerDelegator();
				break;
			}
		}
	}
	
	private function registerDelegator() {
		require(FRAMEWORK_PATH . 'controllers/authenticate/registrationController.php');
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			$rc = new registrationController($this->registry, true);
		}
		else {
			$rc = new registrationController($this->registry, false);
		}
	}

	private function login() {
		if (isset($_GET['code'])) {
			$this->registry->getObject('google')->getGoogleClient()->authenticate($_GET['code']);
			$_SESSION['token'] = $this->registry->getObject('google')->getGoogleClient()->getAccessToken();
			//$this->registry->redirectURL($this->registry->buildURL(array('authenticate', 'login')), 'Systém zamietol Vaše konto. Prisím kontaktujte správcu systému.', 'alert');
			header('Location: ' . filter_var($this->registry->getObject('url')->buildURL(array('authenticate', 'login')), FILTER_SANITIZE_URL));
			return;
		}
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$this->registry->getObject('log')->insertLog('SQL', 'INF', '[AuthenticateController::login] - User ' . $this->registry->getObject('auth')->getUser()->getEmail() . ' was logged in');
			$this->registry->redirectURL($this->registry->buildURL(array()), 'You was succesfully logged in!', 'success');
		}
		else {
			//$this->uiLogin();
			header('Location: ' . filter_var($this->registry->getObject('google')->getGoogleClient()->createAuthUrl()), FILTER_SANITIZE_URL);
		}
	}
	
	private function uiLogin() {
		$tags = array();
		$tags['title'] = 'Login - Infos2';
		$tags['loginUrl'] = $this->registry->getObject('google')->getGoogleClient()->createAuthUrl();
		$this->registry->getObject('template')->buildFromTemplate('login');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}

	private function logout() {
		if ($this->registry->getObject('auth')->isLoggedIn()) {
			$this->registry->getObject('auth')->logout();
			$this->registry->redirectURL($this->registry->buildURL(array()), 'You was succesfully logged out!', 'success');
		}
		else {
			$this->registry->redirectURL($this->registry->buildURL(array('authenticate', 'login')), 'You are not logged in!', 'alert');
		}
	}
}
?>