<?php

include dirname(dirname(__DIR__))."/vendor/autoload.php";
ob_end_clean();
$out = new reqc\EventStream\Server();

$out->send("progress", ["value" => 10]);
sleep(3);
$out->send("progress", ["value" => 20]);
sleep(1);
$out->send(1, "test");