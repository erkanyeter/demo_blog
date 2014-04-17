<?php

namespace Obullo\Log;

use Closure, Exception;

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

    /**
     * Log priorities
     * @var array
     */
    public static $priorities = array(
        'emergency' => LOG_EMERG,
        'alert'     => LOG_ALERT,
        'critical'  => LOG_CRIT,
        'error'     => LOG_ERR,
        'warning'   => LOG_WARNING,
        'notice'    => LOG_NOTICE,
        'info'      => LOG_INFO,
        'debug'     => LOG_DEBUG,
    );
    /**
     * Priority values
     * @var array
     */
    public $priority_values = array();
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
    public $threshold_array = array();
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
     * [$date_format description]
     * @var 
     */
    public $date_format;
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

    public $task;

    /**
    * Constructor
    */
    public function __construct()
    {
        global $c;

        $this->task = $c['cli/task'];

        var_dump($this->task);

        // echo $this->task->run('logger/index/1', true); exit;

        $this->config          = $c['config']['log'];
        $this->enabled         = $this->config['enabled'];
        $this->debug           = $this->config['debug'];
        $this->channel         = $this->config['channel'];
        $this->threshold_array = $this->config['threshold'];
        $this->queries         = $this->config['queries'];
        $this->benchmark       = $this->config['benchmark'];
        $this->line            = $this->config['line'];
        $this->date_format     = $this->config['date_format'];

        $this->priority_values = array_flip(self::$priorities);
        $this->processor = array();  //  new PriorityQueue; ( Php SplPriorityQueue Class )
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
        $this->addWriter($name, $handler, $priority);
        $this->handlers[$name] = $handler;
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
        $this->removeWriter($name);
        unset($this->handlers[$name]);
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
    public function push($handler = 'mongo', $threshold = null)
    {   
        if ( ! isset($this->handlers[$handler])) {
            throw new Exception(sprintf('The log handler %s is not defined in your index.php file.', $handler));
        }

        $this->push[$handler] = 1; // allow push all log data for current push handler.

        if (isset($this->priority_values[$threshold])) {  // Just push for this priority
            $this->push[$handler] = $this->priority_values[$threshold];
        }
    }

    /**
     * Check push handler has threshold
     * 
     * @return boolean [description]
     */
    public function hasThreshold()
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
        if ( ! isset($this->writers[$name])) {
            $this->processor[$name] = new PriorityQueue;    // add processor
            $this->writers[$name]   = array('handler' => $handler, 'priority' => $priority);
        }
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
        unset($this->writers[$name]);
        unset($this->processor[$name]);
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

        if (isset(self::$priorities[$level]) AND in_array(self::$priorities[$level], $this->threshold_array)) { // is Allowed level ?

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
    public function getProcessor($handler = 'file')
    {
        if ( ! isset($this->processor[$handler])) {
            throw new Exception(sprintf('The log handler %s is not defined.', $handler));
        }
        return $this->processor[$handler];
    }

    /**
     * Send logs to Queue for each log handler.
     *
     * Using SplPriorityQueue Class we send
     * messages to Queue like below : 
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

            $this->handlers[$name] = $val['handler'](); // call handler class closure

            $record_formatted = $this->handlers[$name]->format($record_unformatted);  // create log data
            $priority = (empty($message_priority)) ? $val['priority'] : $message_priority;

            $this->processor[$name]->insert($record_formatted, $priority);
        }
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
        foreach ($this->writers as $handler => $val) {    // write log data
            if (isset($this->push[$handler]) OR key($this->handlers) == $handler) { // if handler is primary writer or push data available !
                
                $this->handlers[$handler]->write(); // do write process in task mode.
            }
            unset($val);
        }
        $this->push = array(); // reset push data
    }

}

// END Logger class

/* End of file Logger.php */
/* Location: .Obullo/Log/Logger.php */