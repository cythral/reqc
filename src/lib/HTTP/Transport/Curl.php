<?php

namespace reqc\HTTP\Transport;

/**
 * The Curl class is a connection helper for the Response class.
 */
class Curl {

    /** @var object the curl connection handle **/
    private $handle;

    /**
     * Constructor, fired when a new Curl object is made.
     *
     * @param string $url the url to make a request to
     */
    public function __construct(string $url) {
        $this->handle = curl_init($url);
    }

    /**
     * Sets a curl option
     *
     * @param int $option curl option to set
     * @param mixed $value the value to set for the curl option
     * @return bool true on success or false on failure
     */
    public function setOpt(int $option, $value): bool {
        return curl_setopt($this->handle, $option, $value);
    }

    /**
     * Executes the curl handler
     *
     * @return mixed the result of curl_exec on the handler
     */
    public function exec() {
        return curl_exec($this->handle);
    }

    /**
     * Destructor, closes the curl handler.
     */
    public function __destruct() {
        curl_close($this->handle);
    }
}
