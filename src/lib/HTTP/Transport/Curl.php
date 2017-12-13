<?php

namespace reqc\HTTP\Transport;

/**
 * The Curl class is a connection helper for the Response class.
 */
if(function_exists("curl_init")) {
    class Curl implements Transportable {

        /** @var object the curl connection handle **/
        private $handle;

        /**
         * Constructor, fired when a new Curl object is made.
         *
         * @param string $url the url to make a request to
         */
        public function __construct(string $url) {
            $this->handle = curl_init($url);
            $this->setOpt(CURLOPT_RETURNTRANSFER, true);
            $this->setOpt(CURLOPT_HEADER, true);
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
         * Sets the HTTP verb / method to use
         * 
         * @param string $method the http method to use
         * @return bool true on success or false on failure
         */
        public function setMethod(string $method): bool {
            return $this->setOpt(CURLOPT_CUSTOMREQUEST, $method);
        }

        /**
         * Sets HTTP Request Headers
         * 
         * @param array $headers the http headers to use
         * @return bool true on succes or false on failure
         */
        public function setHeaders(array $headers): bool {
            array_walk($headers, function(&$value, $key) {
                $value = "{$key}: {$value}";
            });

            return $this->setOpt(CURLOPT_HTTPHEADER, $headers);
        }

        /**
         * Sets Data Fields
         * 
         * @param mixed $data the data fields to send
         * @return bool true on succes or false on failure
         */
        public function setBody($body): bool {
            return $this->setOpt(CURLOPT_POSTFIELDS, $body);
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
}