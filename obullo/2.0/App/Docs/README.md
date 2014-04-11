## App Controller Class

------

Application Controller has php <b>__get</b> and <b>__set</b> magic methods that allows to you assign your services to instance of the Controller. 

**Note:** This class is initialized automatically by the <b>index.php</b> file so there is no need to do it manually.

### Making your services

------

Open your index.php file define your classes. 

Forexample we want to build a Mailer service and we have Mailer Class in our <kbd>app/classes</kbd> folder.

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
    return $c['app']->mailer = $mailer;
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
    }
);


/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
```

**Note : ** Please look at container package for more details.