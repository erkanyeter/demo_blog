<?php

namespace Obullo\Error;

use Obullo\Error\ErrorHandler;
use Obullo\Error\ExceptionHandler;

/**
 * Error Debug Cass
 * Modeled after Symfony Debug package.
 * 
 * @category  Error
 * @package   Debug
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/error
 */
Class Debug
{
    /**
     * Enable disable debugging
     * 
     * @var boolean
     */
    protected static $enabled = false;

    /**
     * Enable debugging & On / Off display errors
     * 
     * @param string  $level         error reporting level
     * @param boolean $displayErrors set php.ini display errors
     * 
     * @return void
     */
    public static function enable($level = null, $displayErrors = true)
    {    
        if (static::$enabled) {
            return;
        }
        static::$enabled = true;

        error_reporting(-1);

        ErrorHandler::register($level, $displayErrors);
        
        if ('cli' !== php_sapi_name()) {

            ExceptionHandler::register();  // Cli - display errors only if they're not already logged to STDERR

        } elseif ($displayErrors AND ( ! ini_get('log_errors') OR ini_get('error_log'))) {
            ini_set('display_errors', 1);
        }
    }
}

// END Error Debug class

/* End of file Debug.php */
/* Location: .Obullo/Error/Debug.php */