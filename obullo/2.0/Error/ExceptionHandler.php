<?php

namespace Obullo\Error;

use Obullo\Log\Logger, ErrorException;

/**
 * Exception Handler Class
 * 
 * @category  Error
 * @package   ExceptionHandler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/error
 */
Class ExceptionHandler
{
    /**
     * Debug on /off variable
     * 
     * @var boolean
     */
    protected $debug;

    /**
     * Constructor
     * 
     * @param boolean $debug on / off
     */
    public function __construct($debug = true)
    {
        $this->debug = $debug;
    }

    /**
     * Registers the exception handler.
     *
     * @param boolean $debug on / off
     *
     * @return ExceptionHandler The registered exception handler
     */
    public static function register($debug = true)
    {
        $handler = new static($debug);
        set_exception_handler(array($handler, 'handle'));
        return $handler;
    }

    /**
     * Sends a view response
     *
     * @param object $e An \Exception instance
     *
     * @return void
     */
    public function handle(\Exception $e)
    {
        global $c;

        $message = $e->getMessage();
        $file    = $e->getFile();
        $line    = $e->getLine();

        // Log for local environment
    
        if ($c['logger'] instanceof Logger) { 
            $c['logger']->emergency($message, array('file' => DebugOutput::getSecurePath($file), 'line' => $line));
            $c['logger']->__destruct(); // continue log writing
        }
        $c['exception']->showError($e);
    }
}

// END ExceptionHandler class

/* End of file ExceptionHandler.php */
/* Location: .Obullo/Error/ExceptionHandler.php */