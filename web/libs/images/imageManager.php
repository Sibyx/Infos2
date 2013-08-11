<?php
	/*
	 * 30.03.2013
	 * Class imageManager v1
	 * Uprava obrazkov cez Imagick
	 * CHANGELOG:
	 * 	- v1.0 [30.03.2013]: createTime, vytvaranie miniatur
	 * TODO:
	 *	- upload -> mozno, zatial to spravuje fileManager
	*/

class imageManager {

	private $uploadExtentions = array('png', 'jpg', 'jpeg', 'gif');
	private $uploadTypes = array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png');

	private $image;

	private $imgPath = '';
	private $imgExt = '';
	private $imgMime = '';
	private $imgSize = 0;
	private $imgName = '';
	
	private $width;
	private $height;
	
	public function __construct() {
		$this->image = new Imagick();
	}
	
	public function loadFromFile($path) {
		$this->imgPath = $path;
		$this->image->readImage($path);
		
		$info = getImageSize($path);
		$this->imgMime = $info['mime'];
		$type = exif_imagetype($path);
		
		$this->imgName = $this->image->getFilename();
		
		if($type == IMAGETYPE_JPEG) {
			$this->imageExt = '.jpg';
			
		}
		elseif($type == IMAGETYPE_GIF) {
			$this->imageExt = '.gif';
        }
		elseif($type == IMAGETYPE_PNG ) {
			$this->imageExt = '.png';
		}
		
		$this->imgName = basename($path, $this->imageExt);
		
		$info = $this->image->identifyImage();
		$this->width = $info['geometry']['width'];
		$this->height = $info['geometry']['height'];
		$this->imgSize = $this->image->getImageLength();
	}
	
	public function getWidth() {
		$info = $this->image->identifyImage();
		return $this->width;
	}
	
	public function getHeight() {
		return $this->height;
	}
	
	public function display() {
		header('Content-Type: ' . $this->imgMime);
		echo $this->image->getImageBlob();
	}

	public function getName() {
		return $this->imgName;
	}
	
	public function getExt() {
		return $this->imgExt;
	}
	
	public function getPath() {
		return $this->imgPath;
	}
	
	public function save($newPath = '') {
		$this->image->writeImage($newPath);
	}
	
	public function createThumb() {
		if ($this->width > $this->height) {
			$this->image->scaleImage(0, 130);
		}
		else {
			$this->image->scaleImage(170, 0);
		}
		
		$info = $this->image->identifyImage();
		$this->width = $info['geometry']['width'];
		$this->height = $info['geometry']['height'];
		
		if ($this->height > 130 || $this->width) {
			$this->image->cropImage(170, 130, 0, 0);
		}
		
		$this->imgSize = $this->image->getImageLength();
	}
}
?>