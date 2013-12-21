
## Hmvc Class

Using hmvc package, you can execute hmvc requests between your mods (modules). The HMVC technology offers more flexibility, for example instead of internal Curl requests you can use hmvc, this will improve the performance of your application. You can find more information about hmvc in the docs Advanced Topics / HMVC section.

<b>Note:</b> Using request helper you can quickly access to hmvc class methods.

### Initializing the Class

------

```php
 $request = Request::get('module/controller/method');
```

### Ouick Access

------

Normally first parameter is assigned to request method but if you do not choose a method , the request helper will atuomatically choose $_GET request. Don't forget Obullo also stores get and post data into $_REQUEST global variable.

```php
echo Request::get('blog/blog/read'); 
```

Post Method

```php
echo Request::post('module/controller/method');
```

### Long Access

------

Here is a very simple example showing you how to call a hmvc request using Hmvc class.

```php
$hmvc = new Hmvc();
$hmvc->clear();                       
$hmvc->request('module/controller/method');
$hmvc->setMethod('GET', $params = array());

echo $hmvc->exec();
```

### Function Reference

------

#### $hmvc->setMethod($method = 'get', $params = 'mixed');

Sets the hmvc request method.

*Available Query Methods*

* POST
* GET
* UPDATE
* DELETE
* PUT ( When we use PUT method we provide data as string using third parameter instead of array. )

#### $hmvc->setCache($time = 0 int);

You can do cache for your static hmvc requests. When a hmvc request called the first time, the cache file will be written to your <kbd>app/cache</kbd> folder. You can learn more details about output caching in <kbd>docs/advanced/caching</kbd> section.

#### $hmvc->setServer($key = '', $val = '');

Sets the $_SERVER headers for current hmvc scope.

#### $hmvc->noLoop($enable = true boolean);

Some times advanced users use the HMVC requests when extending a custom Controller, in this case normally a HMVC library do an unlimited loop and this cause server crashes, beware if you use hmvc requests in customized controllers this will make you have an unlimited loop. The noLoop(); method will prevent the any possible loops.

#### echo $hmvc->exec();

Executes hmvc call and returns to response.