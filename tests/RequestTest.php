<?php

use \PHPUnit\Framework\TestCase;
use \reqc\HTTP\Request;
use \reqc\HTTP\Response;

class RequestTest extends TestCase {

	public function testGetRequest() {

		$req = new Request([
			"url" => "http://reqc/build/request/get.php",
			"method" => "GET"
		]);

		$resp = $req->response;

		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals('hello world', (String)$req);
		$this->assertEquals('hello world', $resp->body);
		$this->assertEquals("text/plain", $resp->headers["content-type"]);
	}

	public function testPostRequest() {
		$req = new Request([
			"url" => "http://reqc/build/request/post.php",
			"method" => "POST",
			"data" => [
				"foo" => "bar"
			]
		]);

		$resp = $req->response;

		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals(["foo" => "bar"], json_decode($resp->body, true));
		$this->assertEquals("application/json", $resp->headers["content-type"]);
	}

	public function testJsonRequest() {
		$req = new Request([
			"url" => "http://reqc/build/request/json.php",
			"method" => "POST",
			"json" => true,
			"data" => [
				"foo" => "bar"
			]
		]);

		$resp = $req->response;
		$body = $resp->body;
		$expectedBody = new stdClass;
		$expectedBody->foo = "bar";

		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals($expectedBody, $body);
		$this->assertEquals("application/json", $resp->headers["content-type"]);
	}

	public function testRateLimitedRequest() {
		$req = new Request([ "url" => "http://reqc/build/request/ratelimited.php" ]);
		$this->assertTrue($req->done);
		$this->assertEquals(2, $req->attempts);
		$this->assertEquals("success", (String)$req);
	}
}
