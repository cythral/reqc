<?php

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
	
	public function testGetRequest() {

		$req = new reqc\Request([
			"url" => "http://localhost/build/requestGetTest.php",
			"method" => "GET"
		]);

		$resp = $req->response;

		$this->assertInstanceOf(reqc\Request::class, $req);
		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(reqc\Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals('hello world', (String)$req);
		$this->assertEquals('hello world', $resp->body);
		$this->assertEquals("text/plain", $resp->headers["content-type"]);
	}

	public function testPostRequest() {
		$req = new reqc\Request([
			"url" => "http://localhost/build/requestPostTest.php",
			"method" => "POST",
			"data" => [
				"foo" => "bar"
			]
		]);

		$resp = $req->response;

		$this->assertInstanceOf(reqc\Request::class, $req);
		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(reqc\Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals(["foo" => "bar"], json_decode($resp->body, true));
		$this->assertEquals("application/json", $resp->headers["content-type"]);
	}
}