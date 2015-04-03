<?php

class Notification {
	public $type;
	public $header;
	public $message;

	public function __construct($type, $header, $message) {
		$this->type = $type;
		$this->header = $header;
		$this->message = $message;
	}
}