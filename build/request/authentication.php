<?php

header("content-type: text/plain");

if(!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"]) ||
    !($_SERVER["PHP_AUTH_USER"] == "root" && $_SERVER["PHP_AUTH_PW"] == "password")) {
    http_response_code(401);
    header("www-authenticate: Basic realm=\"cythral\"");
    die("invalid");
}

die("success");
