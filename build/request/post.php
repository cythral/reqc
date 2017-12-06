<?php
include dirname(dirname(__DIR__))."/src/reqc.php";
header("content-type: application/json");
die(json_encode(reqc\VARS));