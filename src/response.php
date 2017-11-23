<?php

namespace reqc;

if(!defined("REQC")) die;

class Response {
	private $data;
	
	public $body;
	public $headers = [];
	public $code;
	public $httpVersion;

	public function __construct($data) {
		$this->data = $data;
		$this->parse();
	}

	private function parse() {
		// remove first line
		$lead = strstr($this->data, "\n", true);
		$this->data = substr(strstr($this->data, "\n"), 1);

		// parse first line
		$pieces = explode(" ", $lead);
		$this->httpVersion = (Integer)trim(substr(strstr($pieces[0], "/"), 1));
		$this->code = (Integer)trim($pieces[1]);

		// parse the rest
		$head = strstr($this->data, "\r\n\r\n", true);
		$this->body = substr(strstr($this->data, "\r\n\r\n"), 4);

		foreach(explode("\n", $head) as $line) {
			$this->headers[strtolower(strstr($line, ":", true))] = trim(substr(strstr($line, ":"), 2));
		}

		unset($this->data, $head, $lead);
	}

	public function __toString() {
		return $this->body;
	}
}