<?php

namespace Obullo\Error;

use Obullo\Log\Logger, ErrorException;

/**
 * Error Handler Class
 * Modeled after Symfony ErrorHandler
 * 
 * @category  Error
 * @package   ErrorHandler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/error
 */
Class ErrorHandler
{
    /**
     * Deprecated constant
     */
    const TYPE_DEPRECATION = -100;

    /**
     * Error Levels
     * 
     * @var array
     */
    protected $levels = array(
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
        E_ERROR             => 'Error',
        E_CORE_ERROR        => 'Core Error',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_CORE_WARNING      => 'Core Warning',
        E_PARSE             => 'Parse Error',
        E_ALL               => 'All Errors',
    );

    /**
     * Logger class
     * 
     * @var object
     */
    protected $logger;

    /**
     * Current php error level
     * 
     * @var integer
     */
    protected $level;

    /**
     * Display Errors On \ Off
     * 
     * @var boolean
     */
    protected $displayErrors;

    /**
     * Registers the error handler.
     *
     * @param integer $level         The level at which the conversion to Exception is done (null to use the error_reporting() value and 0 to disable)
     * @param Boolean $displayErrors Display errors (for dev environment) or just log them (production usage)
     *
     * @return ErrorHandler The registered error handler
     */
    public static function register($level = null, $displayErrors = true)
    {
        $handler = new static;
        $handler->setLevel($level);
        $handler->setDisplayErrors($displayErrors);

        ini_set('display_errors', 0);
        set_error_handler(array($handler, 'handle'));
        register_shutdown_function(array($handler, 'handleFatal'));

        return $handler;
    }

    /**
     * Sets the level at which the conversion to Exception is done.
     *
     * @param integer $level Integer Or "null" - The level (null to use the error_reporting() value and 0 to disable)
     *
     * @return void
     */
    public function setLevel($level)
    {
        $this->level = null === $level ? error_reporting() : $level;
    }

    /**
     * Sets the display_errors flag value.
     *
     * @param integer $displayErrors The display_errors flag value
     *
     * @return void
     */
    public function setDisplayErrors($displayErrors)
    {
        $this->displayErrors = $displayErrors;
    }

    /**
     * Error handler
     * 
     * @param integer $level   error no
     * @param string  $message error str
     * @param string  $file    error file
     * @param integer $line    error line
     * @param array   $context extra data
     * 
     * @return boolean
     *
     * @throws ErrorException If $this->displayErrors = true
     */
    public function handle($level, $message, $file = 'unknown', $line = 0, $context = array())
    {
        global $c;

        if (0 === $this->level) {
            return false;
        }

        if ($level & (E_USER_DEPRECATED | E_DEPRECATED)) {

            if (is_object($c) AND $c['logger'] instanceof Logger) {
                $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

                $c['logger']->channel($c['config']['log']['channel']);
                $c['logger']->warning($message, array('type' => self::TYPE_DEPRECATION, 'stack' => $stack));
            }
            return true;
        }
        unset($context); // remove context data 

        $type = (isset($this->levels[$this->level])) ? $this->levels[$this->level] : $this->level;

        // Log for local environment
        if ($c['logger'] instanceof Logger) {
            $c['logger']->channel($c['config']['log']['channel']);
            $c['logger']->emergency($message, array('level' => $type, 'file' => DebugOutput::getSecurePath($file), 'line' => $line, 'extra' => null));
        }

        if ($this->displayErrors 
            AND error_reporting() 
            AND $level 
            AND $this->level 
            AND $level
        ) {
            $e = new ErrorException($message, $level, 0, $file, $line);
            $c['exception']->showError($e);
        }
        return false;
    }

    /**
     * Fatal error handler
     * 
     * @return void
     */
    public function handleFatal()
    {
        global $c;

        if (null === $error = error_get_last()) {
            return;
        }

        $type = $error['type'];
        if (0 === $this->level OR ! in_array($type, array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE))) {
            return;
        }

        $type = (isset($this->levels[$type])) ? $this->levels[$type] : $type;

        if ($c['logger'] instanceof Logger) {
            $c['logger']->channel($c['config']['log']['channel']);
            $c['logger']->emergency($error['message'], array('level' => $type, 'file' => $error['file'], 'line' => $error['line']));
        }
        if ( ! $this->displayErrors) {
            return;
        }
        $e = new ErrorException($error['message'], $type, 0, $error['file'], $error['line']);
        $c['exception']->showError($e, true);
    }

}

// END ErrorHandler class

/* End of file ErrorHandler.php */
/* Location: .Obullo/Error/ErrorHandler.php */