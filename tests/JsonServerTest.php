<?php

use \PHPUnit\Framework\TestCase;
use \reqc\JSON\Server as JsonServer;
use \reqc\HTTP\Request;

class JsonServerTest extends TestCase {
    
    public function testSuccess() {
        $this->expectOutputString('{"test":true}');
        
        $out = new JsonServer;
        $out->send([ "test" => true ], 200, false);

        $this->assertEquals(200, http_response_code());
    }

    public function testError() {
        $this->expectOutputString('{"test":true}');

        $out = new JsonServer;
        $out->send([ "test" => true ], 400, false);

        $this->assertEquals(400, http_response_code());
    }


}