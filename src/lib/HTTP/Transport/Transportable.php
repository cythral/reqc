<?php

namespace reqc\HTTP\Transport;

interface Transportable {
    public function setMethod(string $method): bool;
    public function setHeaders(array $headers): bool;
    public function setBody($body): bool;
}