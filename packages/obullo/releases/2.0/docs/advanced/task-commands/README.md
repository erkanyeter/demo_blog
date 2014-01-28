## Task Commands<a name="task-commands"></a>

We have useful task commands.

**Note:** These commands requires <kbd>Task Package</kbd>.

#### Log ( Console Debug )

```php
root@localhost:/var/www/framework$ php task log
```

```php
DEBUG - 2013-09-13 06:39:44 --> Application Autoload Initialized 
DEBUG - 2013-09-13 06:39:44 --> Html Helper Initialized 
DEBUG - 2013-09-13 06:39:44 --> Url Helper Initialized 
DEBUG - 2013-09-13 06:39:44 --> Application Autorun Initialized 
DEBUG - 2013-09-13 06:39:44 --> View Class Initialized 
DEBUG - 2013-09-13 06:39:44 --> View file loaded: PUBLIC_DIR/views/footer.php 
DEBUG - 2013-09-13 06:39:44 --> View file loaded: PUBLIC_DIR/views/welcome.php 
DEBUG - 2013-09-13 06:39:44 --> Final output sent to browser 
BENCH - 2013-09-13 06:39:44 --> Loading Time Base Classes: 0.0013 
BENCH - 2013-09-13 06:39:44 --> Execution Time ( Welcome / Welcome / Index ): 0.0021 
BENCH - 2013-09-13 06:39:44 --> Total Execution Time: 0.0034 
BENCH - 2013-09-13 06:39:44 --> Memory Usage: 700,752 bytes 
________LOADED FILES______________________________________________________

Helpers   --> ROOT/packages/html/html.php, ROOT/packages/url/url.php
__________________________________________________________________________

        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Log Manager (c) 2013
Display logs [$php task log], to filter logs [$php task log $level]
```


#### Clear ( Clear Log & Cache files )

When you move your project to another server you need to clear log files and caches. Go to your terminal and type your project path the run the clear.

```php
root@localhost:/var/www/framework$ php task clear 
/* All log files deleted. */
```

#### Export ( Export The Project )

When you upload project files to your live server you need to export it. Export command removes all .svn and .git files and saves the project to export folder.

```php
root@localhost:/var/www/framework$ php task export  
/* Export process named as export_2012-11-12_13-19 and completed ! */
```