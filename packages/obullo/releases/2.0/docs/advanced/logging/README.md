## Logging

------

The <kbd>Log Package</kbd> assists you to write messages to your log files.

**Note:** This package uses <kbd>Log_Write</kbd> component defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### Enable / Disable Log Writing

As default framework comes with logging disabled <kbd>( "0" )</kbd>. You can enable logging mods using <kbd>app/config/config.php</kbd>

On your localhost to see all log messages set <kbd>$config['log_threshold'] = (ENV == 'LIVE') ? 1 : 5;</kbd> level to <b>"5"</b> instead of <b>"0"</b>.

```php
/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to 
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|    0 = Disables logging, Error logging TURNED OFF
|    1 = Error Messages (including PHP errors)
|    2 = Debug Messages
|    3 = Informational Messages
|    4 = Benchmark Info
|    5 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold']         = 5;
$config['log_queries']           = true;
$config['log_benchmark']         = truw;
$config['log_date_format']       = 'Y-m-d H:i:s';
```
#### Explanation of Settings:

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

#### logMe('level', 'message')

This function lets you write messages to your log files. You must supply one of three "levels" in the first parameter, indicating what type of message it is (debug, error, info), with the message itself in the second parameter. Example:

```php
if (empty($someVar))
{
    logMe('error', 'Some variable did not contain a value.');
}
else
{
    logMe('debug', 'Some variable was correctly set');
}

logMe('info', 'The purpose of some variable is to provide some value.');
```

**Note:** In order for the log file to actually be written, the <b>"logs"</b> folder must be writable which is located at <kbd>app/logs</kbd>. In addition, you must set the "threshold" for logging. You might, for example, only want error messages to be logged, and not the other two types. If you set it to zero logging will be disabled. (Look at <kbd>app/config/config.php</kbd>)


Now above the message will be shown with different color on your console.

#### Debugging to Console

Console debug requires <kbd>task</kbd> package, first you need to install it then you can follow the all log messages using below the command.

```php
php task log
```
You can set the log level filter using the level argument.

```php
php task log level error
```

This command displays only log messages which are flagged as debug.

```php
php task log level debug
```

#### Clear All logs

```php
php task clear
```