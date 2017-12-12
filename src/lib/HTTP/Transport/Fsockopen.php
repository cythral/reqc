<?php

namespace reqc\HTTP\Transport;

class Fsockopen implements Transportable {
    private $handle;
    private $urlparts;
    private $headers = [];
    private $body;
    private $method = 'GET';
    private $httpVersion = '1.1';

    const TERMINATOR = "\r\n";

    public function __construct($url) {
        $this->urlparts = parse_url($url);
    }

    public function setHeaders(array $headers): bool {
        $this->headers = $headers;
        return true;
    }

    public function setMethod(string $method): bool {
        $this->method = $method;
        return true;
    }

    public function setBody($body): bool {
        $this->body = $body;
        return true;
    }

    public function exec() {
        $this->handle = fsockopen($this->urlparts["host"], $this->urlparts["port"] ?? 80);

        // send first lines
        $this->sendLine("{$this->method} {$this->urlparts['path']} HTTP/{$this->httpVersion}");
        $this->sendLine("host: {$this->urlparts['host']}");
        $this->sendLine("connection: close");

        // adjust body if array
        if(isset($this->body)) {
            if(is_array($this->body) && isset($this->headers["content-type"])) {
                if(strtolower($this->headers["content-type"]) == "application/json") {
                    $this->body = json_encode($this->body);
                } else if(strtolower($this->headers["content-type"]) == "application/x-www-form-urlencoded") {
                    $this->body = http_build_query($this->body);
                }
            }

            $this->headers["content-length"] = strlen($this->body);
        }

        // send headers
        foreach($this->headers as $key => $val) {
            $this->sendLine("{$key}: {$val}");
        }

        $this->sendLine();

        // send body
        if(isset($this->body)) {
            $this->sendLine($this->body);
        }

        $output = "";
        while(!feof($this->handle)) {
            $output .= fgets($this->handle, 4096);
        }

        fclose($this->handle);

        return $output;
    }

    private function sendLine(string $line = "") {
        fputs($this->handle, $line.self::TERMINATOR);
    }
}