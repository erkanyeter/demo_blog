<?php
/*
|--------------------------------------------------------------------------
| Constants.
|--------------------------------------------------------------------------
 */
if ( ! defined('ROOT')) {
    include 'constants';
}
/*
|--------------------------------------------------------------------------
| Php Errors ( Default Off ) 
|--------------------------------------------------------------------------
| For security reasons its default off.
|
*/
error_reporting(1);
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
| Tasks ( Command Line Interface )
|--------------------------------------------------------------------------
*/ 
if (defined('STDIN')) {
    $_SERVER['HTTP_USER_AGENT']     = 'Cli';
    $_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

    set_time_limit(0);                  // Php execution limit, 0 = Unlimited
    ini_set('memory_limit', '100000M'); // Increase the maximum amount of memory available to PHP Cli operations.
}

require OBULLO_CONTAINER;
require OBULLO_CONFIG;
/*
|--------------------------------------------------------------------------
| Container ( IOC )
|--------------------------------------------------------------------------
*/
$c = new Obullo\Container\Pimple;
/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
*/
$c['config'] = function () { 
    return new Obullo\Config\Config;
};
/*
|--------------------------------------------------------------------------
| Autoloader
|--------------------------------------------------------------------------
*/
require OBULLO_CORE;
require OBULLO_AUTOLOADER;
/*
|--------------------------------------------------------------------------
| Logger
|--------------------------------------------------------------------------
| Define your handlers the "last parameter" is "priority" of the handler.
|
*/
$c['logger'] = function () {
    return new Obullo\Log\Task\Logger(array(LOGGER_FILE, LOGGER_SYSLOG, LOGGER_MONGO));
};
/*
|--------------------------------------------------------------------------
| Disabled Logger
|--------------------------------------------------------------------------
*/
if ($c['config']['log']['enabled'] == false) {
    $c['logger'] = function () {
        return new Obullo\Logger\Disabled;
    };
}
/*
|--------------------------------------------------------------------------
| Error Handler
|--------------------------------------------------------------------------
*/
$c['error'] = function () { 
    return new Obullo\Error\Handler;
};
/*
|--------------------------------------------------------------------------
| Exceptions
|--------------------------------------------------------------------------
*/
$c['exception'] = function ($e) {
    $exception = new Obullo\Exception\Error;
    $exception->display($e);
};
/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/
require OBULLO_CONTROLLER;
/*
|--------------------------------------------------------------------------
| App Controller
|--------------------------------------------------------------------------
*/
$c['app'] = function () {
    return new Obullo\App\Controller;
};
/*
|--------------------------------------------------------------------------
| Uri
|--------------------------------------------------------------------------
*/
$c['uri'] = function () use ($c) {
    return new Obullo\Uri\Uri($c['config']['uri']); 
};
/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/
$c['router'] = function () use ($c) { 
    return new Obullo\Router\Router($c['config']['routes']);
};
/*
|--------------------------------------------------------------------------
| Translator
|--------------------------------------------------------------------------
*/
$c['translator'] = function () use ($c) { 
    return $c['app']->translator = new Obullo\I18n\Translator($c['config']->load('translator'));
};
/*
|--------------------------------------------------------------------------
| Hooks
|--------------------------------------------------------------------------
*/
$c['hooks'] = function () use ($c) { 
    return new Obullo\Hooks\Hooks($c['config']->load('hooks'));
};
/*
|--------------------------------------------------------------------------
| Security
|--------------------------------------------------------------------------
*/
$c['security'] = function () { 
    return $c['app']->security = new Obullo\Security\Security;
};
/*
|--------------------------------------------------------------------------
| Http Component
|--------------------------------------------------------------------------
*/
$c['request'] = function () use ($c) { 
    return $c['app']->request = new Obullo\Http\Request;
};
$c['response'] = function () use ($c) { 
    return $c['app']->response = new Obullo\Http\Response;
};
$c['get'] = function () use ($c) { 
    return $c['app']->get = new Obullo\Http\Get;
};
$c['post'] = function () use ($c) { 
    return $c['app']->post = new Obullo\Http\Post;
};
/*
|--------------------------------------------------------------------------
| View Component
|--------------------------------------------------------------------------
*/
$c['view'] = function () use ($c) { 
    return $c['app']->view = new Obullo\View\View;
};
/*
|--------------------------------------------------------------------------
| Database Component
|--------------------------------------------------------------------------
*/
$c['db'] = function () use ($c) {
    return $c['app']->db = new Obullo\Database\Pdo\Mysql($c['config']['database']);
};
/*
|--------------------------------------------------------------------------
| Crud ( Active Record ) Component
|--------------------------------------------------------------------------
*/
$c['crud'] = function () use ($c) {
    return $c['app']->db = new Obullo\Crud($c['db']);  // Replace database object with crud.
};
/*
|--------------------------------------------------------------------------
| Session Component
|--------------------------------------------------------------------------
*/
$c['sess'] = function () use ($c) {
    return $c['app']->sess = new Obullo\Http\Session\Native($c['config']['session']);
};

// SERVICES
/*
|--------------------------------------------------------------------------
| Cache Service
|--------------------------------------------------------------------------
*/
$c['cache'] = function () use ($c) {
    return $c['app']->cache = new Obullo\Cache\Redis($c['config']['cache']);
};

// PROVIDERS
/*
|--------------------------------------------------------------------------
| NoSQL Provider
|--------------------------------------------------------------------------
*/
$c['mongo'] = function ($params) use ($c) {
    $mongoClient = new MongoClient('mongodb://root:12345@localhost:27017/'.$params['db.name']);
    return new MongoCollection($mongoClient->{$params['db.name']}, $params['db.collection']);
};
/*
|--------------------------------------------------------------------------
| Start
|--------------------------------------------------------------------------
*/
require OBULLO_PHP;


/* End of file index.php */
/* Location: .index.php */