## User Classes

The <kbd>modules/classes</kbd> folder is reserved for user libraries. Below the examples demonstrate creating class and helper files.

### Creating Your Classes

------

```php
Class Test
{
    function __construct($no_instance = true)
    {
        if($no_instance)
        {
            getInstance()->test = $this; // Available it in the contoller $this->test->method();
        }
        
        logMe('debug', "Acl Class Initialized");
    }

    function me()
    {
        echo 'Hello !';
    }
}

new test();
$this->test->me();   // output Hello !
```

### Including Sources

Simply include sources into your classes.

```php
require CLASSES .'test/src/otherclass.php';

Class Test
{
    function __construct($no_instance = true)
    {
        if($no_instance)
        {
            getInstance()->test = $this; // Available it in the contoller $this->test->method();
        }
        
        logMe('debug', "Test Class Initialized");
    }

    function me()
    {
        echo 'Hello !';
    }
}
```

### Creating Your Helpers

```php
namespace Test {

    Class start {

        function __construct()
        {
            \logMe('Test Helper Initialized !');
        }       
    }
    
    function me()
    {
       echo 'Hello me !';
    }
}

new test\start();  // Calling your test functions. ( helpers )
```