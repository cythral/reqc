<?php

namespace reqc\JSON;

use \reqc\Output;
use const \reqc\{ MIME_TYPES };

class Server extends Output {

    public function __construct() {
        $this->setContentType(MIME_TYPES["JSON"]);
    }

    public function send($data, int $code = 200, bool $exit = true) {
        $this->setCode($code);
        echo json_encode($data);
        if($exit) exit;
    }
}