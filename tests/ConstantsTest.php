<?php

use PHPUnit\Framework\TestCase;

class ConstantsTest extends TestCase {
	public function testHttpConstantsIp() {
		$req = new reqc\Request([
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
		$this->assertFalse(isset($constants["reqc\\SUBDOMAIN"]));
		$this->assertFalse(isset($constants["reqc\\DOMAIN"]));
		$this->assertFalse(isset($constants["reqc\\TLD"]));
		$this->assertTrue(isset($constants["reqc\\HOSTISIP"]));
	}

	public function testHttpConstantsLocalhost() {
		$req = new reqc\Request([
			"url" => "http://reqc/build/constantsHttpTest.php",
			"method" => "GET"
		]);

		// make sure we got a valid response
		$this->assertEquals(200, $req->response->code);
		$constants = json_decode($req->response->body, true);

		$this->assertEquals(["*/*"], $constants["reqc\\ACCEPT"]);
		$this->assertEquals(80, $constants["reqc\\PORT"]);
		$this->assertEquals([], $constants["reqc\\VARS"]);
		$this->assertEquals("reqc", $constants["reqc\\HOST"]);
		$this->assertEquals("GET", $constants["reqc\\METHOD"]);
		$this->assertEquals("reqc", $constants["reqc\\BASEURL"]);
		$this->assertEquals("http://reqc/build/constantsHttpTest.php", $constants["reqc\\FULLURL"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\URI"]);
		$this->assertEquals(2, $constants["reqc\\SUBTYPE"]);
		$this->assertEquals("/build", $constants["reqc\\DIRECTORY"]);
		$this->assertEquals("constantsHttpTest", $constants["reqc\\FILENAME"]);
		$this->assertEquals("php", $constants["reqc\\EXTENSION"]);
		$this->assertEquals("constantsHttpTest.php", $constants["reqc\\FILE"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\PATH"]);
		$this->assertEquals(false, $constants["reqc\\H2PUSH"]);
		$this->assertFalse(isset($constants["reqc\\SUBDOMAIN"]));
		$this->assertFalse(isset($constants["reqc\\DOMAIN"]));
		$this->assertEquals("reqc", $constants["reqc\\TLD"]);
		$this->assertFalse(isset($constants["reqc\\HOSTISIP"]));
	}

	public function testHttpConstantsLocalhostSd1() {
		$req = new reqc\Request([
			"url" => "http://sd1.reqc/build/constantsHttpTest.php",
			"method" => "GET"
		]);

		// make sure we got a valid response
		$this->assertEquals(200, $req->response->code);
		$constants = json_decode($req->response->body, true);

		$this->assertEquals(["*/*"], $constants["reqc\\ACCEPT"]);
		$this->assertEquals(80, $constants["reqc\\PORT"]);
		$this->assertEquals([], $constants["reqc\\VARS"]);
		$this->assertEquals("sd1.reqc", $constants["reqc\\HOST"]);
		$this->assertEquals("GET", $constants["reqc\\METHOD"]);
		$this->assertEquals("sd1.reqc", $constants["reqc\\BASEURL"]);
		$this->assertEquals("http://sd1.reqc/build/constantsHttpTest.php", $constants["reqc\\FULLURL"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\URI"]);
		$this->assertEquals(2, $constants["reqc\\SUBTYPE"]);
		$this->assertEquals("/build", $constants["reqc\\DIRECTORY"]);
		$this->assertEquals("constantsHttpTest", $constants["reqc\\FILENAME"]);
		$this->assertEquals("php", $constants["reqc\\EXTENSION"]);
		$this->assertEquals("constantsHttpTest.php", $constants["reqc\\FILE"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\PATH"]);
		$this->assertEquals(false, $constants["reqc\\H2PUSH"]);
		$this->assertFalse(isset($constants["reqc\\SUBDOMAIN"]));
		$this->assertEquals("sd1", $constants["reqc\\DOMAIN"]);
		$this->assertEquals("reqc", $constants["reqc\\TLD"]);
		$this->assertFalse(isset($constants["reqc\\HOSTISIP"]));
	}

	public function testHttpConstantsLocalhostSd2() {
		$req = new reqc\Request([
			"url" => "http://sd2.sd1.reqc/build/constantsHttpTest.php",
			"method" => "GET"
		]);

		// make sure we got a valid response
		$this->assertEquals(200, $req->response->code);
		$constants = json_decode($req->response->body, true);

		$this->assertEquals(["*/*"], $constants["reqc\\ACCEPT"]);
		$this->assertEquals(80, $constants["reqc\\PORT"]);
		$this->assertEquals([], $constants["reqc\\VARS"]);
		$this->assertEquals("sd2.sd1.reqc", $constants["reqc\\HOST"]);
		$this->assertEquals("GET", $constants["reqc\\METHOD"]);
		$this->assertEquals("sd2.sd1.reqc", $constants["reqc\\BASEURL"]);
		$this->assertEquals("http://sd2.sd1.reqc/build/constantsHttpTest.php", $constants["reqc\\FULLURL"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\URI"]);
		$this->assertEquals(2, $constants["reqc\\SUBTYPE"]);
		$this->assertEquals("/build", $constants["reqc\\DIRECTORY"]);
		$this->assertEquals("constantsHttpTest", $constants["reqc\\FILENAME"]);
		$this->assertEquals("php", $constants["reqc\\EXTENSION"]);
		$this->assertEquals("constantsHttpTest.php", $constants["reqc\\FILE"]);
		$this->assertEquals("/build/constantsHttpTest.php", $constants["reqc\\PATH"]);
		$this->assertEquals(false, $constants["reqc\\H2PUSH"]);
		$this->assertEquals("sd2", $constants["reqc\\SUBDOMAIN"]);
		$this->assertEquals("sd1", $constants["reqc\\DOMAIN"]);
		$this->assertEquals("reqc", $constants["reqc\\TLD"]);
		$this->assertFalse(isset($constants["reqc\\HOSTISIP"]));
	}

	public function testCliConstants() {
		$this->assertEquals([], reqc\VARS);
		$this->assertEquals(["build/constantsCliTest.php" => true, "--test" => "true"], json_decode(shell_exec("php build/constantsCliTest.php --test=true"), true));
	}
}
