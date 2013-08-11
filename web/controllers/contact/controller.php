<?php
class contactController {
	private $registry;
	
	public function __construct(Registry $registry) {
		$this->registry = $registry;
		if (isset($_POST['contact_email'])) {
			require_once(FRAMEWORK_PATH . 'models/email.php');
			$email = new Email($this->registry);
			$tags = array();
			$tags['title'] = 'Správa z OM3KHE';
			$tags['header'] = htmlspecialchars($_POST['contact_subject']);
			$tags['senderName'] = htmlspecialchars($_POST['contact_name']);
			$tags['senderIP'] = $_SERVER['REMOTE_ADDR'];
			$tags['message'] = htmlspecialchars($_POST['contact_message']);
			$tags['senderMail'] = $_POST['contact_email'];
			$tags['date'] = date("j. n. Y H:i");
			$email->setRecipient($this->registry->getSetting('adminEmailAddress'), $this->registry->getSetting('adminFullName'));
			$email->setSubject(htmlspecialchars($_POST['contact_subject']));
			$email->buildFromTemplate('contact.htm');
			$email->replaceTags($tags);
			$email->setSender($_POST['contact_email'], htmlspecialchars($_POST['contact_name']));
		}
		else {
			$this->uiContact();
		}
	}

	private function uiContact() {
		echo '<div class="twelve columns">' . "\n";
		echo '<div class="row">' . "\n";
		echo '<section class="six columns">' . "\n";
		echo '<header><h2><small>Rádioklub OM3KHE</small></h2></header>' . "\n";
		echo '<address>' . "\n";
		echo 'Občianske združenie <br />' . "\n";
		echo '038 53 Turany <br />' . "\n";
		echo 'e-mail: om3khe@om3khe.sk <br />' . "\n";
		echo 'IČO 17057264 <br />' . "\n";
		echo '</address>' . "\n";
		echo '</section>' . "\n";
		echo '<section class="six columns">' . "\n";
		echo '<header><h2><small>Doručovanie pošty</small></h2></header>' . "\n";
		echo '<address>' . "\n";
		echo 'Ing. Jozef Vojtek <br />' . "\n";
		echo 'Kol. Hviezda 104<br />' . "\n";
		echo '036 08 Martin<br />' . "\n";
		echo '</address>' . "\n";
		echo '</section>' . "\n";
		echo '</div>' . "\n";
		echo '<div class="row">' . "\n";
		echo '<section class="twelve columns">' . "\n";
		echo '<header><h2><small>Napíšte nám!</small></h2></header>' . "\n";
		$form = array(
			'name'		=> 'Contact',
			'action'	=> $this->registry->getSetting('siteurl') . '/contact',
			'enctype'	=> 'multipart/form-data',
			'method'	=> 'POST',
			'rows'		=> array (
				0	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'text',
							'name'		=> 'name',
							'class'		=> 'seven',
							'label'		=> 'Zadajte svoje meno/značku',
							'required'	=> 'required'
						)
					)
				),
				1	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'email',
							'name'		=> 'email',
							'class'		=> 'seven',
							'label'		=> 'Zadaj Tvoj e-mail',
							'required'	=> 'required'
						)
					)
				),
				2	=> array(
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'input',
							'type'		=> 'text',
							'name'		=> 'subject',
							'class'		=> 'seven',
							'label'		=> 'Predmet',
							'required'	=> 'required'
						)
					)
				),				
				3	=> array (
					'elements'	=> array (
						0	=> array(
							'tag'		=> 'textarea',
							'name'		=> 'message',
							'class'		=> 'twelve contactTextarea',
							'required'	=> 'required'
						)
					)
				)
			),
			'submit'	=> array (
				'label'	=> 'Odoslať',
				'class'	=> 'button six offset-by-three'
			)
		);
		$this->registry->getObject('render')->createForm($form);
		echo '</section>' . "\n";
		echo '</div>' . "\n";
		echo '</div>' . "\n";
		/*echo '<aside class="three columns">' . "\n";
			include 'aside.php';
		echo '</aside>' . "\n";*/
	}
}
?>