<?php

use PHPUnit\Framework\TestCase;

class testRequest extends TestCase {
	
	public function testGetRequest() {

		$req = new reqc\request([
			"url" => "http://localhost/build/requestGetTest.php",
			"method" => "GET"
		]);

		$resp = $req->response;

		$this->assertInstanceOf(reqc\request::class, $req);
		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		
		$this->assertInstanceOf(reqc\response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals('hello world', (String)$req);
		$this->assertEquals('hello world', $resp->body);
		$this->assertEquals("text/plain", $resp->headers["content-type"]);
	}
}