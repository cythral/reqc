<?php

include dirname(__DIR__)."/src/constants.php";
header("Content-Type: application/json");
die(json_encode(["HOST" => reqc\HOST]));