<?php
	ob_start();
	session_start();
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
    //error_reporting(E_ERROR);
	DEFINE("FRAMEWORK_PATH", dirname(__FILE__) . "/");
	require(FRAMEWORK_PATH . 'config/config.php');
	require(FRAMEWORK_PATH . 'include/model.php');
	require(FRAMEWORK_PATH . 'registry/registry.php');
	require_once(FRAMEWORK_PATH . 'libs/FirePHPCore/FirePHP.class.php');
	require_once(FRAMEWORK_PATH . 'libs/FirePHPCore/fb.php');
	$registry = new Registry();
	$registry->setDebugging(false);
	$registry->createAndStoreObject('logger', 'log');
	$registry->createAndStoreObject('database', 'db');
	$registry->db->newConnection($config['mainDB']['host'], $config['mainDB']['user'], $config['mainDB']['password'], $config['mainDB']['database']);
    $registry->db->executeQuery("SET CHARACTER SET utf8");
	$registry->db->executeQuery("SELECT * FROM setting");
	while ($setting = $registry->db->getRows()) {
		$registry->storeSetting($setting['set_value'], $setting['id_setting']);
	}
	$registry->createAndStoreObject('urlprocessor', 'url');
	$registry->createAndStoreObject('googleApi', 'google');
	$registry->createAndStoreObject('authenticate', 'auth');
	$registry->createAndStoreObject('template', 'template');
	$registry->url->getURLData();
	$controllers = array();
	$registry->db->executeQuery("SELECT * FROM controller WHERE ctr_active = 1");
	while ($controller = $registry->db->getRows()) {
		$controllers[] = $controller['id_controller'];
	}
	$controller = $registry->url->getURLBit(0);
	if ($controller != 'api') {
		$registry->auth->checkForAuthentication();
	}
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
		if(in_array($controller, $controllers)) {
			require_once(FRAMEWORK_PATH . 'controllers/' . $controller . '/controller.php');
			$controllerInc = $controller . 'Controller';
			$controller = new $controllerInc($registry, true);
		}
		else {
			$result = array();
			$result['error'] = true;
			$result['error_type'] = 'invalid-controller';
			$result['message'] = 'Nastala chyba pri spustani scriptu';
			echo json_encode($result);
		}
	}
	else {
		if(in_array($controller, $controllers)) {
			require_once(FRAMEWORK_PATH . 'controllers/' . $controller . '/controller.php');
			$controllerInc = $controller . 'Controller';
			$controller = new $controllerInc($registry, true);
		}
		else {
			$controller = 'default';
			require_once(FRAMEWORK_PATH . 'controllers/' . $controller . '/controller.php');
			$controllerInc = $controller . 'Controller';
			$controller = new $controllerInc($registry, true);
		}
	}
?>