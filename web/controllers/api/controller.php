<?php
/**
 * User: admin
 * Date: 18.12.2013
 * Time: 20:28
 */

class apiController {

	private $registry;
	private $allowedControllers = array('suplo', 'server');
	private $requestData;

	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$apiController = $this->registry->url->getURLBit(1);
		$this->delegateControl($apiController);
	}

	private function delegateControl($apiController) {
		if (!empty($apiController) && in_array($apiController, $this->allowedControllers)) {
			require_once( FRAMEWORK_PATH . 'controllers/api/' . $apiController . '.php' );
			$apiController .= 'APIDelegate';
			$api = new $apiController($this->registry, $this);
		}
		else {
			header('HTTP/1.0 404 Not Found');
			exit();
		}
	}

	protected  function requireAuthentication() {
		if(!isset( $_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic realm="Infos2 Login"');
			header('HTTP/1.0 401 Unauthorized');
			exit();
		}
		else {
			$user = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
			if(!$this->registry->auth->apiAuth($user, $password)) {
				header('HTTP/1.0 401 Unauthorized');
				exit();
			}
		}
	}

	public function getRequestData() {
		if($_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->requestData = $_GET;
		}
		elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->requestData = $_POST;
		}
		elseif($_SERVER['REQUEST_METHOD'] == 'PUT') {
			parse_str(file_get_contents('php://input'), $this->requestData);
		}
		elseif($_SERVER['REQUEST_METHOD'] == 'DELETE') {
			parse_str(file_get_contents('php://input'), $this->requestData);
		}
		return $this->requestData;
	}

	public function notFound() {
		header('HTTP/1.0 404 Not Found');
		exit();
	}
}
?>