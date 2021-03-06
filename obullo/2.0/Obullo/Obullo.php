<?php

/**
 * Obullo
 * 
 * @category  Core
 * @package   Obullo
 * @author    Obullo Lvc Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/obullo
 */
$start = microtime(true);  // Run Timer

$hooks_enabled = $c['config']['hooks']['enabled'];

/*
|--------------------------------------------------------------------------
| Instantiate the hooks class
|--------------------------------------------------------------------------
*/
if ($hooks_enabled) {
    $c['hooks']->call('pre_system');
    // @todo
    // $['event']->register('before.request');
}
/*
|--------------------------------------------------------------------------
| Sanitize inputs
|--------------------------------------------------------------------------
*/
if ($c['config']['uri']['query_strings'] == false) {  // Is $_GET data allowed ? If not we'll set the $_GET to an empty array
    $_GET = array();
}
$_GET  = cleanInputData($_GET);
$_POST = cleanInputData($_POST);  // Clean $_POST Data
$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']); // Sanitize PHP_SELF

if ($c['config']['security']['csrf']['protection']) {  // CSRF Protection check
    $c['security']->initCsrf();
    $c['security']->csrfVerify();
}
// Clean $_COOKIE Data
// Also get rid of specially treated cookies that might be set by a server
// or silly application, that are of no use to application anyway
// but that when present will trip our 'Disallowed Key Characters' alarm
// http://www.ietf.org/rfc/rfc2109.txt
// note that the key names below are single quoted strings, and are not PHP variables
unset($_COOKIE['$Version']);
unset($_COOKIE['$Path']);
unset($_COOKIE['$Domain']);

$_COOKIE = cleanInputData($_COOKIE);
/*
 * ------------------------------------------------------
 *  Log requests
 * ------------------------------------------------------
 */
$c['logger']->debug('$_REQUEST_URI: ' . $c['uri']->getRequestUri(), array(), 10);
$c['logger']->debug('$_COOKIE: ', $_COOKIE, 9);
$c['logger']->debug('$_POST: ', $_POST, 9);
$c['logger']->debug('$_GET: ', $_GET, 9);
$c['logger']->debug('Global POST and COOKIE data sanitized', array(), 10);
/*
 * ------------------------------------------------------
 *  Load core components
 * ------------------------------------------------------
 */
$pageUri    = "{$c['router']->fetchDirectory()} / {$c['router']->fetchClass()} / {$c['router']->fetchMethod()}";
$controller = PUBLIC_DIR . $c['router']->fetchDirectory() . DS . 'controller' . DS . $c['router']->fetchClass() . EXT;

if ( ! file_exists($controller)) {
    $c['response']->show404($pageUri);
}
/*
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
if ($hooks_enabled) {
    $c['hooks']->call('pre_controller');
}
// $c['event']->fire('before.controller');

require $controller;  // call the controller.  $app variable now Available in HERE !!

// Do not run private methods. ( _output, _remap, )

if (strncmp($c['router']->fetchMethod(), '_', 1) == 0 
    OR in_array(strtolower($c['router']->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
) {
    $c['response']->show404($pageUri);
}
/*
 * ------------------------------------------------------
 *  Is there a "post_controller_constructor" hook?
 * ------------------------------------------------------
 */
if ($hooks_enabled) {
    $c['hooks']->call('post_controller_constructor');
}
// $['event']->fire('before.response');

$storedMethods = array_keys($app->controllerMethods);

if ( ! in_array(strtolower($c['router']->fetchMethod()), $storedMethods)) {  // Check method exist or not
    $c['response']->show404($pageUri);
}

$arguments = array_slice($c['uri']->rsegments, 2);

if (method_exists($app, '_remap')) {  // Is there a "remap" function? If so, we call it instead
    $app->_remap($c['router']->fetchMethod(), $arguments);
} else {

    // Call the requested method. Any URI segments present (besides the directory / class / method) 
    // will be passed to the method for convenience
    // directory = 0, class = 1,  ( arguments = 2) ( @deprecated  method = 2 method always = index )
    call_user_func_array(array($app, $c['router']->fetchMethod()), $arguments);
}
/*
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
if ($hooks_enabled) {
    $c['hooks']->call('post_controller');
}
/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */
if ($hooks_enabled) {
    if ($c['hooks']->call('display_override') === false) {
        $c['response']->sendOutput();  // Send the final rendered output to the browser
    }
} else {
    $c['response']->sendOutput();    // Send the final rendered output to the browser
}

/*
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
if ($hooks_enabled) {
    $c['hooks']->call('post_system');
}
// $['event']->register('after.response');

$end = microtime(true) - $start;  // End Timer

$extra = array();
if ($c['config']['log']['benchmark']) {     // Do we need to generate benchmark data ? If so, enable and run it.
    $usage = 'memory_get_usage() function not found on your php configuration.';
    if (function_exists('memory_get_usage') AND ($usage = memory_get_usage()) != '') {
        $usage = number_format($usage) . ' bytes';
    }
    $extra = array('time' => number_format($end, 4), 'memory' => $usage);
}

$c['logger']->debug('Final output sent to browser', $extra, -99);

// $['event']->fire('shutdown');

// END Obullo.php File
/* End of file Obullo.php

/* Location: .Obullo/Obullo/Obullo.php */