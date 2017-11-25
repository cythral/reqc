<?php

namespace reqc;

class Request {
	private $options;
	private $ch;
	
	public $done = false;
	public $attempts = 0;
	public $response;

	const REQUIRED_OPTIONS = [
		"url",
		"method"
	];

	public function __construct($options) {
		$this->options = $options;
		$this->validateOptions();

		while(!$this->done) {
			$this->execute();
		}
	}

	private function validateOptions() {
		foreach(self::REQUIRED_OPTIONS as $option) {
			if(!array_key_exists($option, $this->options)) throw new \Exception("Missing Option $option");
		}

		// adjust content type header if json mode is on
		if(isset($this->options["json"]) && $this->options["json"] == true) {
			if(!isset($this->options["headers"])) $this->options["headers"] = [];
			$this->options["headers"]["content-type"] = "application/json";
		}
	}

	private function execute() {
		$this->attempts++;
		$this->ch = curl_init($this->options["url"]);

		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->options["method"]);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, true);

		if(isset($this->options["headers"])) {
			$this->options["headers"] = array_change_key_case($this->options["headers"]);
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->options["headers"]);
		}


		if(isset($this->options["data"])) {
			if(!isset($this->options["headers"]["content-type"]) || 
				strtolower($this->options["headers"]["content-type"]) != "application/json") {

				$this->options["data"] = http_build_query($this->options["data"]);
			}

			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->options["data"]);
		}
		
		
		
		$this->response = new Response(curl_exec($this->ch));
		curl_close($this->ch);

		if($this->response->code == 429) sleep((Integer)$this->response->headers["retry-after"]);
		else $this->done = true;
	}

	public function __toString() {
		return $this->response->body;
	}
}