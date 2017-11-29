<?php

namespace reqc;

class Curl {
    private $handle;

    public function __construct($url) {
        $this->handle = curl_init($url);
    }

    public function setOpt($option, $value) {
        curl_setopt($this->handle, $option, $value);
    }

    public function exec() {
        return curl_exec($this->handle);
    }

    public function __destruct() {
        curl_close($this->handle);
    }
}
