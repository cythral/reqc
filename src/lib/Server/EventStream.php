<?php

namespace reqc\Server;

use const \reqc\{MIME_TYPES};

final class EventStream extends Output {
    public function __construct() {
        $this->setContentType(MIME_TYPES["EVENT_STREAM"]);
        $this->setHeader("cache-control", "no-cache");
    }

    public function sendEvent($key = null, $value) {
        if(is_string($key)) $this->sendNamedEvent($key, $value);
        else if(is_integer($key)) $this->sendIdEvent($key, $value);
        else if(!$key) $this->flush("data:$value\n\n");
    }

    private function flush(string $output) {
        echo $output;
        ob_end_flush();
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