
##Tasks & Cli Requests <a name="tasks-and-cli-requests"></a> 

The task package helps to you use CLI operations ( running shell scripts, unix commands ).

#### Running Tasks

The first segment calls the <kbd>module</kbd> and others call <kbd>controller/method/arguments</kbd>

```php
Task::run('welcome hello/index/arg1/arg2');
```

Using Task::run() function you can run your tasks.


### CLI (Command Line Interface)

------

First of all go your framework root folder.

```php
$cd /var/www/framework/
```

All command line requests go to framework <b>task</b> file which is located in your root.


```php
$php task start
```

Above the command calls the <samp>start</samp> controller from <b>tasks</b> folder which is located in your <kbd>modules/tasks</kbd>.

```php
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2013
Please run [$php task start help] You are in [ modules / tasks ] folder.
```

If you see this screen your command successfully run <b>otherwise</b> check your <b>php path</b> running by this command

```php
$which php // command output /usr/bin/php 
```

If your current php path is not <b>/usr/bin/php</b> open the <b>constants</b> file and define your php path. 

```php
define('PHP_PATH', 'your_php_path_that_you_learned_by_which_command'); 
```