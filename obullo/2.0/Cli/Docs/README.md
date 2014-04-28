## Task Class

The task class helps you use CLI operations ( running shell scripts etc..) using the php command line interface.

### Initializing the Class

------

```php
$c['cli.task'];
$this->task->method();
```

#### Running Tasks

The task uri works like framework uri it calls the <kbd>controller/method/arguments</kbd>

```php
echo $this->task->run('help/index', true);
```

### Function Reference

------

#### $this->task->run('class/method/arg1/arg2 ...', $debug = false);

Using $this->task->run() function run your tasks as a using php shell_exec(); command in the background.


## Useful Cli Commands

The task package helps to you use CLI operations ( running shell scripts, unix commands ).

Available Commands

#### Help

See the list of available commands.

```php
root@localhost:/var/www/project$ php task

        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2014
Please run [$php task help] You are in [ app / tasks ] folder.

Usage:
php task [command] [arguments]

Available commands:
log        : Follows the application log file.
clear      : Clear application log data. It is currently located in data folder.
update     : Update your Obullo version.
help       : See list all of available commands.

```

#### Follow Log Data ( Console Debug )

```php
root@localhost:/var/www/project$ php task log
```

```php
Following log data ...

DEBUG - 2013-09-13 06:39:44 --> Application Controller Class Initialized 
DEBUG - 2013-09-13 06:39:44 --> Html Helper Initialized 
DEBUG - 2013-09-13 06:39:44 --> Url Helper Initialized 
DEBUG - 2013-09-13 06:39:44 --> Application Autorun Initialized 
DEBUG - 2013-09-13 06:39:44 --> View Class Initialized 
DEBUG - 2013-09-13 06:39:44 --> Final output sent to browser 
BENCH - 2013-09-13 06:39:44 --> Memory Usage: 700,752 bytes 
```

#### Clear

When you move your project to another server you need to clear log data. Go to your terminal and type your project path then run the clear.

```php
root@localhost:/var/www/project$ php task clear 
```

#### Update

This command upgrade your Obullo if new version available.

```php
root@localhost:/var/www/project$ php task update
```

### Troubleshooting

------

Go to your framework root folder.

```php
$cd /var/www/project/
```

Command line request goes to framework <b>task</b> file which is located in your root.


```php
$php task help
```

Above the command calls the <kbd>app/task/help.php</kbd> class from <b>tasks</b> folder.

```php
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2014
Please run [$php task help] You are in [ app / tasks ] folder.

Usage:
php task [command] [arguments]

Available commands:
log        : Follows the application log file.
clear      : Clear application log data. It is currently located in data folder.
update     : Update your Obullo version.
help       : See list all of available commands.
```

If you see this screen your command successfully run <b>otherwise</b> check your <b>php path</b> running by this command

```php
$which php // command output /usr/bin/php 
```

If your current php path is not <b>/usr/bin/php</b> open the <b>constants</b> file and define your php path. 

```php
define('PHP_PATH', 'your_php_path_that_you_learned_by_which_command'); 
```

### Running Native Cli Tasks

In some cases you may need to use php native exec() commands. Also you can use it like below the example.

```php
echo shell_exec(TASK .'welcome/start.php');  //  gives Hello World !
```

#### Continious Tasks

Using below the command your task will be done without wait the server response.

```php
shell_exec(TASK .'welcome/start.php > /dev/null &');
```