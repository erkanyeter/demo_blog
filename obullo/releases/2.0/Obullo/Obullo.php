<?php

/**
 * Obullo Class
 * 
 * @category  Core
 * @package   Obullo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/obullo
 */

$start = microtime(true);  // Run Timer
/*
 * ------------------------------------------------------
 *  Instantiate the hooks class
 * ------------------------------------------------------
 */
if ($config['enable_hooks']) {
    /*
     * ------------------------------------------------------
     *  Is there a "pre_system" hook?
     * ------------------------------------------------------
     */
    $c['Hooks']->call('pre_system');
}
/*
 * ------------------------------------------------------
 *  Sanitize Inputs
 * ------------------------------------------------------
 */
if ($config['enable_query_strings'] == false) {  // Is $_GET data allowed ? If not we'll set the $_GET to an empty array
    $_GET = array();
}
$_GET  = cleanInputData($_GET);
$_POST = cleanInputData($_POST);  // Clean $_POST Data
$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']); // Sanitize PHP_SELF

if ($config['csrf_protection']) {  // CSRF Protection check
    $c['Security']->initCsrf();
    $c['Security']->csrfVerify();
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

$c['Logger']->debug('Global POST and COOKIE data sanitized');
/*
 * ------------------------------------------------------
 *  Log requests
 * ------------------------------------------------------
 */
if ($c['Logger']->getProperty('enabled')) {
    $c['Logger']->debug('$_REQUEST_URI: ' .$c['Uri']->getRequestUri());
    if (ENV == 'debug' OR ENV == 'test') {
        $c['Logger']->debug('$_COOKIE: ', $_COOKIE);
        $c['Logger']->debug('$_POST: ', $_POST);
        $c['Logger']->debug('$_GET: ', $_GET);
    }
}
/*
 * ------------------------------------------------------
 *  Load core components
 * ------------------------------------------------------
 */
$pageUri    = "{$c['Router']->fetchDirectory()} / {$c['Router']->fetchClass()} / {$c['Router']->fetchMethod()}";
$controller = PUBLIC_DIR . $c['Router']->fetchDirectory() . DS . 'controller' . DS . $c['Router']->fetchClass() . EXT;

if ( ! file_exists($controller)) {
    $c['Response']->show404($pageUri);
}
/*
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
if ($config['enable_hooks']) {
    $c['Hooks']->call('pre_controller');
}

require $controller;  // call the controller.  $c variable now Available in HERE !!

// Do not run private methods. ( _output, _remap, _getInstance .. )

if (strncmp($c['Router']->fetchMethod(), '_', 1) == 0 
    OR in_array(strtolower($c['Router']->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
) {
    $c['Response']->show404($pageUri);
}
/*
 * ------------------------------------------------------
 *  Is there a "post_controller_constructor" hook?
 * ------------------------------------------------------
 */
if ($config['enable_hooks']) {
    $c['Hooks']->call('post_controller_constructor');
}
$storedMethods = array_keys($app->controllerMethods);

if ( ! in_array(strtolower($c['Router']->fetchMethod()), $storedMethods)) {  // Check method exist or not
    $c['Response']->show404($pageUri);
}

$arguments = array_slice($c['Uri']->rsegments, 2);

if (method_exists($app, '_remap')) {  // Is there a "remap" function? If so, we call it instead
    $app->_remap($c['Router']->fetchMethod(), $arguments);
} else {
    // Call the requested method. Any URI segments present (besides the directory / class / method) 
    // will be passed to the method for convenience
    // directory = 0, class = 1,  ( arguments = 2) ( @deprecated  method = 2 method always = index )
    call_user_func_array(array($app, $c['Router']->fetchMethod()), $arguments);
}
/*
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
if ($config['enable_hooks']) {
    $c['Hooks']->call('post_controller');
}
/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */
if ($config['enable_hooks']) {
    if ($c['Hooks']->call('display_override') === false) {
        $c['Response']->_sendOutput();  // Send the final rendered output to the browser
    }
} else {
    $c['Response']->_sendOutput();    // Send the final rendered output to the browser
}
/*
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
if ($config['enable_hooks']) {
    $c['Hooks']->call('post_system');
}

$time = microtime(true) - $start;  // End Timer

$extra = array();
if ($config['log_benchmark']) {     // Do we need to generate benchmark data ? If so, enable and run it.
    $usage = 'memory_get_usage() function not found on your php configuration.';
    if (function_exists('memory_get_usage') AND ($usage = memory_get_usage()) != '') {
        $usage = number_format($usage) . ' bytes';
    }
    $extra = array('time' => number_format($time, 4), 'memory' => $usage);
}
$c['Logger']->debug('Final output sent to browser', $extra);


// END Obullo.php File
/* End of file Obullo.php

/* Location: .Obullo/Obullo/Obullo.php */