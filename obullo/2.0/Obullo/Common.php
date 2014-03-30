<?php

// ------------------------------------------------------
// Common Functions
// ------------------------------------------------------

/**
 * Clean Input Data
 *
 * This is a helper function. It escapes data and
 * standardizes newline characters to \n
 *
 * @param string $str input
 * 
 * @return   string
 */
function cleanInputData($str)
{
    global $c;
    if (is_array($str)) {
        $new_array = array();
        foreach ($str as $key => $val) {
            $new_array[cleanInputKeys($key)] = cleanInputData($val);
        }
        return $new_array;
    }
    $str = removeInvisibleCharacters($str); // Remove control characters
    if ($c['config']['security']['xss_filtering']) {  // Should we filter the input data?
        $str = $c['security']->xssClean($str);
    }
    return $str;
}

// ------------------------------------------------------------------------

/**
 * Clean Keys
 *
 * This is a helper function. To prevent malicious users
 * from trying to exploit keys we make sure that keys are
 * only named with alpha-numeric text and a few other items.
 *
 * @param string $str input 
 * 
 * @return string
 */
function cleanInputKeys($str)
{
    if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
        die('Malicious Key Characters.');
    }
    return $str;
}

// --------------------------------------------------------------------

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @param string  $str         text
 * @param boolean $url_encoded encode option
 * 
 * @return string
 */
function removeInvisibleCharacters($str, $url_encoded = true)
{
    $non_displayables = array();  // every control character except newline (dec 10)
    if ($url_encoded) {           // carriage return (dec 13), and horizontal tab (dec 09)
        $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
    }
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127
    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}

// Exception & Errors
// ------------------------------------------------------------------------

/**
 * Catch All Exceptions
 * 
 * @param object $e    exception object
 * @param object $type exception type 
 * 
 * @return void
 */
function exceptionsHandler($e, $type = '')
{
    global $version, $c;

    $shutdownErrors = array(
        'ERROR' => 'ERROR', // E_ERROR 
        'PARSE ERROR' => 'PARSE ERROR', // E_PARSE
        'COMPILE ERROR' => 'COMPILE ERROR', // E_COMPILE_ERROR   
        'USER FATAL ERROR' => 'USER FATAL ERROR', // E_USER_ERROR
    );
    $shutdownError = false;
    if (isset($shutdownErrors[$type])) {  // We couldn't use any object for shutdown errors.

        $shutdownError = true;
        $type          = ucwords(strtolower($type));
        $code          = $e->getCode();
        $level         = $c['config']['error']['reporting'];

        if (defined('STDIN')) {  // If Command Line Request.
            echo $type . ': ' . $e->getMessage() . ' File: ' . $error->getSecurePath($e->getFile()) . ' Line: ' . $e->getLine() . "\n";

            $cmdType = (defined('TASK')) ? 'Task' : 'Cmd';
            $c['logger']->error('(' . $cmdType . ') ' . $type . ': ' . $e->getMessage() . ' ' . $c['error']->getSecurePath($e->getFile()) . ' ' . $e->getLine());
            return;
        }
        if ($level > 0 OR is_string($level)) {  // If user want to display all errors
            if (is_numeric($level)) {
                switch ($level) {
                case 0:
                    return;
                    break;
                case 1:
                    include OBULLO .'Exception'. DS . 'Html' . EXT;
                    return;
                    break;
                }
            }
            $rules = $c['error']->parseRegex($level);
            if ($rules == false) {
                return;
            }
            $allowedErrors = $error->getAllowedErrors($rules);  // Check displaying error enabled for current error.

            if (isset($allowedErrors[$code])) {
                include OBULLO .'Exception'. DS . 'Html' . EXT;
            }
        } else {  
            include APP . 'errors' . DS . 'disabled_error' . EXT;  // If error_reporting = 0, we show a blank page template.
        }
        $c['logger']->error($type . ': ' . $e->getMessage() . ' ' . $c['error']->getSecurePath($e->getFile()) . ' ' . $e->getLine());

    } else {  // Is It Exception ? Initialize to Exceptions Component.

        $exception = $c->raw('exception');
        $exception($e, $type);
    }
    return;
}

// --------------------------------------------------------------------

/**
 * Main Error Handler
 * Predefined error constants
 * http://usphp.com/manual/en/errorfunc.constants.php
 * 
 * @access private
 * @param int $errno
 * @param string $errstr
 * @param string $errfile
 * @param int $errline
 */
set_error_handler(
    function ($errno, $errstr, $errfile, $errline) {
        if ($errno == 0) {
            return;
        }
        switch ($errno) {
        case '1': $type = 'ERROR';
            break; // E_WARNING
        case '2': $type = 'WARNING';
            break; // E_WARNING
        case '4': $type = 'PARSE ERROR';
            break; // E_PARSE
        case '8': $type = 'NOTICE';
            break; // E_NOTICE
        case '16': $type = 'CORE ERROR';
            break; // E_CORE_ERROR
        case '32': $type = "CORE WARNING";
            break; // E_CORE_WARNING
        case '64': $type = 'COMPILE ERROR';
            break; // E_COMPILE_ERROR
        case '128': $type = 'COMPILE WARNING';
            break; // E_COMPILE_WARNING
        case '256': $type = 'USER FATAL ERROR';
            break; // E_USER_ERROR
        case '512': $type = 'USER WARNING';
            break; // E_USER_WARNING
        case '1024': $type = 'USER NOTICE';
            break; // E_USER_NOTICE
        case '2048': $type = 'STRICT ERROR';
            break; // E_STRICT
        case '4096': $type = 'RECOVERABLE ERROR';
            break; // E_RECOVERABLE_ERROR
        case '8192': $type = 'DEPRECATED ERROR';
            break; // E_DEPRECATED
        case '16384': $type = 'USER DEPRECATED ERROR';
            break; // E_USER_DEPRECATED
        case '30719': $type = 'ERROR';
            break; // E_ALL
        }
        exceptionsHandler(new ErrorException($errstr, $errno, 0, $errfile, $errline), $type);
        return;
    }
);

// -------------------------------------------------------------------- 

set_exception_handler('exceptionsHandler');

/**
 * Catch last occured errors.
 *
 * @access private
 * @return void
 */
register_shutdown_function(
    function () {
        $error = error_get_last();
        if (!$error) {
            return;
        }
        ob_get_level() AND ob_clean(); // Clean the output buffer
        $shutdownErrors = array(
            '1'   => 'ERROR', // E_ERROR 
            '4'   => 'PARSE ERROR', // E_PARSE
            '64'  => 'COMPILE ERROR', // E_COMPILE_ERROR
            '256' => 'USER FATAL ERROR', // E_USER_ERROR
        );
        $type = (isset($shutdownErrors[$error['type']])) ? $shutdownErrors[$error['type']] : '';
        exceptionsHandler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']), $type);
    }
);

// END Common.php File
/* End of file Common.php

/* Location: .Obullo/Obullo/Common.php */