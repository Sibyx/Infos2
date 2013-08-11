<?php
	if (!$this->registry->getObject('auth')->isLoggedIn()) {
		$form = array(
			'name'		=> 'Login',
			'action'	=> $this->registry->getSetting('siteurl') . '/authenticate/login',
			'enctype'	=> 'multipart/form-data',
			'method'	=> 'POST',
			'rows'		=> array (
				0	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'text',
							'name'		=> 'nick',
							'class'		=> 'twelve',
							'label'		=> 'OM6AW',
							'required'	=> 'required'
						)
					)
				),
				1	=> array (
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'password',
							'name'		=> 'password',
							'class'		=> 'twelve',
							'label'		=> 'Heslo',
							'required'	=> 'required'
						)
					)
				)
				
			),
			'submit'	=> array (
				'label'	=> 'Prihlásiť',
				'class'	=> 'button twelve'
			)
		);
		echo '<section class="columns twelve">' . "\n";
		echo '<header><h2><small>Prihlásenie</small></h2></header>' . "\n";
		$this->registry->getObject('render')->createForm($form);
		echo '</section>' . "\n";
	}
	else {
		echo '<section class="columns twelve">' . "\n";
		echo '<header><h2><small>' . $this->registry->getObject('auth')->getUser()->getNick() . '</small></h2></header>' . "\n";
		echo '<ul class="eleven side-nav offset-by-one">' . "\n";
		echo  '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/new">Nový článok</a></li>' . "\n";
		echo  '<li><a href="' . $this->registry->getSetting('siteurl') . '/gallery/new">Nový album</a></li>' . "\n";
		echo  '<li><a href="' . $this->registry->getSetting('siteurl') . '/profile/edit">Upraviť profil</a></li>' . "\n";
		echo  '<li class="divider"></li>' . "\n";
		echo  '<li><a href="' . $this->registry->getSetting('siteurl') . '/authenticate/logout">Odhlásiť sa</a></li>' . "\n";
		echo '</ul>' . "\n";
		echo '</section>' . "\n";
	}
	echo '<section class="columns twelve">' . "\n";
	echo '<header><h2><small>Kamera</small></h2></header>' . "\n";
	echo '<div class="text-center">' . "\n";
	echo '<img src="' . $this->registry->getSetting('siteurl') . '/views/' . $this->registry->getSetting('view') . '/images/offline.png" alt="Offline"/>' . "\n";
	echo '<p>Kamera je offline</p>' . "\n";
	echo '</div>' . "\n";
	echo '</section>' . "\n";
?>