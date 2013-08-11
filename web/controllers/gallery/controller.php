<?php
class galleryController {
	
	public function __construct($registry){
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		switch(isset($urlBits[1]) ? $urlBits[1] : '') {
			case 'view':
				$this->viewAlbum(intval($urlBits[2]));
			break;
			case 'edit':
				$this->editAlbum(intval($urlBits[2]));
			break;
			case 'new':
				$this->newAlbum();
			break;
			case 'uploadPhotos':
				$this->uploadPhotos(intval($urlBits[2]));
			break;
			default:				
				$this->listAlbums(intval($urlBits[1]));
			break;
		}
	}
	
	private function listAlbums($offset) {
		echo '<div class="nine columns">' . "\n";
		echo '<header><h2><small>Fotogaléria</small></h2></header>' . "\n";
		require_once(FRAMEWORK_PATH . 'models/albums.php');
		require_once(FRAMEWORK_PATH . 'models/photos.php');
		$photos = new Photos($this->registry);
		$albums = new Albums($this->registry);
		$pagination = $albums->listAlbumYears($offset);
		if ($pagination->getNumRowsPage() > 0) {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
				echo '<section>' . "\n";
				echo '<header><h3><small>' . $row['album_year'] . '</small></h3></header>' . "\n";
				echo '<ul class="block-grid four-up mobile">' . "\n";
				$cache = $albums->listAlbumByYear($row['album_year']);
				while ($data = $this->registry->getObject('db')->resultsFromCache($cache)) {
					$photoCache = $photos->randomPhoto($data['id_album']);
					$photoData = $this->registry->getObject('db')->resultsFromCache($photoCache);
					echo '<li>' . "\n";
						echo '<div class="albumIcon">' . "\n";
						echo '<a href="' . $this->registry->getSetting('siteurl') . '/gallery/view/' . $data['id_album'] . '"><img src="' . $this->registry->getSetting('siteurl') . "/" . $this->registry->getSetting('upload_dir') .  "/gallery/" . $data['id_album'] . "/thumb/" . $photoData['photo_location'] . '" alt="' . $data['album_title'] . '" class="photoPreview"/></a>' . "\n";
						echo '<div><span>' . $data['album_title']  . '</span></div>' . "\n";
						echo '</div>' . "\n";
					echo '</li>' . "\n";
				}
				echo '</ul>' . "\n";
				echo '</section>' . "\n";
			}
			echo '<div class="pagination-centered">' . "\n";
			echo '<ul class="pagination">' . "\n";
			if ($pagination->isFirst()) {
				echo '<li class="arrow unavailable"><a href="">&laquo;</a></li>' . "\n";
			}
			else {
				echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/gallery/' . ($pagination->getCurrentPage()-2) . '">&laquo;</a></li>' . "\n";
			}
			for ($i = 1;$i <= $pagination->getNumPages(); $i++) {
				if ($i == $pagination->getCurrentPage()) {
					echo '<li class="current"><a href="' . $this->registry->getSetting('siteurl') . '/gallery/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
				}
				else {
					echo '<li><a href="' . $this->registry->getSetting('siteurl') . '/gallery/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
				}
			}
			if ($pagination->isLast()) {
				echo '<li class="arrow unavailable"><a href="">&raquo;</a></li>' . "\n";
			}
			else {
				echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/gallery/' . ($pagination->getCurrentPage()) . '">&raquo;</a></li>' . "\n";
			}
			echo '</ul>' . "\n";
			echo '</div>' . "\n";
		}
		else {
			echo '<div class="alert-box columns six centered">Žiadne albumy</div>' . "\n";
		}
		echo '</div>' . "\n";
		
