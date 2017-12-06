<?php

use \PHPUnit\Framework\TestCase;
use \reqc\EventStream\Server as EventStreamServer;
use \reqc\HTTP\Request;

// MUST USE OB_START OTHERWISE PHPUNIT WILL SKIP THE TEST

class EventStreamTest extends TestCase {

    public function testNamedEvent() {
        $es = new EventStreamServer();

        $this->expectOutputString("event:progress\ndata:10\n\n");
        $es->send("progress", 10);
    }

    public function testNamedEventWithJson() {
        $es = new EventStreamServer();

        $this->expectOutputString("event:progress\ndata:{\"value\":10}\n\n");
        $es->send("progress", ["value" => 10]);
    }

    public function testIdEvent() {
        $es = new EventStreamServer();

        $this->expectOutputString("id:1\ndata:test\n\n");
        $es->send(1, "test");
    }

    public function testDataEvent() {
        $es = new EventStreamServer();

        $this->expectOutputString("data:test\n\n");
        $es->send(null, "test");
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