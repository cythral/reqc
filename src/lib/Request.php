<?php

namespace reqc;

use \Exception;

class Request {

	private $options;
	public $done = false;
	public $attempts = 0;
	public $response;

	const REQUIRED_OPTIONS = [
		"url"
	];

	const DEFAULT_OPTIONS = [
		"url" => "",
		"method" => "GET",
		"headers" => [
			"content-type" => "application/x-www-form-urlencoded"
		],
		"handle-ratelimits" => true,
		"max-attempts" => 5,
		"json" => false
	];

	public function __construct($options) {
		$this->setupOptions($options);

		while(!$this->done) $this->execute();
	}

	private function setupOptions($options) {
		// check for required options
		foreach(self::REQUIRED_OPTIONS as $option) {
			if(!array_key_exists($option, $options)) throw new Exception("Missing Option $option");
		}

		$this->options = array_replace_recursive(self::DEFAULT_OPTIONS, $options);
		$this->options["headers"] = array_change_key_case($this->options["headers"]);
		if($this->options["json"]) $this->options["headers"]["content-type"] = "application/json";

		// adjust data value based on content-type if present
		if(isset($this->options["data"])) {
			if(strtolower($this->options["headers"]["content-type"]) == "application/x-www-form-urlencoded") {
				if(!is_array($this->options["data"])) throw new Exception("Expected array for data option when content type is application/x-www-form-urlencoded");
				$this->options["data"] = http_build_query($this->options["data"]); // query string
			}

			if(strtolower($this->options["headers"]["content-type"]) == "application/json") {
				if(!is_array($this->options["data"])) throw new Exception("Expected array for data option when content type is application/json");
		  		$this->options["data"] = json_encode($this->options["data"]);
			}
		}
	}

	private function execute() {
		$this->attempts++;

		$this->con = new Curl($this->options["url"]);
		$this->con->setOpt(CURLOPT_CUSTOMREQUEST, $this->options["method"]);
		$this->con->setOpt(CURLOPT_RETURNTRANSFER, true);
		$this->con->setOpt(CURLOPT_HEADER, true);
		$this->con->setOpt(CURLOPT_HTTPHEADER, $this->options["headers"]);
		if(isset($this->options["data"])) $this->con->setOpt(CURLOPT_POSTFIELDS, $this->options["data"]);

		// get response
		$this->response = new Response($this->con->exec(), $this->options["json"]);
		unset($this->con);

		// retry if rate limited
		if($this->options["handle-ratelimits"] && ($this->attempts + 1) != $this->options["max-attempts"]) {
			if($this->response->code == 429) sleep((Integer)$this->response->headers["retry-after"]);
			else $this->done = true;
		}
	}

	public function __toString() {
		return $this->response->body;
	}
}
