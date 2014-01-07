<?php
require_once(FRAMEWORK_PATH . 'libs/PHPMailer/class.phpmailer.php');
class Email {

	private $message;
	private $mail;
	
    public function __construct(Registry $registry)  {
		$this->registry = $registry;
		$this->mail = new PHPMailer(true);
		$this->mail->IsSMTP();
		$this->mail->Host = $this->registry->getSetting('emailHost');
		$this->mail->SMTPDebug = 0;
		$this->mail->SMTPAuth = true;
		$this->mail->SMTPSecure = "ssl";
		$this->mail->Port = $this->registry->getSetting('emailPort');
		$this->mail->Username = $this->registry->getSetting('emailUsername');
		$this->mail->Password = $this->registry->getSetting('emailPassword');
	}
	
	public function setRecipient($email) {
		$this->mail->AddAddress($email);
	}

    public function addBCC($email) {
        $this->mail->addBCC($email);
    }
	
	public function buildFromText($message){
		$this->message .= $message;
	}
	
	public function setSender($email = '', $name = '') {
		if($email == '' && $name == '') {
			$this->mail->SetFrom($this->registry->getSetting('emailUsername'), 'GVPT Newsletter');
			return true;
		}
		else {
			$this->mail->SetFrom($email, $name);
			$this->mail->addReplyTo($email, $name);
			return true;
		}
	}
	
	public function buildFromTemplate($templateName) {
		$content = "";
		if(file_exists(FRAMEWORK_PATH . 'mailTemplates/' . $templateName) == true ) {
			$content .= file_get_contents(FRAMEWORK_PATH . 'mailTemplates/' . $templateName);
		}
		$this->message =  $content;
	}

	public function replaceTags($tags) {
		if(sizeof($tags) > 0) {
			foreach($tags as $tag => $data) {
				if(!is_array($data)) {
					$newContent = str_replace('{' . $tag . '}', $data, $this->message);
					$this->message = $newContent;
				}
			}
		}
        $this->replaceLangTags();
	}

    private function replaceLangTags() {
        $langTags = parse_ini_file(FRAMEWORK_PATH . 'views/' . $this->registry->getSetting('view') . '/lang/' . $this->registry->getSetting('lang') . '.lang.ini', false);
        foreach($langTags as $tag => $data) {
            if(!is_array($data)) {
                $this->message = str_replace('{' . $tag . '}', $data, $this->message);
            }
        }
    }
	
	public function setSubject($subject) {
		$this->mail->Subject = $subject;
	}
	
	public function send() {
		$this->mail->CharSet="UTF-8";
		$this->mail->MsgHTML($this->message);
		return $this->mail->Send();
	}
}
?>