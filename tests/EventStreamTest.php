<?php

use \PHPUnit\Framework\TestCase;
use \reqc\Server\EventStream;
use \reqc\Client\HTTP\Request;

// MUST USE OB_START OTHERWISE PHPUNIT WILL SKIP THE TEST

class EventStreamTest extends TestCase {

    public function testNamedEvent() {
        $es = new EventStream();
        ob_start();

        $this->expectOutputString("event:progress\ndata:10\n\n");
        $es->sendEvent("progress", 10);
    }

    public function testNamedEventWithJson() {
        $es = new EventStream();
        ob_start();

        $this->expectOutputString("event:progress\ndata:{\"value\":10}\n\n");
        $es->sendEvent("progress", ["value" => 10]);
    }

    public function testIdEvent() {
        $es = new EventStream();
        ob_start();

        $this->expectOutputString("id:1\ndata:test\n\n");
        $es->sendEvent(1, "test");
    }

    public function testDataEvent() {
        $es = new EventStream();
        ob_start();

        $this->expectOutputString("data:test\n\n");
        $es->sendEvent(null, "test");
    }

    public function testOutput() {
        $req = new Request([ "url" => "http://reqc/build/output/eventstream.php" ]);
        $resp = (String)$req->response;
        $events = explode("\n\n", $resp);

        $this->assertEquals("event:progress\ndata:{\"value\":10}", $events[0]);
        $this->assertEquals("event:progress\ndata:{\"value\":20}", $events[1]);
        $this->assertEquals("id:1\ndata:test", $events[2]);
    }
}