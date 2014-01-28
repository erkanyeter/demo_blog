## Web ( Hmvc Request ) Class

Web Request library supports <b>internal</b> requests (HMVC). If you new to use hmvc features you can find some useful information in Advanced Topics (/docs/advanced/hmvc) section.

### Calling HMVC Requests

------

Normally first parameter is assigned for request method but if you do not choose a method , request helper will do $_GET request atuomatically. Don't forget Hmvc also stores get and post data into $_REQUEST global variable.

```php
echo $this->web->get('blog/getallusers');  // output value
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
$this->web->post('blog/insert',  function(){
	$this->data['article'] = 'content blabla'
});
```

GET data example

```php
echo $this->web->get('blog/search?tag=php');
```

PUT data example

```php
$this->web->post('blog/update',  function(){
    $this->data['id'] = '4';
});
```

DELETE data example

```php
$this->web->post('blog/delete',  function(){
    $this->data['id'] = '4';
});
```


### GET data with Query String

------

You can enter query strings and hmvc will parse it simply as get data.

```php
echo $this->web->get('members/get_all.json?id=4');
```

### Examples

------

```php
<?php

/**
 * $c hello_hmvc
 * 
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    new Url;
    new Html;
    new View;
    new Web;
});

$c->func('index',function() {

    $this->view->get('hello_hmvc', function() {

        $this->set('response_a', $this->web->get('tutorials/hello_dummy/test/1/2/3'));
        $this->set('response_b', $this->web->get('tutorials/hello_dummy/test/4/5/6'));

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));

    });
});

/* End of file hello_hmvc.php */
/* Location: .public/tutorials/controller/hello_hmvc.php */
```

```php
<?php

/**
 * $c hello_dummy 
 * Dummy test class for Hmvc
 * 
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('test', function($arg1, $arg2, $arg3){
	echo '<pre>Response: '.$arg1 .' - '.$arg2. ' - '.$arg3.'</pre>';
});

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */
```

### Send query Data to your web service directory

```php
new Web('web_service');  // default directory is "web_service"

$this->web->query('post','example.method.json',function(){

    $this->data['user_email'] 	 = 'me@me.com';
    $this->data['user_username'] = 'test';
});

var_dump($this->web->getRow());  		// decodes one json row and returns to row "object"
var_dump($this->web->getRowArray());	// decodes one json row and returns to row "array"
var_dump($this->web->getResult());		// decodes all json rows and returns to "object"
var_dump($this->web->getResultArray()); // decodes all json rows and returns to "object"
var_dump($this->web->getCount()); 		// decodes all json rows and returns to counts of "array"
```

### Function Reference of Query Results

------

#### $this->db->getRow();

This function fetches one item and returns query result as object or false on failure.

#### $this->db->getRowArray();

Identical to the above row() function, except it returns an array.

#### $this->db->getResult()

This function returns the query result as object.

#### $this->db->getResultArray();

This function returns the query result as a pure array, or an empty array when no result is produced.

#### $this->db->getCount();

This function returns to counts of array.

#### $this->db->getCount();

In addition, you can walk forward/backwards/first/last through your results using these variations.

#### $this->db->getFirstRow();

#### $this->db->getLastRow();

#### $this->db->getNextRow();

#### $this->db->getPreviousRow();