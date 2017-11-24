<?php

use PHPUnit\Framework\TestCase;

class constantsTest extends TestCase {
	public function testConstantsIp() {
		$req = new reqc\request([
			"url" => "http://127.0.0.1/build/constantsHttpTest.php",
			"method" => "GET"
		]);

		// make sure we got a valid response
		$this->assertEquals(200, $req->response->code);
		$constants = json_decode($req->response->body, true);

		$this->assertEquals(["*/*"], $constants["reqc\\ACCEPT"]);
		$this->assertEquals(80, $constants["reqc\\PORT"]);
		$this->assertEquals([], $constants["reqc\\VARS"]);
		$this->assertEquals("127.0.0.1", $constants["reqc\\HOST"]);
		$this->assertEquals("GET", $constants["reqc\\METHOD"]);
		$this->assertEquals("127.0.0.1", $constants["reqc\\BASEURL"]);
		$this->assertEquals("http://127.0.0.1/build/constantsHttpTest.php", $constants["reqc\\FULLURL"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\URI"]);
		$this->assertEquals(2, $constants["reqc\\SUBTYPE"]);
		$this->assertEquals("/build", $constants["reqc\\DIRECTORY"]);
		$this->assertEquals("constantsHttpTest", $constants["reqc\\FILENAME"]);
		$this->assertEquals("php", $constants["reqc\\EXTENSION"]);
		$this->assertEquals("constantsHttpTest.php", $constants["reqc\\FILE"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\PATH"]);
		$this->assertEquals(false, $constants["reqc\\H2PUSH"]);
	}
}