<?php

$p = new Phar(__DIR__.'/reqc.phar');
$p->startBuffering();
$p->buildFromDirectory(__DIR__."/src/");
$p->setStub($p->createDefaultStub("bootstrap.php"));
$p->stopBuffering();

