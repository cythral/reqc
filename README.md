# reqc - a requests library
[![Build Status](https://travis-ci.org/cythral/reqc.svg?branch=master)](https://travis-ci.org/cythral/reqc)

**reqc** is a php library for handling incoming and outgoing requests.  It is also a submodule of [Phroses](https://github.com/Cythral/Phroses).  reqc provides constants for various http and cli request variables/parameters, as well as Request and Response objects for making outgoing http requests.

## Installation
To install reqc, use composer to install it to your project:

```bash
composer require cythral/reqc
```

In the future, a packaged phar will also be made available for each release.

## Usage
### Making HTTP Requests
To make a request, do:

```php
use \reqc\Client\HTTP\Request;

$request = new Request($options);
var_dump($request->response);
```

where $options is an array that has the following key => value pairs:

- **url** - the url to make the request to **(REQUIRED)**
- **method** - the HTTP method to use (GET/POST/etc.)
- **headers** - an array of headers to set
- **json** - bool value, whether or not to parse the request and response bodies as json
- **data** - an array of key value pairs to send as post fields or json data.  If content-type is set to application/json or if the json mode is used, this will automagically be json encoded.
- **handle-ratelimits** - defaults to true.  If true, the request will attempt to retry until a non-429 response code is received.
- **max-attempts** - defaults to 5.  Sets the maximum amount of retry attempts to make if handling rate limits.

### Using constants
reqc exposes a number of different constants that help to determine how to handle an incoming request. Documentation for each of these is to be added.


## Future
In the future, the ability to do multipart/formdata requests will be added as well as the ability to handle and make file uploads.  Other features TBD.
