<?php
	ob_start();
	session_start();
	DEFINE("FRAMEWORK_PATH", dirname(__FILE__) . "/");
	require(FRAMEWORK_PATH . 'config/config.php');
	require(FRAMEWORK_PATH . 'registry/registry.php');
	require_once(FRAMEWORK_PATH . 'libs/FirePHPCore/FirePHP.class.php');
	require_once(FRAMEWORK_PATH . 'libs/FirePHPCore/fb.php');
	$registry = new Registry();
	$registry->setDebugging(true);
	$registry->createAndStoreObject('logger', 'log');
	$registry->createAndStoreObject('mysqldb', 'db');
	$registry->getObject('db')->newConnection($configs['db_host'], $configs['db_user'], $configs['db_pass'], $configs['db_database']);
	$registry->getObject('db')->executeQuery("SET CHARACTER SET utf8");
	$settings = "SELECT * FROM settings";
	$registry->getObject('db')->executeQuery($settings);
	while ($setting = $registry->getObject('db')->getRows()) {
		$registry->storeSetting($setting['value'], $setting['key']);
	}
	$registry->createAndStoreObject('urlprocessor', 'url');
	$registry->createAndStoreObject('googleApi', 'google');
	$registry->createAndStoreObject('authenticate', 'auth');
	$registry->createAndStoreObject('renderer', 'render');
	$registry->createAndStoreObject('template', 'template');
	$registry->getObject('url')->getURLData();
	$controllers = array();
	$registry->getObject('db')->executeQuery("SELECT * FROM controllers WHERE active = 1");
	while ($controller = $registry->getObject('db')->getRows()) {
		$controllers[] = $controller['controller']; 
	}
	$registry->getObject('auth')->checkForAuthentication();
	$controller = $registry->getObject('url')->getURLBit(0);
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
	elseif ($registry->getObject('url')->getURLBit(0) == 'repository' && $registry->getObject('url')->getURLBit(1) == 'view') {
		require_once(FRAMEWORK_PATH . 'controllers/repository/controller.php');
		$controllerInc = $controller . 'Controller';
		$controller = new $controllerInc($registry, true);
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