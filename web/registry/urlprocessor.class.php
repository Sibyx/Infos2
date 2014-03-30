<?php
class urlprocessor {
	
	private $urlBits = array();
	private $urlPath;
	private $registry;
	
	public function setURLPath($path) {
		$this->urlPath = $path;
	}
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
	}
	
	public function getURLData() {
        $urlData = (isset($_GET['page'])) ? $_GET['page'] : '';
		$this->urlPath = $urlData;
		if ($urlData == '') {
			$this->urlBits[] =  '';
			$this->urlPath = '';
		}
		else {
			$data = explode('/', $urlData);
			while (!empty($data) && strlen(reset($data)) === 0) {
				array_shift($data);
			}
			while (!empty($data) && strlen(end($data)) === 0) {
				array_pop($data);
			}
			$this->urlBits = $this->array_trim($data);
		}
	}
	
	public function getURLBits() {
		return $this->urlBits;
	}
	
	public function getURLBit($which) {
		return (isset($this->urlBits[$which])) ? $this->urlBits[$which] : 0;
	}
	
	public function getURLPath() {
		return $this->urlPath;
	}
	
	public function getCurrentURL() {
		return $this->registry->getSetting('siteurl') . '/' . $this->urlPath;
	}
	
	public function buildURL($bits, $qs='') {
		$rest = '';
		foreach ($bits as $bit) {
			$rest .= $bit . '/';
		}
		$rest = ($qs != '') ? $rest . '?&' . $qs : $rest;
		return $this->registry->getSetting('siteurl') . '/' . $rest;
	}
	
	private function array_trim($array) {
		while (!empty($array) && strlen(reset($array)) === 0){
			array_shift($array);
		}
		while (!empty($array) && strlen(end($array)) === 0) {
			array_pop( $array );
	    }
		return $array;
	}

	public function redirectURL($url, $message = '', $class = '') {
		$tags = array();
		$tags['class'] = $class;
		$tags['message'] = $message;
		$tags['url'] = $url;
		$tags['title'] = 'Redirect';
		$tags['meta-description'] = "Redirect";
		$tags['logoutGoogle'] = "";
		$this->registry->template->buildFromTemplate('redirect');
		$this->registry->template->replaceTags($tags);
		echo $this->registry->template->parseOutput();
	}
}
?>