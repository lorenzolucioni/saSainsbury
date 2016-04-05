<?php

class ConsoleExceptionClass extends Exception {
	protected $customMessage;

	function __construct($customMessage) {
		$this->customMessage = $customMessage;
	}

	public function getCustomMessage() {
		return $this->customMessage;
	}
} 

?>
