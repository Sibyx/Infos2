<?php
require_once(FRAMEWORK_PATH . 'libs/PHPMailer/class.phpmailer.php');
class Email {

	private $message;
	private $headers;
	private $recipient;
	private $subject;
	private $mail;
	
    public function __construct(Registry $registry)  {
		$this->registry = $registry;
		$this->mail = new PHPMailer(true);
		$this->mail->IsSMTP();
		$this->mail->Host = "smtp.websupport.sk";
		$this->mail->SMTPDebug = 0;
		$this->mail->SMTPAuth = true;
		$this->mail->SMTPSecure = "ssl";
		$this->mail->Port = 465;
		$this->mail->Username = "robot@cncgravirovacky.sk";
		$this->mail->Password = "andromeda255";
	}
	
	public function setRecipient($email) {
		$this->mail->AddAddress($email);
	}
	
	public function buildFromText($message){
		$this->message .= $message;
	}
	
	public function setSender($email = '', $name = '') {
		if($email == '' && $name == '') {
			$this->mail->SetFrom($this->registry->getSetting('adminEmailAddress'), $this->registry->getSetting('sitename'));
			return true;
		}
		else {
			$this->mail->SetFrom($email, $name);
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
	}
	
	public function setSubject($subject) {
		$this->mail->Subject = $subject;
	}
	
	public function send() {
		$this->mail->CharSet="UTF-8";
		$this->mail->MsgHTML($this->message);
		$this->mail->Send();
	}
}
?>