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
define('ENV', 'DEBUG');

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
| Global Config Files
|--------------------------------------------------------------------------
*/
require APP .'config'. DS . strtolower(ENV) . DS .'config'. EXT;
require DATA .'cache'. DS .'packages.cache';
/*
|--------------------------------------------------------------------------
| Build IOC
|--------------------------------------------------------------------------
*/
require PACKAGES .'pimple'. DS .'releases'. DS .$packages['dependencies']['pimple']['version']. DS .'pimple'. EXT;
$c = new Pimple;
/*
|--------------------------------------------------------------------------
| Load Logger Package
|--------------------------------------------------------------------------
*/
if ($config['log_enabled']) {
    include PACKAGES .'logger'. DS .'releases'. DS .$packages['dependencies']['logger']['version']. DS .'logger'. EXT;
    $c['Logger'] = function () {
        return new Logger;
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
    Class Logger
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
    $c['Logger'] = function () {
        return new Logger;
    };
}

/*
|--------------------------------------------------------------------------
| Load Common Functions
|--------------------------------------------------------------------------
*/
require PACKAGES .'obullo'. DS .'releases'. DS .$packages['dependencies']['obullo']['version']. DS .'common'. EXT;
/*
|--------------------------------------------------------------------------
| Default Your Components & Services
|--------------------------------------------------------------------------
| Notice: You don't need to define all classes in here
| If a class not defined here framework load it from
| /packages folder then assign it to Controller instance
| usign getInstance() method.
| 
*/
$c['Uri'] = function () {
    return new Uri;
};
$c['Router'] = function () { 
    return new Router;
};
$c['Hooks'] = function () { 
    return new Hooks;
};
$c['Security'] = function () { 
    return new Security;
};
$c['Config'] = function () { 
    return getInstance()->config = new Config;
};
$c['Translator'] = function () { 
    return getInstance()->translator = new Translator;
};
$c['Response'] = function () { 
    return getInstance()->response = new Response;
};
$c['Sess'] = function () use ($c) {      // Build Session Driver
    return getInstance()->sess = new Sess_Native($c['Config']->load('sess'));
};
$c['View'] = function () { 
    return getInstance()->view = new View;
};
/*
|--------------------------------------------------------------------------
| Load Framework
|--------------------------------------------------------------------------
*/
require PACKAGES .'obullo'. DS .'releases'. DS .$packages['dependencies']['obullo']['version']. DS .'obullo'. EXT;