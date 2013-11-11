<?php
/*
 * 05.06.2013
 * Class Authenticate v2.0
 * Objekt sprostredkujuci autorizaciu a pristup k objektu uzivatela. Vyuziva Google OAuth2 API
 * CHANGELOG:
 * 	- v1.0 [16.10.2012]: createTime
 *	- v1.1 [02.03.2013]
 *	- v2.0 [05.06.2013]: Prerobene na autorizaciu cez GoogleAPI
*/

class Authenticate {

	private $user;
	private $loggedIn;
	private $registry;
	private $googleOAuth;
	public $loginFailureReason;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$this->googleOAuth = new Google_Oauth2Service($this->registry->getObject('google')->getGoogleClient());
	}
	
	public function checkForAuthentication() {
		if (isset($_SESSION['token'])) {
			$this->registry->getObject('google')->getGoogleClient()->setAccessToken($_SESSION['token']);
		}
		if ($this->registry->getObject('google')->getGoogleClient()->getAccessToken()) {
			require_once(FRAMEWORK_PATH . 'registry/user.class.php');
			$this->user = new User($this->registry, $this->googleOAuth->userinfo);
			if ($this->user->isValid()) {
				$this->loggedIn = true;
				$_SESSION['sn_auth_session_uid'] = $this->user->getID();
			}
			else {
				$this->loggedIn = false;
			}
		}
		else {
			$this->loggedIn = false;
		}
	}
	
	public function isLoggedIn() {
		return $this->loggedIn;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function logout() {
		unset($_SESSION['sn_auth_session_uid']);
		unset($_SESSION['token']);
		$this->loggedIn = false;
		$this->registry->getObject('google')->getGoogleClient()->revokeToken();
	}
	
}
?>