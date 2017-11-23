<?php

use PHPUnit\Framework\TestCase;

class constantsTest extends TestCase {
	public function testConstantsRequest() {
		$req = new reqc\request([
			"url" => "http://127.0.0.1/build/constantsHttpTest.php",
			"method" => "GET"
		]);

		echo $req;
		$this->assertEquals(200, $req->response->code);
	}
}