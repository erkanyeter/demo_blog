<?php

namespace Obullo\Log;

use Closure, RunTimeException;

/**
 * Logger Adapter Class
 *
 * http://www.php.net/manual/en/class.splpriorityqueue.php
 * 
 * @category  Logger
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class Logger
{
    // @ Syslog constants
    // ----------------------------------------------
    // LOG_EMERG   system is unusable
    // LOG_ALERT   action must be taken immediately
    // LOG_CRIT    critical conditions
    // LOG_ERR error conditions
    // LOG_WARNING warning conditions
    // LOG_NOTICE  normal, but significant, condition
    // LOG_INFO    informational message
    // LOG_DEBUG   debug-level message

    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'error';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    /**
     * Log priorities
     * 
     * @var array
     */
    public static $priorities = array(
        self::EMERGENCY => LOG_EMERG,
        self::ALERT     => LOG_ALERT,
        self::CRITICAL  => LOG_CRIT,
        self::ERROR     => LOG_ERR,
        self::WARNING   => LOG_WARNING,
        self::NOTICE    => LOG_NOTICE,
        self::INFO      => LOG_INFO,
        self::DEBUG     => LOG_DEBUG,
    );
    /**
     * Map native PHP errors to priority
     *
     * @var array
     */
    public static $errorPriorities = array(
        E_NOTICE            => self::NOTICE,
        E_USER_NOTICE       => self::NOTICE,
        E_WARNING           => self::WARNING,
        E_CORE_WARNING      => self::WARNING,
        E_USER_WARNING      => self::WARNING,
        E_ERROR             => self::ERROR,
        E_USER_ERROR        => self::ERROR,
        E_CORE_ERROR        => self::ERROR,
        E_RECOVERABLE_ERROR => self::ERROR,
        E_STRICT            => self::DEBUG,
        E_DEPRECATED        => self::DEBUG,
        E_USER_DEPRECATED   => self::DEBUG,
    );

    /**
     * Registered error handler
     *
     * @var bool
     */
    protected static $registeredErrorHandler = false;

    /**
     * Registered exception handler
     *
     * @var bool
     */
    protected static $registeredExceptionHandler = false;

    /**
     * Priority values
     * @var array
     */
    public $priorityValues = array();
    /**
     * Config object
     * @var object
     */
    public $config;
    /**
     * Write all outputs to end of the page
     * @var boolean
     */
    public $debug = false;    // Write all outputs to end of the page
    /**
     * On / Off Logging
     * @var boolean
     */
    public $enabled = true;
    /**
     * Threshold array
     * @var array
     */
    public $thresholdArray = array();
    /**
     * Sql Queries
     * @var boolean
     */
    public $queries = false;    // log sql queries
    /**
     * Benchmark Data, Memory usage, Cpu info
     * @var boolean
     */
    public $benchmark = false;    // log bechmark data, memory usage etc.
    /**
     * Output format for line based handlers
     * @var string
     */
    public $line = '';       // line output format
    /**
     * Date format
     * @var 
     */
    public $format = 'Y-m-d H:i:s';
    /**
     * Available  writers: file, mongo, syslog
     * @var array
     */
    public $writers = array();
    /**
     * Default channel
     * @var string
     */
    public $channel = 'system'; // default log channel
    /**
     * Defined handlers in the container
     * @var array
     */
    protected $handlers  = array();
    /**
     * Log queue object
     * @var array
     */
    protected $processor = array();
    /**
     * Push data
     * @var array
     */
    protected $push = array();

    /**
    * Constructor
    */
    public function __construct()
    {
        global $c;

        $this->config          = $c['config']['log'];
        $this->enabled         = $this->config['enabled'];
        $this->debug           = $this->config['debug'];
        $this->channel         = $this->config['channel'];
        $this->thresholdArray  = $this->config['threshold'];
        $this->queries         = $this->config['queries'];
        $this->benchmark       = $this->config['benchmark'];
        $this->line            = $this->config['line'];
        $this->format          = $this->config['format'];

        $this->priorityValues = array_flip(self::$priorities);
        $this->processor = array();  //  new PriorityQueue; ( Php SplPriorityQueue Class )

        if (isset($options['exceptionhandler']) AND $this->config['exceptionhandler'] === true) {
            static::registerExceptionHandler($this);
        }

        if (isset($options['errorhandler']) AND $this->config['errorhandler'] === true) {
            static::registerErrorHandler($this);
        }
    }
    
    /**
     * Load the defined log handler
     * 
     * @param string $name defined log handler name
     * 
     * @return void
     */
    public function load($name)
    {
        if ( ! isset($this->handlers[$name])) {
            throw new RunTimeException(
                sprintf(
                    'The push handler %s is not defined in your components.php file.', 
                    $name
                )
            );
        }
        $val = $this->handlers[$name];
        $this->addWriter($name, $val['handler'], $val['priority']);
    }

    /**
     * Add Handler
     * 
     * @param string  $name     handler name
     * @param object  $handler  closure object
     * @param integer $priority log queue level
     *
     * @return void
     */
    public function addHandler($name, Closure $handler, $priority = 0)
    {
        $this->handlers[$name]  = array('handler' => $handler, 'priority' => $priority);
    }

    /**
     * Remove Handler
     * 
     * @param string $name handler name
     * 
     * @return void
     */
    public function removeHandler($name)
    {
        unset($this->handlers[$name]);
    }

    /**
     * Add writer
     * 
     * @param string  $name     handler key
     * @param object  $handler  handler object
     * @param integer $priority priority of handler
     *
     * @return void
     */
    public function addWriter($name, Closure $handler, $priority = 1)
    {
        $this->processor[$name] = new PriorityQueue;    // add processor
        $this->writers[$name]   = array('handler' => $handler(), 'priority' => $priority);
    }

    /**
     * Remove Writer
     * removers handler from processors and writers
     * 
     * @param string $name handler name
     * 
     * @return void
     */
    public function removeWriter($name)
    {
        unset($this->processor[$name]);
        unset($this->writers[$name]);
    }

    /**
     * Get property of the logger
     * 
     * @param string $key property of logger
     * 
     * @return mixed
     */
    public function getProperty($key)
    {
        return $this->{$key};
    }

    /**
     * Set property to the logger class
     * 
     * @param string $key property to logger
     * @param mixed  $val value of property
     * 
     * @return void
     */
    public function setProperty($key, $val)
    {
        $this->{$key} = $val;
    }

    /**
     * Change channel
     * 
     * @param string $channel add a channel
     * 
     * @return void
     */
    public function channel($channel)
    {
        $this->setProperty('channel', $channel);
    }

    /**
     * Emergency
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function emergency($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }

    /**
     * Alert
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function alert($message = '', $context = array(), $priority = null)
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }

    /**
     * Critical
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function critical($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }

    /**
     * Error
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function error($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }
    
    /**
     * Warning
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function warning($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }
    
    /**
     * Notice
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function notice($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }
    
    /**
     * Info
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function info($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }

    /**
     * Info
     * 
     * @param string  $message  log message
     * @param array   $context  data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function debug($message = '', $context = array(), $priority = null) 
    {
        $this->log(__FUNCTION__, $message, $context, $priority);
    }

    /**
     * Push to another handler
     * 
     * $logger->channel('security');
     * $logger->alert('Possible hacking attempt !', array('username' => $username));
     * $logger->push('email');  // send log data using email handler
     * $logger->push('mongo', LOG_ALERT);  // send log data to mongo db
     * 
     * @param string  $handler   set log handler
     * @param integer $threshold set threshold of log message
     * 
     * @return void
     */
    public function push($handler = null, $threshold = null)
    {
        if ( ! isset($this->handlers[$handler])) {
            throw new RunTimeException(
                sprintf(
                    'The push handler %s is not defined in your components.php file.', 
                    $handler
                )
            );
        }
        if ( ! isset($this->writers[$handler])) {
            throw new RunTimeException(
                sprintf(
                    'The push handler %s not available in log writers please first load it using below the command.
                    <pre>$this->logger->load(LOGGER_HANDLERNAME);</pre>', 
                    $handler
                )
            );
        }
        $this->push[$handler] = 1; // allow push all log data for current push handler.

        if (isset($this->priorityValues[$threshold])) {  // Just push for this priority
            $this->push[$handler] = $this->priorityValues[$threshold];
        }
    }

    /**
     * Check push handler has threshold
     *
     * @param string $handler handler name
     * 
     * @return boolean
     */
    public function hasThreshold($handler)
    {
        if (isset($this->push[$handler]) AND is_string($this->push[$handler])) {
            return true;
        }
        return false;
    }

    /**
     * Get push handler threshold
     * 
     * @param string $handler name
     * 
     * @return integer | boolean
     */
    public function getThreshold($handler = '')
    {
        return $this->push[$handler];
    }

    /**
     * Store log data into array
     * 
     * @param string  $level    log level
     * @param string  $message  log message
     * @param array   $context  context data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function log($level, $message, $context = array(), $priority = null)
    {
        $record_unformatted = array();

        if (isset(self::$priorities[$level]) AND in_array(self::$priorities[$level], $this->thresholdArray)) { // is Allowed level ?

            $record_unformatted['level']   = $level;
            $record_unformatted['message'] = $message;
            $record_unformatted['context'] = $context;

            $this->sendToQueue($record_unformatted, $priority); // Send to Job queue
            $this->channel($this->channel);          // reset channel to default
        }
    }
    
    /**
     * Get splPriority object of valid handler
     * 
     * @param string $handler name
     * 
     * @return object of handler
     */
    public function getProcessor($handler = LOGGER_FILE)
    {
        if ( ! isset($this->processor[$handler])) {
            throw new RunTimeException(
                sprintf(
                    'The log handler %s is not defined.', 
                    $handler
                )
            );
        }
        return $this->processor[$handler];
    }

    /**
     * Send logs to Queue for each log handler.
     *
     * $processor = new SplPriorityQueue;
     * $processor->insert($record, $priority = 0); 
     * 
     * @param array   $record_unformatted unformated log data
     * @param integer $message_priority   message priority
     * 
     * @return void
     */
    public function sendToQueue($record_unformatted, $message_priority = null)
    {
        foreach ($this->writers as $name => $val) {

            $formatted = $val['handler']->format($record_unformatted);
            $priority  = (empty($message_priority)) ? $val['priority'] : $message_priority;

            $this->processor[$name]->insert($formatted, $priority);
        }
    }

    /**
     * Register logging system as an error handler to log PHP errors
     *
     * @link http://www.php.net/manual/function.set-error-handler.php
     * @param  Logger $logger
     * @param  bool   $continueNativeHandler
     * @return mixed  Returns result of set_error_handler
     */
    public static function registerErrorHandler(Logger $logger, $continueNativeHandler = false)
    {
        // Only register once per instance
        if (static::$registeredErrorHandler) {
            return false;
        }
        $errorPriorities = static::$errorPriorities;

        $previous = set_error_handler(function ($level, $message, $file, $line)
            use ($logger, $errorPriorities, $continueNativeHandler)
        {
            $iniLevel = error_reporting();

            if ($iniLevel & $level) {
                if (isset($errorPriorities[$level])) {
                    $priority = $errorPriorities[$level];
                } else {
                    $priority = Logger::NOTICE;
                }
                $logger->log($priority, $message, array(
                    'errno'   => $level,
                    'file'    => $file,
                    'line'    => $line,
                ));
            }

            return !$continueNativeHandler;
        });

        static::$registeredErrorHandler = true;
        return $previous;
    }

    /**
     * Unregister error handler
     *
     */
    public static function unregisterErrorHandler()
    {
        restore_error_handler();
        static::$registeredErrorHandler = false;
    }

    /**
     * Register logging system as an exception handler to log PHP exceptions
     *
     * @link http://www.php.net/manual/en/function.set-exception-handler.php
     * @param Logger $logger
     * @return bool
     * @throws Exception\InvalidArgumentException if logger is null
     */
    public static function registerExceptionHandler(Logger $logger)
    {
        // Only register once per instance
        if (static::$registeredExceptionHandler) {
            return false;
        }
        $errorPriorities = static::$errorPriorities;
        
        set_exception_handler(function ($exception) use ($logger, $errorPriorities) {
            $logMessages = array();

            // @see http://www.php.net/manual/tr/errorexception.getseverity.php
            do {
                $priority = Logger::ERROR;
                if ($exception instanceof ErrorException AND isset($errorPriorities[$exception->getSeverity()])) {
                    $priority = $errorPriorities[$exception->getSeverity()];
                }
                $extra = array(
                    'file'  => $exception->getFile(),
                    'line'  => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                );
                if (isset($exception->xdebug_message)) {
                    $extra['xdebug'] = $exception->xdebug_message;
                }
                $logMessages[] = array(
                    'priority' => $priority,
                    'message'  => $exception->getMessage(),
                    'extra'    => $extra,
                );
                $exception = $exception->getPrevious();
            } while ($exception);

            foreach (array_reverse($logMessages) as $logMessage) {
                $logger->log($logMessage['priority'], $logMessage['message'], $logMessage['extra']);
            }
        });
        static::$registeredExceptionHandler = true;
        return true;
    }

    /**
     * Unregister exception handler
     */
    public static function unregisterExceptionHandler()
    {
        restore_exception_handler();
        static::$registeredExceptionHandler = false;
    }

    /**
     * End of the logs and beginning of 
     * the handler process.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->enabled == false) {  // check logger is disabled.
            return;
        }
        if ($this->debug) {             // debug log data if debug
            $debug = new Debug($this);
            echo $debug->printDebugger();
            return;
        } 
        foreach ($this->writers as $val) {    // write log data
            $val['handler']->write();
        }
        $this->push = array(); // reset push data
    }

}

// END Logger class

/* End of file Logger.php */
/* Location: .Obullo/Log/Logger.php */