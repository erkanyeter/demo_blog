<?php

/*
|--------------------------------------------------------------------------
| APPLICATION ENVIRONMENT
|--------------------------------------------------------------------------
|
| You can load different configurations depending on your
| current environment. Setting the environment also influences
| things like logging and error reporting.
|
| This can be set to anything, but default usage is:
|
|     o DEBUG - Debug Mode  ( Quick Debugging, Show All Php Native Errors )
|     o TEST  - Testing     ( Test mode, behaviours like LIVE )
|     o LIVE  - Production  ( Production mode, all errors disabled )
|
*/
define('ENV', 'debug');

/*
|--------------------------------------------------------------------------
| Native PHP Error Handler (Default Off) 
|--------------------------------------------------------------------------
| For security reasons its default off.
| But default `Error Handler` is active if you don't want to use Framework
| development error handler you can *turn off it easily from "app/config/config.php" 
| file.
|
*/
error_reporting(1);
ini_set('display_errors', 'On');

/*
|--------------------------------------------------------------------------
| Set Default Time Zone Identifer.
|--------------------------------------------------------------------------
|                                                                 
| Set the default timezone identifier for date function ( Server Time )
| @see  http://www.php.net/manual/en/timezones.php
| 
*/
date_default_timezone_set('Europe/London');

/*
|--------------------------------------------------------------------------
| Application Constants.
|--------------------------------------------------------------------------
| This file specifies which APP constants should be loaded by default.
|
 */
if ( ! defined('ROOT')) {
    include 'constants';
}

/*
|--------------------------------------------------------------------------
| Disable Deprecated Zend Mode
|--------------------------------------------------------------------------
|
| Enable compatibility mode with Zend Engine 1 (PHP 4). It affects the cloning, 
| casting (objects with no properties cast to false or 0), and comparing of objects. 
| In this mode, objects are passed by value instead of reference by default.
| 
| This feature has been DEPRECATED and REMOVED as of PHP 5.3.0. 
| It should be '0'. 
| 
*/
ini_set('zend.ze1_compatibility_mode', 0); 
             
/*
|--------------------------------------------------------------------------
| Framework Command Line ( Tasks )
|--------------------------------------------------------------------------
|  @see User Guide: Chapters / General Topics / Tasks
|
*/ 
if (defined('STDIN')) {
    /*
    |--------------------------------------------------------------------------
    | Set Command Line Server Headers
    |--------------------------------------------------------------------------
    */ 
    $_SERVER['HTTP_USER_AGENT']     = 'Framework CLI';
    $_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

    /*
    |--------------------------------------------------------------------------
    | Set Execution Limit
    |--------------------------------------------------------------------------
    | 
    | Limits the maximum execution time. 0 = Unlimited.
    | Set the number of seconds a script is allowed to run. If this is reached, 
    | the script returns a fatal error. The default limit is 30 seconds or, if it exists, 
    | the max_execution_time value defined in the php.ini.
    | 
    */
    set_time_limit(0);

    /*
    |--------------------------------------------------------------------------
    | Set Memory limit
    |--------------------------------------------------------------------------
    |
    | Increase the maximum amount of memory available to PHP Cli
    | operations.
    | 
    */
    ini_set('memory_limit', '100000M');
}
/*
|--------------------------------------------------------------------------
| Load Common Functions
|--------------------------------------------------------------------------
*/
require OBULLO .'common'. EXT;
/*
|--------------------------------------------------------------------------
| Build IOC
|--------------------------------------------------------------------------
*/
require OBULLO .$version. DS .'Container'. DS .'Container'. EXT;
$c = new Obullo\Container;  // Dependency Container

/*
|--------------------------------------------------------------------------
| Global Config Files
|--------------------------------------------------------------------------
*/
require OBULLO .$version. DS .'Config'. DS .'Config'. EXT;
$config = new Obullo\Config;

require DATA .'cache'. DS .'version.cache';

/*
|--------------------------------------------------------------------------
| Load Logger Package
|--------------------------------------------------------------------------
*/
if ($config['log']['enabled']) {
    include OBULLO .$version. DS .'Logger'. DS .'Logger'. EXT;
    $c['logger'] = function () {
        return new Obullo\Logger;
    };
} else {
    /**
     * If logging feature closed don't load the class.
     * Create a fake logger and use it.
     * 
     * @category  Fake_Logger
     * @package   Logger
     * @author    Obullo Framework <obulloframework@gmail.com>
     * @copyright 2009-2014 Obullo
     * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
     * @link      http://obullo.com/docs/package/logger
     */
    Class Obullo_Logger
    {
        /**
         * Call fake methods
         * 
         * @param string $method    method
         * @param array  $arguments arguments
         * 
         * @return void
         */
        public function __call($method, $arguments)
        { 
            $method    = null;
            $arguments = null;
            return; 
        } 
    }
    // Create logger component
    //-------------------------------------
    $c['logger'] = function () {
        return new Obullo_Logger;
    };
}
require OBULLO .$version. DS .'Controller'. DS .'Controller'. EXT;

/*
|--------------------------------------------------------------------------
| Core Components
|--------------------------------------------------------------------------
*/
$c['app'] = function () {
    return new Obullo\App;
};
$c['uri'] = function () {
    return new Obullo\Uri;
};
$c['router'] = function () { 
    return new Obullo\Router;
};
$c['hooks'] = function () { 
    return new Obullo\Hooks;
};
$c['security'] = function () { 
    return new Obullo\Security;
};
$c['config'] = function () use ($config) { 
    return $config;
};
$c['error'] = function () { 
    return new Obullo\Error;
};
$c['exception'] = function ($e, $type) { 
    $exception = new Obullo\Exception;
    $exception->write($e, $type);
};
/*
|--------------------------------------------------------------------------
| Define Your Service Components
|--------------------------------------------------------------------------
| Notice: You don't need to define all classes in here
| If class not defined in $c['App'], We load it from
| "/packages" folder & assign to Controller instance.
|
| Just define your service components here !
| forexample: 
|
| $c['Mailer'] = function () use ($c) {
|     return $c['App']->mailer = new Mailer;   // your mail handler
| }
|
*/
$c['translator'] = function () use ($c) { 
    return $c['app']->translator = new Obullo\Translator;
};
$c['response'] = function () use ($c) { 
    return $c['app']->response = new Obullo\Response;
};
$c['view'] = function () use ($c) { 
    return $c['app']->view = new Obullo\View;
};
$c['sess'] = function () use ($c) {
    return $c['app']->sess = new Obullo\Sess\Native($c['config']->load('sess')); // Build Session Driver
};
$c['db'] = function () use ($c) {
    return $c['app']->db = new Obullo\Pdo\Mysql($c['config']->load('database')); // Build Cache Driver
};
$c['crud'] = function () use ($c) {
    return $c['app']->db = new Obullo\Crud($c['db']);  // Replace database object with crud if it used.
};
$c['cache'] = function () use ($c) {
    return $c['app']->cache = new Obullo\Cache\Redis($c['config']->load('cache'));   // Build Cache Driver
};
/*
|--------------------------------------------------------------------------
| Load Framework
|--------------------------------------------------------------------------
*/
require OBULLO .$version. DS .'obullo'. EXT;