<?php

include dirname(__DIR__)."/constants.php";
header("Content-Type: application/json");
die(json_encode(["HOST" => reqc\HOST]));