<?php
/*
 * 04.10.2013
 * Class Logger v1.1
 * Objekt na zaznamenavanie aktivit
 * CHANGELOG:
 * 	- v1.0 [14.04.2013]: createTime
 *	- v1.0 [18.05.2013]: dokumentacia, navratova hodnota
 *  - v1.1 [04.10.2013]: log location
*/
class Logger {

	private $file;
    private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		$this->file = fopen(FRAMEWORK_PATH . 'framework.log', 'a');
	}
	/*
	 * Insert log message to DB or File
	 * @param $dest - FILE|SQL
	 * @param $type - ERR|INF|WAR
	 * @param $location - call location
	 * @param $message - message
	 * @result bool - uspech?
	 * insertLog('SQL', 'ERR', 'Logger', 'Funny message!');
	*/
	public function insertLog($dest, $type, $location, $message) {
		if ($dest == 'FILE') {
			$logMessage = '';
			$logMessage .= date("d.m.Y H:i:s");
			$logMessage .= " - [$type] - [$location] - $message \n";
			return fwrite($this->file, $logMessage);
		}
		elseif ($dest == 'SQL') {
			$values = array();
			$values[] = $type;
            $values[] = $location;
			$values[] = $this->registry->getObject('db')->sanitizeData($message);
			return $this->registry->getObject('db')->callRutine('insertLog', $values);
		}
		else {
			return false;
		}
	}

	private function sendReport() {
		return true;
	}

	public function __deconstruct() {
		fclose($this->file);
	}
}
?>