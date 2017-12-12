<?php

include dirname(dirname(__DIR__))."/vendor/autoload.php";

$req = new reqc\HTTP\Request(["url" => "http://reqc/build/request/get.php"]);

var_dump($req->response);