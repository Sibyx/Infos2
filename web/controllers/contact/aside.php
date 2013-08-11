<?php
	if ($this->registry->getObject('auth')->isLoggedIn()) {
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
?>