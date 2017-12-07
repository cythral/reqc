<?php

header("content-type: text/plain");

if(!file_exists("./hit")) {
    touch("./hit");
    http_response_code(429);
    header("retry-after: 5");
} else {
    echo "success";
    unlink("./hit");
}