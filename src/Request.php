<?php

namespace reqc;

use \Exception;

class Request {

	private $options;
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

		while(!$this->done) $this->execute();
	}

	private function validateOptions() {
		// check for required options
		foreach(self::REQUIRED_OPTIONS as $option) {
			if(!array_key_exists($option, $this->options)) throw new Exception("Missing Option $option");
		}

		// lowercase header keys, easier to work with
		if(isset($this->options["headers"])) $this->options["headers"] = array_change_key_case($this->options["headers"]);

		// adjust content type header if json mode is on
		if(isset($this->options["json"]) && $this->options["json"] == true) {
			if(!isset($this->options["headers"])) $this->options["headers"] = [];
			$this->options["headers"]["content-type"] = "application/json";
		}

		// adjust data value
		if(isset($this->options["data"])) {

			// query string if not json
			if(!isset($this->options["headers"]["content-type"]) ||
			   strtolower($this->options["headers"]["content-type"]) != "application/json") {
					 $this->options["data"] = http_build_query($this->options["data"]);
			}

			// json encoded if json
			if(isset($this->options["headers"]["content-type"]) &&
				 strtolower($this->options["headers"]["content-type"] == "application/json") {
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

		if(isset($this->options["headers"])) $this->con->setOpt(CURLOPT_HTTPHEADER, $this->options["headers"]);
		if(isset($this->options["data"])) $this->con->setOpt(CURLOPT_POSTFIELDS, $this->options["data"]);

		$this->response = new Response($this->con->exec(), $this->options["json"] ?? false);

		unset($this->con);

		// retry if rate limited
		if($this->response->code == 429) sleep((Integer)$this->response->headers["retry-after"]);
		else $this->done = true;
	}

	public function __toString() {
		return $this->response->body;
	}
}
