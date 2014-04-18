<?php

if (isset($_SERVER['REMOTE_ADDR'])) die('Access denied');
/*
|--------------------------------------------------------------------------
| Log writer task
|--------------------------------------------------------------------------
| Send log data to queue
|
*/
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
| This file specifies which APP constants should be loaded by default.
|
*/
require 'constants';
require OBULLO_AUTOLOADER;
require OBULLO_CONTAINER;
require OBULLO_CONFIG;
/*
|--------------------------------------------------------------------------
| Container ( IOC ) & Config
|--------------------------------------------------------------------------
*/
$c = new Obullo\Container\Pimple;

$c['config'] = function () { 
    return new Obullo\Config\Config;
};
/*
|--------------------------------------------------------------------------
| Log records
|--------------------------------------------------------------------------
*/
$recordUnformatted = unserialize(base64_decode($_SERVER['argv'][1]));
/*
|--------------------------------------------------------------------------
| Logger Task
|--------------------------------------------------------------------------
*/
$logger = new Obullo\Log\Logger;
$logger->addHandler(
    LOGGER_FILE,
    function () use ($logger) { 
        return new Obullo\Log\Handler\File($logger);  // primary
    },
    3  // priority
);
$logger->addHandler(
    LOGGER_SYSLOG,
    function () use ($logger) { 
        return new Obullo\Log\Handler\Syslog(
            $logger, array(
            'app.name' => 'my_application', 
            'app.facility' => LOG_USER
            )
        );
    },
    2  // priority
);
$logger->addHandler(
    LOGGER_MONGO, 
    function () use ($logger) { 
        return new Obullo\Log\Handler\Mongo(
            $logger, 
            array(
            'db.dsn' => 'mongodb://root:12345@localhost:27017/test', 
            'db.collection' => 'test_logs'
            )
        );
    },
    1
);
/*
|--------------------------------------------------------------------------
| Removes file handler and use syslog handler as primary 
| in "production" mode.
|--------------------------------------------------------------------------
*/
if (ENV == 'live') {
    $logger->removeHandler(LOGGER_FILE);
}
$logger->sendToQueue($recordUnformatted); // Send to Job queue
$logger->__destruct();


/* End of file logger.php */
/* Location: .app/tasks/cli/log/write.php */