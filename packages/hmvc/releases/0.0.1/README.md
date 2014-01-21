
## Hmvc Class

Using hmvc package, you can execute hmvc requests between your mods (modules). The HMVC technology offers more flexibility, for example instead of internal Curl requests you can use hmvc, this will improve the performance of your application. You can find more information about hmvc in the docs Advanced Topics / HMVC section.

<b>Note:</b> Using request helper you can quickly access to hmvc class methods.

### Initializing the Class

------

```php
new Hmvc;
$this->hmvc->method();
```

Here is a very simple example showing you how to call a hmvc request using Hmvc class.

```php
new Hmvc();
$this->hmvc->clear();                       
$this->hmvc->setRequest('module/controller/method');
$this->hmvc->setMethod('GET', $params = array());

echo $this->hmvc->exec();
```

### Function Reference

------

#### $this->hmvc->setMethod($method = 'get', $params = 'mixed');

Sets the hmvc request method.

*Available Query Methods*

* POST
* GET
* UPDATE
* DELETE
* PUT ( When we use PUT method we provide data as string using third parameter instead of array. )

#### $this->hmvc->setCache($time = 0 int);

You can do cache for your static hmvc requests. When a hmvc request called the first time, the cache file will be written to your <kbd>app/cache</kbd> folder. You can learn more details about output caching in <kbd>docs/advanced/caching</kbd> section.

#### $this->hmvc->setServer($key = '', $val = '');

Sets the $_SERVER headers for current hmvc scope.

#### $this->hmvc->noLoop($enable = true boolean);

Some times advanced users use the HMVC requests when extending a custom Controller, in this case normally a HMVC library do an unlimited loop and this cause server crashes, beware if you use hmvc requests in customized controllers this will make you have an unlimited loop. The noLoop(); method will prevent the any possible loops.

#### echo $this->hmvc->exec();

Executes hmvc call and returns to response.