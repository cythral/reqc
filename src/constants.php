<?php

namespace reqc;

define("reqc\TYPES", [
	"CLI" => 0,
	"HTTP" => 1,
	"PAGE" => 2,
	"ASSET" => 3
]);

define("reqc\TYPE", php_sapi_name() == "cli" ? TYPES["CLI"] : TYPES["HTTP"]);


// HTTP Constants
if(TYPE == TYPES["HTTP"]) {

	$uri = strtok($_SERVER["REQUEST_URI"], "?");
	$path = (strpos($uri, ".")) ? strstr($uri, ".", true) : $uri;
	$parts = explode("/", $path);
	$directory = implode("/", array_slice($parts, 0, -1));
	$filename = array_reverse($parts)[0];
	$extension = (ltrim(strstr($uri, "."), "."));
	$extension = ($extension == "") ? null : $extension;
	$domainParts = array_reverse(explode(".", $_SERVER["HTTP_HOST"]));

	parse_str(strtok("?"), $_GET);
	parse_str(file_get_contents('php://input'), $_REQUEST);

	define("reqc\PROTOCOL", $_SERVER["SERVER_PROTOCOL"]);
	define("reqc\SSL", (bool)($_SERVER["SSL"] ?? false));
	define("reqc\IP", $_SERVER["REMOTE_ADDR"]);
	define("reqc\USERAGENT", $_SERVER["HTTP_USER_AGENT"] ?? null);
	define("reqc\ACCEPT", explode(",", ($_SERVER["HTTP_ACCEPT"] ?? '')));
	define("reqc\PORT", (int)$_SERVER['SERVER_PORT']);
	define("reqc\VARS", $_REQUEST);
	define("reqc\HOST", strtok($_SERVER["HTTP_HOST"], ":"));
	define("reqc\METHOD", strtoupper($_SERVER['REQUEST_METHOD']));
	define("reqc\BASEURL", $_SERVER["SERVER_NAME"]);
	define("reqc\FULLURL", (SSL ? "https://" : "http://").$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	define("reqc\URI", strtok($_SERVER["REQUEST_URI"], "?"));
	define("reqc\SUBDOMAIN", (count($domainParts) == 2) ? "main" : implode(".", array_slice($domainParts, 2)));
	define("reqc\SUBTYPE", (isset($extension) && !in_array($extension, ["html", "php"])) ? TYPES["ASSET"] : TYPES["PAGE"]);
	define("reqc\DIRECTORY", $directory);
	define("reqc\FILENAME", $filename);
	define("reqc\EXTENSION", $extension);
	define("reqc\FILE", $filename.((isset($extension)) ? ".".$extension : ""));
	define("reqc\PATH", $directory."/".$filename.((isset($extension)) ? ".".$extension : ""));
	define("reqc\H2PUSH", (bool)($_SERVER["H2PUSH"] ?? false));


// CLI Constants
} else {

}


echo IP;