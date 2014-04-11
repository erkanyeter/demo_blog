## Container Class

------

The Container ( Pimple ) assist you to you assign your services to <kbd>$c</kbd> variable. Then we use the <kbd>$c</kbd> variable every where in the application.

**Note:** This class is initialized automatically by the <b>index.php</b> file so there is no need to do it manually.

<kbd>$c</kbd> is the container variable we declare it in the top of the index.php file.

```php
/*
|--------------------------------------------------------------------------
| Container ( IOC )
|--------------------------------------------------------------------------
*/
$c = new Obullo\Container\Pimple;
```

## Services

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
    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
```

### Extending to Services

Below the example override default sender to "John <john@example.com>";

```php
<?php

/**
 * $c hello_world
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;

        $c->extend(
            'mailer',
            function($mailer) {
                $mailer->from('Web Site Mail Service <admin@example.com>');
                return $mailer;
            }
        );
    }
);
$app->func(
    'index',
    function () {

        $this->mailer->to('me@me.com');
        $this->mailer->subject('test');
        $this->mailer->message('Hello World !');
        $this->mailer->send();
    }
);
```

## Providers

### Creating NoSQL Provider

```php
<?php
/*
|--------------------------------------------------------------------------
| NoSQL Provider
|--------------------------------------------------------------------------
*/
$c['mongo'] = function ($params) use ($c) {
    $mongoClient = new MongoClient('mongodb://root:12345@localhost:27017/'.$params['db.name']);
    return new MongoCollection($mongoClient->{$params['db.name']}, $params['db.collection']);
};
```
### Querying results using NoSQL Provider

```php
<?php
/**
 * $app hello_world
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['html'];
        $c['view'];
        $c['url'];

        $c->bind('mongo', array('db.name' => 'test', 'db.collection' => 'users'));

        $cursor = $this->mongo->find();

        foreach ($cursor as $docs) {
            echo $docs['user_email'].'<br />';
        }
 
        // gives
        /*
        me@me.com
        test@test.com
        */
    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
```

### Function Reference

------

#### $c->extend(string $class, closure $callable);

Extends your class and override methods or variables using current instance of the object.

#### $c->bind(string $class, array $params);

Send parameters to closure and create new instance of the object.

#### $c->raw(string $class);

Returns closure data of the class.

#### $c->keys();

Returns to all stored keys ( class names ) in the container.