<?php
/*
|--------------------------------------------------------------------------
| Debug Environment - Php Errors ( Default Off ) 
|--------------------------------------------------------------------------
| For security reasons its default off.
|
*/
error_reporting(1);
ini_set('display_errors', 'On');
/*
|--------------------------------------------------------------------------
| Set Default Time Zone Identifer.
|--------------------------------------------------------------------------                                        
| Set the default timezone identifier for date function ( Server Time )
|
| @link  http://www.php.net/manual/en/timezones.php
|
*/
date_default_timezone_set('Europe/London');

/*
|--------------------------------------------------------------------------
| Command Line Interface ( Tasks )
|--------------------------------------------------------------------------
*/ 
if (defined('STDIN')) {
    $_SERVER['HTTP_USER_AGENT']     = 'Cli';
    $_SERVER['HTTP_ACCEPT_CHARSET'] = 'utf-8';

    set_time_limit(0);                  // Php execution limit, 0 = Unlimited
    ini_set('memory_limit', '100000M'); // Increase the maximum amount of memory available to PHP Cli operations.
}

$c = new Obullo\Container\Pimple;       // Dependency Container
/*
|--------------------------------------------------------------------------
| Core Components
|--------------------------------------------------------------------------
*/
$c['config'] = function () { 
    return new Obullo\Config\Config;
};
$c['logger'] = function () use ($c) {
    return new Obullo\Logger\Handler\File($c['config']['logger']);
};
$c['error'] = function () { 
    return new Obullo\Error\Handler;
};
$c['exception'] = function ($e, $type) {
    $exception = new Obullo\Exception\Error;
    $exception->display($e, $type);
};
/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/
require OBULLO .'Controller'. DS .'Controller'. EXT;
/*
|--------------------------------------------------------------------------
| Core Components
|--------------------------------------------------------------------------
*/
$c['app'] = function () {
    return new Obullo\App\Controller;
};
$c['uri'] = function () use ($c) {
    return new Obullo\Uri\Uri($c['config']['uri']); 
};       
$c['router'] = function () use ($c) { 
    return new Obullo\Router\Router($c['config']['routes']);
};
$c['translator'] = function () use ($c) { 
    return new Obullo\I18n\Translator($c['config']->load('translator'));
};
$c['hooks'] = function () use ($c) { 
    return new Obullo\Hooks\Hooks($c['config']->load('hooks'));
};
$c['security'] = function () { 
    return new Obullo\Security\Security;
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
    return $c['app']->view = new Obullo\View;
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
    return $c['app']->db = new Obullo\Crud($c['db']);  // We replace database object with crud.
};
/*
|--------------------------------------------------------------------------
| Session Component
|--------------------------------------------------------------------------
*/
$c['sess'] = function () use ($c) {
    return $c['app']->sess = new Obullo\Http\Session\Native($c['config']['session']);
};
/*
|--------------------------------------------------------------------------
| Cache Service
|--------------------------------------------------------------------------
*/
$c['cache'] = function () use ($c) {
    return $c['app']->cache = new Obullo\Cache\Redis(
        array(
           'servers' => array(
                              'hostname' => '127.0.0.1',
                              'port'     => '6379',
                               // 'timeout'  => '2.5'   // 2.5 sec timeout, just for redis cache
                              'weight'   => '1'         // The weight parameter effects the consistent hashing 
                                                        // used to determine which server to read/write keys from.
                              ),
            'auth' =>  '',                         // Just redis cache for connection password
            'cache_path' =>  '/data/temp/cache/',  // Just cache file .data/temp/cache
        )
    );
};
/*
|--------------------------------------------------------------------------
| NoSQL Service
|--------------------------------------------------------------------------
*/
$c['mongo'] = function () {
    return new MongoClient('connection:string');
};