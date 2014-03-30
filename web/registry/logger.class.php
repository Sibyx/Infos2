<?php
/*
 * 04.10.2013
 * Class Logger v1.1
 * Objekt na zaznamenavanie aktivit
 * CHANGELOG:
 * 	- v1.0 [14.04.2013]: createTime
 *	- v1.0 [18.05.2013]: dokumentacia, navratova hodnota
 *  - v1.1 [04.10.2013]: log location
 *  - v1.2 [28.02.2014]: log user
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
			if (is_object($this->registry->auth)) {
				if ($this->registry->auth->isLoggedIn()) {
					$values[] = $this->registry->auth->getUser()->getEmail();
				}
				else {
					$values[] = 'No User';
				}
			}
			else {
				$values[] = 'No User';
			}
			$values[] = $this->registry->db->sanitizeData($message);
			return $this->registry->db->callRutine('insertLog', $values);
		}
		else {
			return false;
		}
	}

	public function __deconstruct() {
		fclose($this->file);
	}
}
?>