		echo '<aside class="three columns">' . "\n";
			include 'aside.php';
		echo '</aside>' . "\n";
	}
	
	private function viewAlbum($albumId) {
		echo '<div class="nine columns">' . "\n";
			require_once(FRAMEWORK_PATH . 'models/album.php');
			require_once(FRAMEWORK_PATH . 'models/photos.php');
			$album = new Album($this->registry, $albumId);
			if ($album->isValid()) {
				$data = $album->toArray();
				echo '<h2><small>' . $data['title'] . ' - ' . $data['year'] . '</small></h2>' . "\n";
				$photos = new Photos($this->registry);
				$cache = $photos->listPhotos($albumId);
				echo '<ul class="block-grid four-up mobile" data-clearing>' . "\n";
				while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
					$urlOrigin = $this->registry->getSetting('siteurl') . "/" . $this->registry->getSetting('upload_dir') .  "/gallery/$albumId/origin/" . $row['photo_location'];
					$urlThumb = $this->registry->getSetting('siteurl') . "/" . $this->registry->getSetting('upload_dir') .  "/gallery/$albumId/thumb/" . $row['photo_location'];
					echo '<li><a href="' . $urlOrigin . '" class="th"><img src="' . $urlThumb . '" class="photoPreview" /></a></li>' . "\n";
				}
				echo '</ul>' . "\n";
			}
			else {
				echo '<div class="alert-box alert columns six centered">Album neexistuje!</div>' . "\n";
			}
		echo '</div>' . "\n";
		
		echo '<aside class="three columns">' . "\n";
			include 'aside.php';
		echo '</aside>' . "\n";
	}
	
	private function newAlbum() {
		if (isset($_POST['newAlbum_title'])) {
			require_once(FRAMEWORK_PATH . 'models/album.php');
			$album = new Album($this->registry);
			$album->setTitle($_POST['newAlbum_title']);
			$album->setYear($_POST['newAlbum_year']);
			$album->save();
			$redirectBits[] = 'gallery';
			$redirectBits[] = 'uploadPhotos';
			$redirectBits[] = $album->getId();
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Album bol vytvorený!', 'alert-box columns six centered');
		}
		else {
			$this->uiNew();
		}
	}
	
	private function uiNew() {
		$form = array(
			'name'		=> 'NewAlbum',
			'action'	=> $this->registry->getSetting('siteurl') . '/gallery/new',
			'enctype'	=> 'multipart/form-data',
			'method'	=> 'post',
			'rows'		=> array (
				0	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'text',
							'name'		=> 'title',
							'class'		=> 'six offset-by-three',
							'label'		=> 'Titulok',
							'required'	=> 'required'
						)
					)
				),
				1	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'text',
							'name'		=> 'year',
							'class'		=> 'six offset-by-three',
							'label'		=> 'Rok',
							'required'	=> 'required'
						)
					)
				)		
			),
			'submit'	=> array (
				'label'	=> 'Vytvoriť',
				'class'	=> 'button four centered offset-by-four mt'
			)
		);
		echo '<section>' . "\n";
		echo '<div class="twelve columns">' . "\n";
		echo '<header><h1><small>Nový album</small></h1></header>' . "\n";
		$this->registry->getObject('render')->createForm($form);
		echo '</div>' . "\n";
		echo '</section>' . "\n";
	}
	
	private function checkDir($path) {
		if (file_exists($path) && is_dir($path)) {
			return true;
		}
		else {
			return mkdir($path);
		}
	}
	
	private function uploadPhotos($idAlbum) {
		if (isset($_POST['addPhotos_albumId'])) {
			require_once(FRAMEWORK_PATH . 'models/photo.php');
			require_once(FRAMEWORK_PATH . 'libs/files/fileManager.php');
			require_once(FRAMEWORK_PATH . 'libs/images/imageManager.php');
			$newPath = FRAMEWORK_PATH . $this->registry->getSetting('upload_dir') . '/gallery/';
			if ($this->checkDir($newPath . $_POST['addPhotos_albumId'])) {
				$this->checkDir($newPath . $_POST['addPhotos_albumId'] . "/origin");
				$this->checkDir($newPath . $_POST['addPhotos_albumId'] . "/thumb");
				$image = new imageManager();
				foreach ($_FILES['addPhotos_photos']["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {
						$file = new FileManager();
						$file->setFileName($_FILES['addPhotos_photos']["name"][$key]);
						$file->setFileMime($_FILES['addPhotos_photos']["type"][$key]);
						$file->setFileSize($_FILES['addPhotos_photos']["size"][$key]);
						$file->setFilePath($_FILES['addPhotos_photos']["tmp_name"][$key]);
						if ($file->isValid()) {
							$file->moveFile($newPath . $_POST['addPhotos_albumId'] . '/origin/');
							$image->loadFromFile($file->getFilePath());
							$image->createThumb();
							$image->save($newPath . $_POST['addPhotos_albumId'] . '/thumb/' . $file->getFileName());
							$photo = new Photo($this->registry);
							$photo->setPath($file->getFileName());
							$photo->setMime($_FILES['addPhotos_photos']["type"][$key]);
							$photo->setAlbumId($_POST['addPhotos_albumId']);
							$photo->save();
						}
					}
				}
			}
		}
		else {
			$this->uiUploadPhotos($idAlbum);
		}
	}
	
	private function uiUploadPhotos($idAlbum) {
		$formCustom = <<<EOM
<br />
<table class="twelve invisible" id="filesTable">
	<thead>
		<tr>
			<th>Názov súboru</th>
			<th>Typ</th>
			<th>Veľkosť</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody id="filesList">
	</tbody>
	<tfoot id="filesFooter">
	</tfoot>
</table>
<br />
<div id="progressbar"></div>

<div id="infoModal" class="reveal-modal medium">
<h2>Nastala chyba!</h2>
<p class="lead">Nastala chyba pri uploade súboru.</p>
<a class="close-reveal-modal">&#215;</a>
</div>

EOM;
		$form = array(
			'name'		=> 'AddPhotos',
			'action'	=> $this->registry->getSetting('siteurl') . '/gallery/uploadPhotos/' . $idAlbum,
			'enctype'	=> 'multipart/form-data',
			'method'	=> 'post',
			'rows'		=> array (
				0	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'file',
							'name'		=> 'photos[]',
							'id'		=> 'photos',
							'class'		=> 'six',
							'label'		=> 'Files',
							'required'	=> 'required',
							'custom'	=> 'multiple'
						),
						1	=> array(
							'tag'		=> 'input',
							'type'		=> 'hidden',
							'name'		=> 'albumId',
							'value'		=> $idAlbum
						)
					)
				),
			),
			'custom'	=> $formCustom,
			'submit'	=> array (
				'label'	=> 'Pridať',
				'class'	=> 'button four centered offset-by-four mt invisible'
			)
		);
		echo '<section>' . "\n";
		echo '<div class="twelve columns">' . "\n";
		echo '<header><h1><small>Pridať fotky</small></h1></header>' . "\n";
		$this->registry->getObject('render')->createForm($form);
		echo '</div>' . "\n";
		echo '</section>' . "\n";
	}

}
?>