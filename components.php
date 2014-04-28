<?php
/*
|--------------------------------------------------------------------------
| COMPONENTS
|--------------------------------------------------------------------------
| This file specifies the your application components.
*/
/*
|--------------------------------------------------------------------------
| Logger
|--------------------------------------------------------------------------
| Define your handlers the "last parameter" is "priority" of the handler.
|
*/
$c['logger'] = function () use ($c) {
    
    if ($c['config']['log']['enabled'] == false) {  // Use disabled handler if config disabled.
        return new Obullo\Log\Disabled;
    }
    $logger = new Obullo\Log\Logger;
    $logger->addWriter(
        LOGGER_FILE,
        function () use ($logger) { 
            return new Obullo\Log\Handler\File($logger);  // primary
        },
        3  // priority
    );
    /*
    |--------------------------------------------------------------------------
    | Removes file handler and use second defined handler as primary 
    | in "production" mode.
    |--------------------------------------------------------------------------
    */
    if (ENV == 'live') {
        $logger->removeWriter(LOGGER_FILE);
        // $logger->addWriter(); your live log writer
    }
    return $logger;
};
/*
|--------------------------------------------------------------------------
| Exception
|--------------------------------------------------------------------------
*/
$c['exception'] = function () {
    return new Obullo\Error\Exception;
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
| Request
|--------------------------------------------------------------------------
*/
$c['request'] = function () use ($c) { 
    return $c['app']->request = new Obullo\Http\Request;
};
/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/
$c['response'] = function () use ($c) { 
    return $c['app']->response = new Obullo\Http\Response;
};
/*
|--------------------------------------------------------------------------
| Input Get
|--------------------------------------------------------------------------
*/
$c['get'] = function () use ($c) { 
    return $c['app']->get = new Obullo\Http\Get;
};
/*
|--------------------------------------------------------------------------
| Input Post
|--------------------------------------------------------------------------
*/
$c['post'] = function () use ($c) { 
    return $c['app']->post = new Obullo\Http\Post;
};
/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
*/
$c['view'] = function () use ($c) { 
    return $c['app']->view = new Obullo\View\View;
};


/* End of file components.php */
/* Location: .components.php */