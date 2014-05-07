<?php
class GreetingServiceImpl {
	
	public function greetServer($input){
		//FieldVerifier is omitted
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$input = $this->escapeHtml($input);
		$userAgent = $this->escapeHtml($userAgent);
		
		return "Hello, " . $input . "!<br><br>I am running " . "PHP " . phpversion()
        . ".<br><br>It looks like you are using:<br>" . $userAgent;
	}
	
	private function escapeHtml($html){
		return htmlspecialchars($html);
	}
}