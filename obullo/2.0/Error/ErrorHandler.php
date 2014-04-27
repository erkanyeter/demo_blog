<?php

namespace Obullo\Error;

use Obullo\Log\Logger, ErrorException;

// use Debug\Exception\ContextErrorException;
// use Debug\Exception\FatalErrorException;
// use Debug\Exception\DummyException;
// use Debug\FatalErrorHandler\UndefinedFunctionFatalErrorHandler;
// use Debug\FatalErrorHandler\ClassNotFoundFatalErrorHandler;

/**
 * ErrorHandler.
 * Modeled after Symfony Debug package.
 */
Class ErrorHandler
{
    const TYPE_DEPRECATION = -100;

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
        E_PARSE             => 'Parse',
    );

    protected $logger;
    protected $level;
    protected $reservedMemory;
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
     * @param integer|null $level The level (null to use the error_reporting() value and 0 to disable)
     */
    public function setLevel($level)
    {
        $this->level = null === $level ? error_reporting() : $level;
    }

    /**
     * Sets the display_errors flag value.
     *
     * @param integer $displayErrors The display_errors flag value
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

                $c['logger']->channel('system');
                $c['logger']->warning($message, array('type' => self::TYPE_DEPRECATION, 'stack' => $stack));
            }
            return true;
        }

        if ($c['logger'] instanceof Logger) { 
            $c['logger']->emergency($message, array('type' => $this->level, 'file' => DebugOutput::getSecurePath($file), 'line' => $line, 'extra' => $context));
            $c['logger']->__destruct(); // continue log writing
        }

        if ($this->displayErrors 
            AND error_reporting() 
            AND $level 
            AND $this->level 
            AND $level
        ) {
            $e = new ErrorException($message, $level, 0, $file, $line);
            $this->displayException($e);
        }
        return false;
    }


    public function isXmlHttp()
    {
        if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    public function displayException($e, $fatalError = false)
    {
        global $c;

        if (is_object($c)) { 

            $lastQuery = '';                // show last sql query
            if (isset($c['app']->db)) {
                $lastQuery = '';
                if (method_exists($c['app']->db, 'lastQuery')) {
                    $lastQuery = $c['app']->db->lastQuery();
                }
            }

            ob_start();
            include OBULLO . 'Error' . DS . 'DisplayException' . EXT;  // load view
            $error_msg = ob_get_clean();

            if ($this->isXmlHttp()) {
                $error_msg = strip_tags('Exception Error: ' .$e->getMessage().' '. DebugOutput::getSecurePath($e->getFile()).' '. $e->getLine(). "\n");
            } 
            echo $error_msg;
        }
    }

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
        if ($c['logger'] instanceof Logger) {
            $c['logger']->channel('system');
            $c['logger']->emergency($error['message'], array('type' => $type, 'file' => $error['file'], 'line' => $error['line']));
        }
        if ( ! $this->displayErrors) {
            return;
        }
        $e = new ErrorException($error['message'], $type, 0, $error['file'], $error['line']);
        $this->displayException($e, true);
    }

}