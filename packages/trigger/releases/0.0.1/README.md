## Trigger Class

The Trigger Class trigger your autoload functions that are defined in your <kbd>app/config/triggers.php</kbd> file.
Using trigger object you can intiate your class methods in <b>__construct()</b> level.

### Running Triggers

------

```php
new Trigger('public');
```

Above the public trigger run predefined <b>"public"</b> operation in Controller <b>__construct()</b> level.

### Trigger Config File

In order to keep the framework as light-weight as possible only the absolute minimal resources are run by default. This file lets you globally define which systems you would like run with every request of "Trigger Object".

```php
-  app
    + config
        + debug
            agents.php
            hooks.php
            triggers.php
```

```php
new Trigger('private', 'navbar');
```

Above the private trigger run predefined "private" operation in Controller __construct() level.

An example <b>trigger</b> config file shown ad below.

```php
<?php
$triggers['func'] = array(
    
    'private' => function(){  
        // All of your authorized users play games in here !

        if( ! $this->auth->hasIdentity()) {  // if user has not identity ?
            new Url;
            $this->url->redirect('/login');  // redirect user to login page
        }

    },
    'public' => function(){          
        // All of your public users play games in here !
    },
    'navbar' => function(){  // this load navigation menu settings

        $this->config->load('navbar');    
    },  
);
```

**Note:** We don't recommend declaring all classes in trigger functions this will restrict your application development.

An example trigger usage in controller.

```php
<?php
/**
 * $c home
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Url;
    new Html;
    new Db;
    new Date_Format;
    new Tag_Cloud;
    new View;
    new Sess;
    new Auth;

    new Trigger('public','navbar'); // run triggers

});

$c->func('index', function(){

    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');

    $posts = $this->db->getResultArray();

    $this->view->get('home', function() use($posts) {

        $this->set('title', 'Welcome to home');
        $this->set('posts', $posts);
        $this->getScheme();
    });

});

/* End of file home.php */
/* Location: .public/home/controller/home.php */
```