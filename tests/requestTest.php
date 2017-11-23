<?php

use PHPUnit\Framework\TestCase;

class testRequest extends TestCase {
	
	public function testGetRequest() {

		$req = new reqc\Request([
			"url" => "http://api.phroses.com/reqc-get-test",
			"method" => "GET"
		]);

		$resp = $req->response;

		$this->assertInstanceOf(reqc\Request::class, $req);
		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertEquals('{"hello":true}', (String)$req);
		
		$this->assertInstanceOf(reqc\Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals('{"hello":true}', $resp->body);
		$this->assertEquals("application/json", $resp->headers["content-type"]);
	}

}