<?php

include dirname(__DIR__)."/src/reqc.php";
header("Content-Type: application/json");

$constants = get_defined_constants(true)["user"];
$list = [];
foreach($constants as $key => $val) {
	if(substr($key, 0, 5) == "reqc\\") $list[$key] = $val;
}

die(json_encode($list));