## App Controller Class

------

The Application allows to you assign your services to Controller instance.

**Note:** This class is initialized automatically by the <b>index.php</b> file so there is no need to do it manually.

### Making your services

------

Open your index.php file define your classes. 

Forexample we want to build a Mailer service and we have Maliler Class in our <kbd>app/classes</kbd> folder.

```php
<?php
/*
|--------------------------------------------------------------------------
| Mailer Service
|--------------------------------------------------------------------------
*/
$c['mailer'] = function () {
    $mailer = new Mailer;
    $mailer->from('No reply <noreply@example.com>');
    return $mailer;
};

/* End of file index.php */
/* Location: .index.php */
```

Then $this->mailer object available in your Controller and you can use it like this.

```php
<?php
/**
 * $c hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['html'];
        $c['view'];
        $c['mailer'];
    }
);
$app->func(
    'index',
    function () {

    	$this->mailer->to('me@me.com');
    	$this->mailer->subject('test');
    	$this->mailer->message('Hello World !');
    	$this->mailer->send();

        $this->view->get(
            'hello_scheme',
            function () {
                $this->set('name', 'Obullo');
                $this->set('title', 'Hello Scheme World !');
                $this->getScheme('welcome');
            }
        );
    }
);


/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
```

### Extending to Services

Below the example sets default sender to "John <john@example.com>";

```php
<?php

$c->extend('mailer', function($mailer) {
    $mailer->from('Web Site Mail Service <admin@example.com>');
    return $mailer;
});

$c['mailer']->to('me@me.com');
$c['mailer']->subject('Test Subject');
$c['mailer']->send();
```

### Creating Mongo NoSQL Service

```php
<?php
/*
|--------------------------------------------------------------------------
| NoSQL Service
|--------------------------------------------------------------------------
*/
$c['mongo'] = function () {
    $mongo = new MongoClient('mongodb://root:123456@localhost:27017/my_database');
    return $mongo->my_database;
};
```
Using mongo Container and Querying results

```php
<?php
/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['html'];
        $c['view'];
        $c['url'];

        $collection = new MongoCollection($c['mongo'], 'users');
        $cursor = $collection->find(array('username' => 'guest_3941574'));

        foreach ($cursor as $doc) {
            var_dump($doc);
        }
 
        /*
        array (size=8)
          'active' => string '1' (length=1)
          'user_id' => string '3941574' (length=7)
          'username' => string 'guest_3941574' (length=13)
        */
    }
);

/* End of file index.php */
/* Location: .index.php */
```