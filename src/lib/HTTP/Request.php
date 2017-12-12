<?php

namespace reqc\HTTP;

use \Exception;


/**
 * The Request class is used for making HTTP Requests
 *
 * @author Cythral <talen@cythral.com>
 */
class Request {

	/** @var array an array of options **/
	private $options;

	/** @var Curl a connection handler **/
	private $con;

	/** @var bool whether or not the request is done or not, used for tracking 429s **/
	public $done = false;

	/** @var int how many attempts were made before getting a valid response **/
	public $attempts = 0;

	/** @var Response the response received from making the request **/
	public $response;

	private $transport = self::TRANSPORTS["CURL"];

	/** @var array an array of required options **/
	const REQUIRED_OPTIONS = [
		"url"
	];

	/** @var array an array of default options **/
	const DEFAULT_OPTIONS = [
		"url" => "",
		"method" => "GET",
		"headers" => [
			"content-type" => "application/x-www-form-urlencoded"
		],
		"handle-ratelimits" => true,
		"max-attempts" => 5,
		"json" => false,
		"use-fsockopen" => false
	];

	const TRANSPORTS = [
		"CURL" => "reqc\HTTP\Transport\Curl",
		"FSOCKOPEN" => "reqc\HTTP\Transport\Fsockopen"
	];

	/**
	 * Constructor, fired when a new request object is made.
	 *
	 * @param array $options different options to use for the request
	 */
	public function __construct(array $options) {
		$this->setupOptions($options);

		// revert to fsockopen if curl not available
		if(!class_exists($this->transport) || $this->options["use-fsockopen"]) $this->transport = self::TRANSPORTS["FSOCKOPEN"];

		while(!$this->done) $this->execute();
	}

	/**
	 * Validates and sets up the options property.
	 *
	 * @param array $options an array of options to validate and setup
	 */
	private function setupOptions(array $options) {
		// check for required options
		foreach(self::REQUIRED_OPTIONS as $option) {
			if(!array_key_exists($option, $options)) throw new Exception("Missing Option $option");
		}

		// setup options
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

	/**
	 * Executes the Request.  This will repeat until the done property is
	 * set to true.
	 */
	private function execute() {
		$this->attempts++;

		$this->con = new $this->transport($this->options["url"]);
		$this->con->setMethod($this->options["method"]);
		$this->con->setHeaders($this->options["headers"]);
		if(isset($this->options["data"])) $this->con->setBody($this->options["data"]);

		// get response
		$this->response = new Response($this->con->exec(), $this->options["json"], ($this->transport == self::TRANSPORTS["CURL"]) ? false : true);
		unset($this->con);

		// retry if rate limited
		if($this->options["handle-ratelimits"] &&
		   ($this->attempts + 1) != $this->options["max-attempts"] &&
	       $this->response->code == 429) {
			   sleep((Integer)$this->response->headers["retry-after"]);

		} else $this->done = true;
	}

	/**
	 * Returns the response body as a string. If it was parsed as json, this will reencode it.
	 *
	 * @return string the response body
	 */
	public function __toString() {
		return (is_string($this->response->body)) ? $this->response->body : json_encode($this->response->body);
	}
}
