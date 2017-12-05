<?php

namespace reqc\Server;

use const \reqc\{TYPE, TYPES, EXTENSION, MIME_TYPES};

class Output {

    public function __construct(bool $autoContentType = true) {
        if($autoContentType) $this->setContentType(MIME_TYPES[strtoupper(EXTENSION)] ?? "text/html");
    }

    public function setHeader(string $headerName, string $headerValue, bool $replace = true) {
        if(TYPE != TYPES["CLI"]) header("{$headerName}: {$headerValue}", $replace);
    }

    public function setContentType(string $contentType) {
        $this->setHeader("content-type", $contentType);
    }
}