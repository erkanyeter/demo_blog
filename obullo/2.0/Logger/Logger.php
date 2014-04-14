<?php

namespace Obullo\Logger;
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
    /**
     * Log priorities
     * @var array
     */
    protected static $priorities = array(
        'emergency' => LOGGER_EMERGENCY,
        'alert'     => LOGGER_ALERT,
        'critical'  => LOGGER_CRITICAL,
        'error'     => LOGGER_ERROR,
        'warning'   => LOGGER_WARNING,
        'notice'    => LOGGER_NOTICE,
        'info'      => LOGGER_INFO,
        'debug'     => LOGGER_DEBUG,
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
     * Available  writers: file, email, mongo
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

        $this->config          = $c['config']['logger'];
        $this->debug           = $this->config['debug'];
        $this->channel         = $this->config['channel'];
        $this->threshold_array = $this->config['threshold'];
        $this->queries         = $this->config['queries'];
        $this->benchmark       = $this->config['benchmark'];
        $this->line            = $this->config['line'];
        $this->priority_values = array_values(self::$priorities);

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
        $this->addWriter($name, $handler(), $priority);
        $this->handlers[$name] = $name;
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
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function emergency($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * Alert
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function alert($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * Critical
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function critical($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * Error
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function error($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }
    
    /**
     * Warning
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function warning($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }
    
    /**
     * Notice
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function notice($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }
    
    /**
     * Info
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function info($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * Info
     * 
     * @param string $message log message
     * @param array  $context data
     * 
     * @return void
     */
    public function debug($message = '', $context = array()) 
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    /**
     * Push to another handler
     * 
     * $logger->channel('security');
     * $logger->alert('Possible hacking attempt !', array('username' => $username));
     * $logger->push('email');  // send log data using email handler
     * $logger->push('mongo');  // send log data to mongo db
     * 
     * @param string  $name     set log handler
     * @param integer $priority set level of priority
     * 
     * @return void
     */
    public function push($name = 'email', $priority = null)
    {   
        if ( ! isset($this->handlers[$name])) {
            throw new Exception('The handler '.$name.' not defined in your application index.php');
        }
        if (in_array($priority, $this->priority_values)) {  // Just push for this priority
            $this->push[$name] = $priority;
        }
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
    public function addWriter($name, $handler, $priority = 1)
    {
        if ( ! isset($this->writers[$name])) {
            $this->processor[$name] = new PriorityQueue;    // add processor
            $this->writers[$name]   = array('handler' => $handler, 'priority' => $priority);
        }
    }

    /**
     * Store log data into array
     * 
     * @param string $level   log level
     * @param string $message log message
     * @param array  $context context data
     * 
     * @return void
     */
    public function log($level, $message, $context = array())
    {
        $record_unformatted = array();

        if ($this->isAllowed($level)) {

            $record_unformatted['level']   = $level;
            $record_unformatted['message'] = $message;
            $record_unformatted['context'] = $context;

            $this->sendToQueue($record_unformatted); // Send to Job queue
            $this->channel($this->channel);          // reset channel to default
        }
    }
    
    /**
     * Get processor object of valid handler
     * 
     * @param string $handler name
     * 
     * @return object of handler
     */
    public function getProcessorInstance($handler = 'file')
    {
        return $this->processor[$handler];
    }

    /**
     * Send logs to Queue
     *
     * Using SplPriorityQueue Class we send
     * messages to Queue like below : 
     *
     * $processor = new SplPriorityQueue;
     * $processor->insert($record, $priority = 0); 
     * 
     * @param array $record_unformatted unformated log data
     * 
     * @return void
     */
    public function sendToQueue($record_unformatted)
    {
        /**
         * Insert record to queue for each log handlers
         */
        foreach ($this->writers as $name => $val) {
            $record_formatted = $val['handler']->format($record_unformatted);  // create log data
            $this->processor[$name]->insert($record_formatted, $val['priority']);
        }
    }

    /**
     * Is it allowed level ?
     *
     * @param string $level current level ( debug, alert .. )
     * 
     * @return boolean 
     */
    public function isAllowed($level)
    {
        if (isset(self::$priorities[$level]) AND in_array(self::$priorities[$level], $this->threshold_array)) {
            return true;
        }
        return false;
    }

    /**
     * End of the logs and beginning of 
     * the handler process.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->enabled == false) {
            return;
        }
        if ($this->debug) {                    // debug log data if debu
            $output = new DebugOutput($this);
            echo $output->printDebugger();
        }
        foreach ($this->writers as $array) {    // write log data
            $array['handler']->write();
        }
    }

}

// END Logger class

/* End of file Adapter.php */
/* Location: .Obullo/Logger/Adapter.php */