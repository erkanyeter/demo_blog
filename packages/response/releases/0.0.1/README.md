## Response Class

Response class simply sets the http headers to given status. 

**Note:** Output package uses the <kbd>$this->response->setHttpResponse();</kbd> for successful page views and <b>router</b> class uses <kbd>$this->response->show404();</kbd> method for not found pages.

### Error Functions and Headers

------

#### $this->response->show404();

Generates <b>404 Page Not Found</b> errors.

#### $this->response->showError($message, $status_code = 500, $heading = 'An Error Was Encountered');

Manually shows an error to users.

#### $this->response->setHttpResponse(code, 'text');

Permits you to manually set a server status header. Example:

```php
$this->response->setHttpResponse(401);  // Sets the header as:  Unauthorized
```

[See here](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) for a full list of headers.