### Debug Settings

------

Framework builds user friendly debugging into your applications using the configurations described below. Open your <kbd>app/config/config.php</kbd> and look at the <var>$config['debug_backtrace']</var>

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 5);
```
You can enable or disable debugging also you can disable it using native php error constants.

```php
$config['debug_backtrace']  = array('enabled' => false, 'padding' => 5)
```

If you change the padding option debugging lines will be smaller.

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 3); (
```

```php
Notice): Undefined variable: undefined 
PUBLIC/welcome/controllers/welcome.php Code : 8 ( Line : 14 )

12     {   
13 
14         echo $undefined;
15         
16         $data['var'] = 'and generated by ';
17 
```

### Console Debug

Framework allows to you see all debug messages from your console which are listed below.

* SQL QUERIES
* LOG DATA
* BENCHMARK DATA
* MEMORY USAGE
* PHP ERRORS
* HMVC REQUESTS
* TASK REQUESTS
* LOADED FILES

In your config file <b>$config['log_threshold']</b> value > 0 framework simply will keep all <b>errors</b>, <b>logs</b>, <b>bechmarks</b> into your log files.

Set your log writing level to <b>"5"</b> to see all logs.

```php
$config['log_threshold'] = (ENV == 'LIVE') ? 1 : 5;
```

#### Run the Debugging

Console debug requires <kbd>task</kbd> package, first you need to install it then you can follow the all log messages using below the command.

```php
php task log
```
You can set the log level filter using the level argument.

```php
php task log level info
```

This command display only log messages which are flagged as debug.

```php
php task log level debug
```

#### Explanation of Log Settings:

* log_threshold - The threshold determines what gets logged.
* log_queries - If this option set to true all Database SQL Queries gets logged.
* log_benchmark - If this option set to true all framework benchmarks gets logged.
* log_date_format - Logging date format.

#### Explanation of Message Types:

* (0) Logging Disabled - Error logging <b>TURNED OFF</b>.
* (1) Error Messages - These are actual errors, such as <b>PHP errors</b> or user errors.
* (2) Debug Messages - These are messages that <b>assist in debugging</b>. For example, if a class has been initialized, you could log this as debugging info.
* (3) Informational Messages - These are the <b>lowest priority messages</b>, simply giving information regarding some process. Framework doesn't natively generate any info messages but you may want to in your application.
* (4) Benchmark Info - These are the <b>benchmark</b> messages, simply giving information about loading time classes, memory consumption and others.
* (5) All Messages  - <b>All</b> type of log messages.

For more details about logging look at <kbd>Log Package</kbd>.