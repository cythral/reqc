<?php

use PHPUnit\Framework\TestCase;

class constantsTest extends TestCase {
	public function testConstants() {
		$req = new reqc\request([
			"url" => "http://127.0.0.1/build/constantsHttpTest.php",
			"method" => "GET"
		]);

		// make sure we got a valid response
		$this->assertEquals(200, $req->response->code);
		$constants = json_decode($req->response->body);

		var_dump($constants);
	}
}