<?php

include dirname(dirname(__DIR__))."/vendor/autoload.php";
$out = new reqc\EventStream\Server();

$out->send("progress", ["value" => 10]);
sleep(3);
$out->send("progress", ["value" => 20]);
sleep(1);
$out->send(1, "test");