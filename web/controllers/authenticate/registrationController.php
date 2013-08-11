<?php
/* Registracny kontroler 0.1
 * pripraveny registracny kontroler
 * OTESTOVAT -> pravdepodobne hotovo
 * pridat typ error
*/
class registrationController {

	private $registry;
	private $fields = array('first_name' => 'Meno', 'last_name' => 'Priezvisko', 'password' => 'Heslo', 'password_confirm' => 'Potvredenie heslo', 'email' => 'E-mail', 'class' => 'Trieda');
	private $registrationErrors = array();
	private $registrationErrorLabels = array();
	private $submitedValues = array();
	private $sanitizedValues = array();
	private $salt = '#lfo587Tr4hg@fdm';
	
	public function __construct(Registry $registry, $ajax) {
		$this->registry = $registry;
		if($ajax) {
			$result = array();
			if($this->checkRegistration() == true) {
				$userId = $this->processRegistration();
				$result['error'] = false;
				$result['userId'] = $userId;
			}
			else {
				$result['error'] = true;
				$result['error_type'] = 'registration';
				$result['registrationErrors'] = $this->registrationErrors;
				$result['registrationErrorLabels'] = $this->registrationErrorLabels;
			}
			echo json_encode($result);
		}
		else {
			$this->uiRegister();
		}
	}
	
	private function createHash($pass) {
		return hash('sha256', $pass . $salt);
	}
	
	private function checkRegistration() {
		$allClear = true;
		foreach($this->fields as $field => $value) {
			if (!isset($_POST['register_' . $field]) || $_POST['register_' . $field] == '') {
				$allClear = false;
				$this->registrationErrors[] = 'Prosim vyplnte pole ' . $value;
				$this->registrationErrorLabels['register_' . $field . '_label'] = 'error';
			}
		}
		if ($_POST['register_password'] != $_POST['register_password_confirm']) {
			$allClear = false;
			$this->registrationErrors[] = 'Hesla sa nezhoduju';
			$this->registrationErrorLabels['register_password_label'] = 'error';
			$this->registrationErrorLabels['register_password_confirm_label'] = 'error';
		}
		if (strlen($_POST['register_password']) < 5) {
			$allClear = false;
			$this->registrationErrors[] = 'Zadane heslo musi obsahovat aspon 5 znakov';
			$this->registrationErrorLabels['register_password_label'] = 'error';
			$this->registrationErrorLabels['register_password_confirm_label'] = 'error';
		}
		if(strpos((urldecode($_POST['register_email'])),"\r") !== false || strpos((urldecode($_POST['register_email'])),"\n" ) !== false) {
			$allClear = false;
			$this->registrationErrors[] = 'Zadana a-mailova adresa nie je platna';
			$this->registrationErrorLabels['register_email_label'] = 'error';
		}
		if(!preg_match( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})^", $_POST[ 'register_email' ])) {
			$allClear = false;
			$this->registrationErrors[] = 'Zadana a-mailova adresa nie je platna';
			$this->registrationErrorLabels['register_email_label'] = 'error';
		}
		/*if (!isset($_POST['register_term']) || $_POST['register_term'] != 1) {
			$allClear = false;
			$this->registrationErrors[] = 'Akceptujte prosim podmienky pouzivania';
			$this->registrationErrorLabels['register_term_label'] = 'error';
		}*/
		$e = $this->registry->getObject('db')->sanitizeData($_POST['register_email']);
		$first_name = $this->registry->getObject('db')->sanitizeData($_POST['register_first_name']);
		$last_name = $this->registry->getObject('db')->sanitizeData($_POST['register_last_name']);
		$class_id = $this->registry->getObject('db')->sanitizeData($_POST['register_class']);
		$sql = "SELECT * FROM users WHERE usr_email = '$e'";
		$this->registry->getObject('db')->executeQuery($sql);
		if ($this->registry->getSetting('captcha.enabled') == 1) {
			require_once FRAMEWORK_PATH . 'libs/recaptchalib.php';
			$privatekey = getSetting('captcha.privatekey');
			$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$allClear = false;
				$this->registrationErrors[] = 'Zle zadane reCaptcha. Odpoved z reCaptcha: ' . $resp->error;
			}
		}
		if ($allClear == true) {
			$this->sanitizedValues['usr_first_name'] = $first_name;
			$this->sanitizedValues['usr_last_name'] = $last_name;
			$this->sanitizedValues['id_class'] = $class_id;
			$this->sanitizedValues['usr_email'] = $e;
			$this->sanitizedValues['usr_password'] = $this->createHash($_POST['register_password']);
			$this->sanitizedValues['usr_status'] = 'inactive';
			$this->sanitizedValues['id_role'] = 3;
			return true;
		}
	}
	
	private function processRegistration() {
		$this->registry->getObject('db')->insertRecords('users', $this->sanitizedValues);
		$uid = $this->registry->getObject('db')->lastInsertID();
		return $uid;
		$redirectBits = array();
		$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Registracia bola uspesna! Tvoj ucet caka na aktivaciu!', 'success');
	}
	
	private function uiRegister() {
		$fields = array_keys($this->fields);
		echo <<<OEM
			<script type="text/javascript">
				var RecaptchaOptions = {
				theme : 'clean'
				};
			</script>
OEM;
		echo '<div id="register_form">' . "\n";
		echo '<form method="post" name="registrationForm" id="registrationForm">' . "\n";
		foreach ($this->fields as $field => $value) {
			echo '<div class="field">' . "\n";
			echo '<label for="register_' . $field . '">' . $value . ':</label>' . "\n";
			switch ($field) {
				case 'email':
					echo '<input type="email" name="register_' . $field . '" id="register_' . $field . '" required="required" />' . "\n";
				break;
				case 'class':
					echo '<select name="register_' . $field . '" id="register_' . $field .  '" required="required">' . "\n";
					$this->registry->getObject('db')->executeQuery("SELECT * FROM classes;");
					while ($option = $this->registry->getObject('db')->getRows()) {
						echo '<option value="' . $option['id_class'] . '">' . $option['cl_title'] . '</option>' . "\n";
					}
					echo '</select>' . "\n";
				break;
				case 'password':
				case 'password_confirm':
				echo '<input type="password" name="register_' . $field . '" id="register_' . $field . '" required="required"/>' . "\n";
				break;
				default:
					echo '<input type="text" name="register_' . $field . '" id="register_' . $field . '" required="required"/>' . "\n";
				break;
			}
			echo '</div>' . "\n";
		}
		if ($this->registry->getSetting('captcha.enabled') == 1) {
			require_once FRAMEWORK_PATH . 'libs/recaptchalib.php';
			echo recaptcha_get_html($this->registry->getSetting('captcha.publickey'), $error);
			echo '<br />' . "\n";
		}
		echo '<button type="submit" id="register_submit" name="register_submit">Registruj!</button>' . "\n";
		echo "</form> \n";
		echo '<div id="result"></div>' . "\n";
		echo "</div> \n";
	}
}
?>