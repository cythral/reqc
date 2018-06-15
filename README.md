# reqc [![Build Status](https://travis-ci.org/cythral/reqc.svg?branch=master)](https://travis-ci.org/cythral/reqc) [![Coverage Status](https://coveralls.io/repos/github/cythral/reqc/badge.svg)](https://coveralls.io/github/cythral/reqc) [![License](https://img.shields.io/badge/license-GPL--3.0-blue.svg)](https://github.com/cythral/reqc/blob/master/LICENSE) [![GitHub release](https://img.shields.io/github/release/cythral/reqc.svg)](https://github.com/cythral/reqc/releases/latest)
**Request handling made easy in PHP**

# This project is no longer maintained, use [Devisr HTTP](https://gitlab.com/devisr/http) instead
----

**Request Controller (reqc)** is a php library for handling both  incoming and outgoing HTTP, REST, JSON, EventStream/SSE, and CLI requests.  An additional interface for WebSockets is to be added in a future release.  This library aims to make handling requests and responses as easy as possible.  If you would like a specific client, server or general feature added please feel free to request it in the [issues tab](https://github.com/cythral/reqc/issues) or open a pull request.

At this time, functionality in the HTTP Client is limited to simple requests that do require HTTP authentication or file uploading. Those features and more are to be added in future releases.

## Installation
To install reqc, use composer to install it to your project:

```bash
composer require cythral/reqc
```


## Usage
### HTTP Client
reqc provides an HTTP client, acting as a wrapper for php's curl extension.  In the future, it will also be able to use fsockopen as a fallback when curl is not available.  

```php
$request = new reqc\HTTP\Request($options);
var_dump($request->response);
```

where $options is an array that has the following key => value pairs:

- **url**\* - the url to make the request to
- **method** - the HTTP method to use (GET/POST/etc.)
- **headers** - an array of headers to set
- **json** - bool value, whether or not to parse the request and response bodies as json
- **data** - an array of key value pairs to send as post fields or json data.  If content-type is set to application/json or if the json mode is used, this will automagically be json encoded.
- **handle-ratelimits** - defaults to true.  If true, the request will attempt to retry until a non-429 response code is received.
- **max-attempts** - defaults to 5.  Sets the maximum amount of retry attempts to make if handling rate limits.

\* indicates a required option


### EventStream Server
Reqc provides a EventStream Server for sending Server-Sent Events.  The interface is simple, the only method to note is send, which sends events to the client.  This automatically sets the content-type and cache-control headers.  To create and use an EventStream server, do:

```php
$es = new reqc\EventStream\Server();

$es->send("testEventName", "hello world"); // named event
$es->send(1, "hello world"); // event with an id
$es->send(null, "hello world"); // event without a name or id
$es->send("test", [ "foo" => "bar" ]); // event with json data
```

### Request Constants
Reqc exposes a number of different constants that help to determine how to handle an incoming request. Documentation for each of these is to be added.


## Future
In the future, the ability to do multipart/formdata requests will be added as well as the ability to handle and make file uploads.  Other features TBD.
