## Logger Class

The Logger class assists you to <kbd>write messages</kbd> to your log handlers. The logger class use php SplPriorityQueue class to manage your handler proirities.

**Note:** This class is initialized automatically by the <b>index.php</b> file so there is no need to do it manually.

**Note:** The <b>logger</b> package uses <kbd>Disabled</kbd> handler as default.

### Available Log Hanlers

* Disabled
* File
* Mongo

### Enable / Disable Logger

As default framework comes with logging disabled <kbd>false</kbd>. You can enable logger setting <kbd>enabled to true.</kbd>

On your local environment config file  set <kbd>threshold</kbd> level <b>1</b> to <b>7</b>.

```php
/*
|--------------------------------------------------------------------------
| Logger
|--------------------------------------------------------------------------
| Severities:
| LOG_EMERG (0)    : Emergency: system is unusable.
| LOG_ALERT (1)    : Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
| LOG_CRIT (2)     : Critical conditions. Example: Application component unavailable, unexpected exception.
| LOG_ERR (3)      : Runtime errors that do not require immediate action but should typically be logged and monitored.
| LOG_WARNING (4)  : Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
| LOG_NOTICE (5)   : Normal but significant events.
| LOG_INFO (6)     : Interesting events. Examples: User logs in, SQL logs, Application Benchmarks.
| LOG_DEBUG (7)    : Detailed debug information.
| ---------------------------------------------------
| @see Syslog Protocol http://tools.ietf.org/html/rfc5424
| @link http://www.php.net/manual/en/function.syslog.php
| ---------------------------------------------------
*/
'logger' =>   array(
        'enabled'   => true,        // On / Off logging.
        'debug'     => false,       // On / Off debug html output. When it is enabled all handlers will be disabled.
        'threshold' => array(       // Set allowed log levels. ( @see http://www.php.net/manual/en/function.syslog.php )
            LOG_EMERG,
            LOG_ALERT,
            LOG_CRIT,
            LOG_ERR,
            LOG_WARNING,
            LOG_NOTICE,
            LOG_INFO,
            LOG_DEBUG
        ),
        'queries'   => true,        // If true "all" SQL Queries gets logged.
        'benchmark' => true,        // If true "all" Application Benchmarks gets logged.
        'channel'   => 'system',    // Default channel name should be general.
        'line'      => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
        'path'      => array(
            'app'   => 'data/logs/app.log',       // file handler application log path
            'cli'   => 'data/logs/cli/app.log',   // file handler cli log path  
        ),
),
```
#### Explanation of Settings:

* enabled - On / Off logging
* debug - On / Off html output, logger gives html output bottom of the current page.
* threshold - The threshold determines what gets logged.
* queries - If this option set to true all Database SQL Queries gets logged.
* benchmark - If this option set to true all framework benchmarks gets logged.
* channel - Default channel name should be general.
* line - Logging line format for line based handlers.
* path - File handler paths
* writers - Available log handlers priority settings for log queue.

#### Explanation of Severities:

<table class="span9">
<thead>
<tr>
<th>Severity</th>
<th>Level</th>
<th>Constant</th>
<th>Desciription</th>
</tr>
</thead>
<tbody>
<tr>
<td>emergency</td>
<td>0</td>
<td>LOG_EMERG</td>
<td>Emergency: System is unusable.</td>
</tr>

<tr>
<td>alert</td>
<td>1</td>
<td>LOG_ALERT</td>
<td>Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.</td>
</tr>

<tr>
<td>critical</td>
<td>2</td>
<td>LOG_CRIT</td>
<td>Critical conditions. Example: Application component unavailable, unexpected exception.</td>
</tr>

<tr>
<td>error</td>
<td>3</td>
<td>LOG_ERR</td>
<td>Runtime errors that do not require immediate action but should typically be logged and monitored.</td>
</tr>

<tr>
<td>warning</td>
<td>4</td>
<td>LOG_WARNING</td>
<td>Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.</td>
</tr>

<tr>
<td>notice</td>
<td>4</td>
<td>LOG_NOTICE</td>
<td>Normal but significant events.</td>
</tr>

<tr>
<td>info</td>
<td>6</td>
<td>LOG_INFO</td>
<td>Interesting events. Examples: User logs in, SQL logs, Application Benchmarks.</td>
</tr>

<tr>
<td>debug</td>
<td>7</td>
<td>LOG_DEBUG</td>
<td>Detailed debug information.</td>
</tr>
</tbody>
</table>

#### $this->logger->level($message = string,  $context = array());

First choose your channel and set log level, you can send your additinonal context data using second parameter.

### Example Logging:

```php
$this->logger->channel('security');
$this->logger->alert('Possible hacking attempt !', array('username' => $username));
$this->logger->push('email');  // send all log data using email handler
$this->logger->push('mongo', LOG_ALERT);  // send just alert data to mongo db handler.
```

* VERY IMPORTANT: For a live site you'll usually only enable 0 - 4 to be logged otherwise your log files will fill up very fast.

### Primary Handler

Forexample to switch mongo database as a primary handler just replace "file" as "mongo".

```php
<?php
/*
|--------------------------------------------------------------------------
| Logger
|--------------------------------------------------------------------------
| Define your handlers the "last parameter" is "priority" of the handler.
|
*/
$c['logger'] = function () {
    $logger = new Obullo\Log\Logger;
    $logger->addHandler(
        'file',
        function () use ($logger) { 
            return new Obullo\Log\Handler\File($logger);  // primary
        },
        2  // priority
    );
    $logger->addHandler(
        'mongo', 
        function () use ($logger) { 
            return new Obullo\Log\Handler\Mongo(
                $logger, 
                array(
                'db.dsn' => 'mongodb://root:12345@localhost:27017/test', 
                'db.collection' => 'logs'
                )
            );
        },
        1
    );
    return $logger;
};
```

#### Displaying Logs

You can follow the all log messages using below the command.

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

#### Clear All Log Data

```php
php task clear
```

### Function Reference

------

#### $this->logger->debug = true

On / Off debug html output. When it is enabled all handlers will be disabled.

#### $this->logger->channel(string $channel);

Sets log channel.

#### $this->logger->emergency(string $message = '', $context = array());

Create <b>LOG_EMERG</b> level log message.

#### $this->logger->alert(string $message = '', $context = array());

Create <b>LOG_ALERT</b> level log message.

#### $this->logger->critical(string $message = '', $context = array());

Create <b>LOG_CRIT</b> level log message.

#### $this->logger->error(string $message = '', $context = array());

Create <b>LOG_ERROR</b> level log message.

#### $this->logger->warning(string $message = '', $context = array());

Create <b>LOG_WARNING</b> level log message.

#### $this->logger->notice(string $message = '', $context = array());

Create <b>LOG_NOTICE</b> level log message.

#### $this->logger->info(string $message = '', $context = array());

Create <b>LOG_INFO</b> level log message.
    
#### $this->logger->debug(string $message = '', $context = array());

Create <b>LOG_DEBUG</b> level log message.

#### $this->logger->push(string $handler = 'mongo', $threshold = null);

Push current page log data to log handlers.