<?php

include dirname(dirname(__DIR__))."/vendor/autoload.php";

$out = new reqc\Server\EventStream();
$out->sendEvent("progress", ["value" => 10]);
sleep(1);
$out->sendEvent("progress", ["value" => 20]);
sleep(1);
$out->sendEvent(1, "test");