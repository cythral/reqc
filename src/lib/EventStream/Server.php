<?php

namespace reqc\EventStream;

use \reqc\Output;
use const \reqc\{MIME_TYPES};

class Server extends Output {
    public function __construct() {
        $this->setContentType(MIME_TYPES["EVENT_STREAM"]);
        $this->setHeader("cache-control", "no-cache");
    }

    public function send($key = null, $value) {
        if(is_string($key)) $this->sendNamedEvent($key, $value);
        else if(is_integer($key)) $this->sendIdEvent($key, $value);
        else if(!$key) $this->flush("data:$value\n\n");
    }

    private function flush(string $output) {
        echo $output;
        flush();
    }

    private function sendNamedEvent(string $name, $value) {
        if(is_array($value)) $value = json_encode($value);
        $this->flush("event:{$name}\ndata:{$value}\n\n");
    }

    private function sendIdEvent(int $id, $value) {
        $this->flush("id:{$id}\ndata:{$value}\n\n");
    }
}