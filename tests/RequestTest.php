<?php

use \PHPUnit\Framework\TestCase;
use \reqc\HTTP\Request;
use \reqc\HTTP\Response;

class RequestTest extends TestCase {

	/**
	 * @dataProvider providerGetRequest
	 */
	public function testGetRequest($req) {
		$resp = $req->response;

		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals('hello world', (String)$req);
		$this->assertEquals('hello world', $resp->body);
		$this->assertEquals("text/plain", $resp->headers["content-type"]);
	}

	/**
	 * @dataProvider providerPostRequest
	 */
	public function testPostRequest($req) {
		$resp = $req->response;

		$this->assertTrue($req->done);
		$this->assertEquals(1, $req->attempts);
		$this->assertInstanceOf(Response::class, $resp);
		$this->assertEquals(200, $resp->code);
		$this->assertEquals(["foo" => "bar"], json_decode($resp->body, true));
		$this->assertEquals("application/json", $resp->headers["content-type"]);
	}

	/**
	 * @dataProvider providerJsonRequest
	 */
	public function testJsonRequest($req) {
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

	/**
	 * @dataProvider providerRateLimitedRequest
	 */
	public function testRateLimitedRequest($req) {
		$this->assertTrue($req->done);
		$this->assertEquals(2, $req->attempts);
		$this->assertEquals("success", (String)$req);
	}
	
	
	public function providerGetRequest() {
		$options = ["url" => "http://reqc/build/request/get.php"];
		return [
			[ new Request($options) ],
			[ new Request($options + ["use-fsockopen" => true]) ]
		];
	}

	public function providerPostRequest() {
		$options = [
			"url" => "http://reqc/build/request/post.php",
			"method" => "POST",
			"data" => [ "foo" => "bar" ]
		];

		return [
			[ new Request($options) ],
			[ new Request($options + ["use-fsockopen" => true]) ]
		];
	}

	public function providerJsonRequest() {
		$options = [
			"url" => "http://reqc/build/request/json.php",
			"method" => "POST",
			"json" => true,
			"data" => [ "foo" => "bar" ]
		];

		return [
			[ new Request($options) ],
			[ new Request($options + ["use-fsockopen" => true]) ]
		];
	}

	public function providerRateLimitedRequest() {
		$options = [ "url" => "http://reqc/build/request/ratelimited.php" ];
		return [
			[ new Request($options) ],
			[ new Request($options + ["use-fsockopen" => true]) ]
		];
	}
}
