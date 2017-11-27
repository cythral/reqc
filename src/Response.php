<?php

namespace reqc;

class Response {
	private $data;
	private $json;

	public $body;
	public $headers = [];
	public $code;
	public $httpVersion;

	public function __construct($data, $json = false) {
		$this->data = $data;
		$this->json = $json;
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

		// fix content type header
		if(isset($this->headers["content-type"]) && strpos($this->headers["content-type"], ";")) {
			$this->headers["content-type"] = strtok($this->headers["content-type"], ";");
			if(!isset($this->headers["charset"])) $this->headers["charset"] = substr(strstr(strtok(";"), "="), 1);
		}

		if($this->json && strtolower($this->headers["content-type"]) == "application/json") {
			$this->body = json_decode($this->body);
		}

		unset($this->data, $head, $lead);
	}

	public function __toString() {
		return $this->body;
	}
}