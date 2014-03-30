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
				if ($this->registry->google->getGoogleClient()->isAccessTokenExpired()) {
					$this->registry->google->getGoogleClient()->refreshToken(json_decode($_SESSION['token'])->refresh_token);
				}
				$this->registry->google->getGoogleClient()->setAccessToken($_SESSION['token']);
			}
			catch (Google_Service_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(setAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}
			catch(Google_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(setAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}

			try {
				$token_data = $this->registry->google->getGoogleClient()->verifyIdToken(json_decode($_SESSION['token'])->id_token)->getAttributes();
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
			catch (Google_Service_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(getAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}
			catch(Google_Exception $e) {
				$this->registry->log->insertLog('SQL', 'ERR', 'Authenticate', "[Authenticate(getAccessToken)]: Google Error " . $e->getCode() . ":" . $e->getMessage());
			}
		}
		else {
			$this->loggedIn = false;
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

	/**
	 * @return User
	 */
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
		$tags['url'] = $this->registry->url->buildURL(array());
		$tags['title'] = 'Logout';
		$tags['meta-description'] = "Logout";
		$tags['logoutGoogle'] = '<iframe src="https://accounts.google.com/logout" class="hide"></iframe>';
		$this->registry->template->buildFromTemplate('redirect');
		$this->registry->template->replaceTags($tags);
		echo $this->registry->template->parseOutput();
	}
	
}
?>