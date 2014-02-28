<?php
/*
 * 05.06.2013
 * Class Authenticate v2.0
 * Objekt sprostredkujuci autorizaciu a pristup k objektu uzivatela. Vyuziva Google OAuth2 API
 * CHANGELOG:
 * 	- v1.0 [16.10.2012]: createTime
 *	- v1.1 [02.03.2013]
 *	- v2.0 [05.06.2013]: Prerobene na autorizaciu cez GoogleAPI
 *  - v2.0 [25.02.2014]: Google Client API v1.0.0
*/

class Authenticate {

	private $user;
	private $loggedIn;
	private $registry;
	public $loginFailureReason;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function checkForAuthentication() {
		if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
			try {
				$this->registry->getObject('google')->getGoogleClient()->setAccessToken($_SESSION['token']);
				if ($this->registry->getObject('google')->getGoogleClient()->isAccessTokenExpired()) {
					$this->registry->getObject('google')->getGoogleClient()->refreshToken($_SESSION['token']);
				}
			}
			catch (Google_Service_Exception $e) {
				$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(setAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}
			catch(Google_Exception $e) {
				$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(setAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}
		}
		try {
			if ($this->registry->getObject('google')->getGoogleClient()->getAccessToken()) {
				$_SESSION['token'] = $this->registry->getObject('google')->getGoogleClient()->getAccessToken();
				$token_data = $this->registry->getObject('google')->getGoogleClient()->verifyIdToken()->getAttributes();
				require_once(FRAMEWORK_PATH . 'registry/user.class.php');
				$this->user = new User($this->registry, $token_data);
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
		catch (Google_Service_Exception $e) {
			$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(getAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
		}
		catch(Google_Exception $e) {
			$this->registry->getObject('log')->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(getAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
		}
	}

	public function apiAuth($user, $password) {
		$password = $this->registry->getSetting('salt') . $password . '-' . $user;
		if ($this->registry->getSetting('apiPassword') == $password && $this->registry->getSetting('apiUser') == $user) {
			return true;
		}
		else {
			return false;
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
		$tags = array();
		$tags['class'] = 'success';
		$tags['message'] = "{lang_successfulLogout}";
		$tags['url'] = $this->registry->buildURL(array());
		$tags['title'] = 'Logout';
		$tags['meta-description'] = "Logout";
		$tags['logoutGoogle'] = '<iframe src="https://accounts.google.com/logout" class="hide"></iframe>';
		$this->registry->getObject('template')->buildFromTemplate('redirect');
		$this->registry->getObject('template')->replaceTags($tags);
		echo $this->registry->getObject('template')->parseOutput();
	}
	
}
?>