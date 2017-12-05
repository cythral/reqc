<?php

namespace reqc\Client\HTTP;

/**
 * The Response class parses data returned from a Request object.
 *
 * @author Cythral <talen@cythral.com>
 */
class Response {
	/** @var string data returned from a request object */
	private $data;

	/** @var bool whether or not to parse the response body as json */
	private $json;

	/** @var string a parsed response body **/
	public $body;

	/** @var array an array of parsed headers **/
	public $headers = [];

	/** @var int the parsed http response code  **/
	public $code;

	/** @var int the parsed http version **/
	public $httpVersion;

	/**
	 * Constructor, fired when a new response object is created.
	 *
	 * @param string $data text returned from a request to be parsed
	 * @param boolean $json whether or not to parse the body as json
	 */
	public function __construct(string $data, bool $json = false) {
		$this->data = $data;
		$this->json = $json;
		$this->parse();
	}

	/**
	 * Parses the data passed to the constructor into headers,
	 * body, http version, and response code.
	 */
	private function parse() {
		// remove first line
		$lead = strstr($this->data, "\n", true);
		$this->data = substr(strstr($this->data, "\n"), 1);

		// parse first line
		$pieces = explode(" ", $lead);
		$this->httpVersion = (Integer)trim(substr(strstr($pieces[0], "/"), 1));
		$this->code = (Integer)trim($pieces[1]);

		// split into head and body
		$head = strstr($this->data, "\r\n\r\n", true);
		$this->body = substr(strstr($this->data, "\r\n\r\n"), 4);

		// parse headers
		foreach(explode("\n", $head) as $line) {
			$this->headers[strtolower(strstr($line, ":", true))] = trim(substr(strstr($line, ":"), 2));
		}

		// fix content type header, move charset to its own if present
		if(isset($this->headers["content-type"]) && strpos($this->headers["content-type"], ";")) {
			$this->headers["content-type"] = strtok($this->headers["content-type"], ";");
			if(!isset($this->headers["charset"])) $this->headers["charset"] = substr(strstr(strtok(";"), "="), 1);
		}

		// parse body as json if in json mode
		if($this->json && strtolower($this->headers["content-type"]) == "application/json") {
			$json = json_decode($this->body);
			if(json_last_error() == JSON_ERROR_NONE) $this->body = $json;
		}

		// cleanup
		unset($this->data, $head, $lead);
	}

	/**
	 * Returns the body as a string.  If the body was
	 * parsed as json, this will re-encode it.
	 *
	 * @return string the response body
	 */
	public function __toString(): string {
		return (is_string($this->body)) ? $this->body : json_encode($this->body);
	}
}
