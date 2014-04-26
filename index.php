<?php
/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
*/
if ( ! defined('ROOT')) {  // Cli support
    include 'constants';
}
/*
|--------------------------------------------------------------------------
| Php Errors ( Default Off ) 
|--------------------------------------------------------------------------
| For security reasons its default off.
|
*/
error_reporting(E_ALL);
ini_set('display_errors', 'On');
/*
|--------------------------------------------------------------------------
| Set Default Time Zone Identifer. @link http://www.php.net/manual/en/timezones.php
|--------------------------------------------------------------------------                                        
| Set the default timezone identifier for date function ( Server Time ).
|
*/
date_default_timezone_set('Europe/London');
/*
|--------------------------------------------------------------------------
| Command Line Interface
|--------------------------------------------------------------------------
*/ 
if (defined('STDIN')) {
    $_SERVER['HTTP_USER_AGENT']     = 'Cli';
    $_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

    set_time_limit(0);                   // Php execution limit, 0 = Unlimited
    ini_set('memory_limit', '100000M');  // Set maximum amount of memory for Cli operations.
}

require OBULLO_CONTAINER;
require OBULLO_CONFIG;
require OBULLO_CORE;
require OBULLO_AUTOLOADER;
require OBULLO_COMPONENTS;
require OBULLO_SERVICES;
require OBULLO_PHP;

Obullo\Error\ErrorHandler::register(E_ALL);


/* End of file index.php */
/* Location: .index.php */