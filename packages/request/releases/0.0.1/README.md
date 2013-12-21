## Request ( Hmvc ) Helper

HMVC library supports <b>internal</b> requests. If you new to use hmvc you can find some useful information in Advanced Topics (/docs/advanced/hmvc) section.

### Calling HMVC Requests

------

Normally first parameter is assigned for request method but if you do not choose a method , request helper will do $_GET request atuomatically. Don't forget Hmvc also stores get and post data into $_REQUEST global variable.

```php
$response = $this->request->get('blog/blog/read');

echo $response; // output value
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
$this->request->post('blog/write',  array('article' => 'content blabla'));  // data must be array
```

GET data example

```php
$this->request->get('blog/write',  array('article' => 'content blabla'));  // data must be array
```


### GET data with Query String

------

You enter query strings and hmvc will parse it simply as getting data.

```php
echo $this->request->get('api/v1?query=SELECT * FROM users LIMIT 100');
```

### Examples

------

```php
<?php
Class Welcome extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }           
    
    public function index()
    {   
        echo $this->request->post('blog/read/18282/');
    }
}

/* End of file welcome.php 
Location: .public/welcome/controller/welcome.php */
```

and <kbd>modules/blog/controller/blog.php</kbd> file should be like this.

```php
<?php
Class Blog extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }           
    
    public function read($id)
    {
        $this->db->where('id', $id);
        $this->db->get('articles');
        $row = $this->db->row();
        
        echo $row->article;  // hmvc request output must be return to string.
    }

}

/* End of file blog.php 
/Location: .public/blog/controller/blog.php */
```