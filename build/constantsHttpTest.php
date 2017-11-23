<?php

include dirname(__DIR__)."/constants.php";
header("application/json");
die(json_encode(["HOST" => reqc\HOST]));