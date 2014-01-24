## Web ( Hmvc Request ) Class

Web Request library supports <b>internal</b> requests (HMVC). If you new to use hmvc features you can find some useful information in Advanced Topics (/docs/advanced/hmvc) section.

### Calling HMVC Requests

------

Normally first parameter is assigned for request method but if you do not choose a method , request helper will do $_GET request atuomatically. Don't forget Hmvc also stores get and post data into $_REQUEST global variable.

```php
echo $this->web->get('blog/blog/read');  // output value
```

### Available Query Methods

------

<ul>
    <li>POST</li>
    <li>GET</li>
    <li>UPDATE</li>
    <li>DELETE</li>
    <li>PUT ( When we use PUT method we provide data as string using third parameter instead of array. )</li>
</ul>

### Sending POST and GET Data

------

You can set post or get data by manually.

POST data example

```php
$this->web->post('blog/write',  array('article' => 'content blabla'));  // data must be array
```

GET data example

```php
$this->web->get('blog/write',  array('article' => 'content blabla'));  // data must be array
```


### GET data with Query String

------

You can enter query strings and hmvc will parse it simply as get data.

```php
echo $this->web->get('api/?query=SELECT * FROM users LIMIT 100');
```

### Examples

------

```php
<?php
/**
 * $c hello_web
 *
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new web;
});

$c->func('index', function(){
    echo $this->web->post('hello_web/test/123');
});

$c->func('test', function($arg1, $arg2, $arg3){
    echo '<pre>Response: '.$arg1 .' - '.$arg2. ' - '.$arg3.'</pre>';
});

/* End of file hello_web.php */
/* Location: .public/tutorials/controller/hello_web.php */
```