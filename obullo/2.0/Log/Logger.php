<?php

namespace Obullo\Log;

use RuntimeException, Closure;

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
 * @link      http://obullo.com/package/log
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
            throw new RuntimeException(sprintf('The log handler %s is not defined in your index.php file.', $handler));
        }
        $this->push[$handler] = 1; // allow push all log data for current push handler.

        if (isset($this->priority_values[$threshold])) {  // Just push for this priority
            $this->push[$handler] = $this->priority_values[$threshold];
        }
    }

    /**
     * Check push handler has threshold
     *
     * @param string $handler name
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
    public function getThreshold($handler)
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
     * Get splPriority object of valid handler
     * 
     * @param string $handler name
     * 
     * @return object of handler otherwise "false"
     */
    public function getProcessor($handler = 'file')
    {
        if ( ! isset($this->processor[$handler])) {
            return false;
        }
        return $this->processor[$handler];
    }

    /**
     * Send logs to Queue for each log handler.
     *
     * Using SplPriorityQueue Class we send messages to Queue like below : 
     *
     * $processor = new SplPriorityQueue;
     * $processor->insert($record, $priority = 0); 
     * 
     * @param array $unformatted unformated log data
     * 
     * @return void
     */
    public function sendToQueue($unformatted)
    {
        foreach ($this->writers as $handler => $val) {

            $this->handlers[$handler] = $val['handler'](); // run handler closure
            $priority = (empty($unformatted['priority'])) ? $val['priority'] : $unformatted['priority'];

            $this->addRecord($this->processor[$handler], $this->handlers[$handler], $unformatted, $priority);
        }
    }

    /**
     * Add record to processor queue
     *
     * @param object  $processor        SplPriority Object
     * @param object  $handler          handler object
     * @param array   $unformattedArray unformatted record
     * @param integer $priority         priority of log message
     * 
     * @return void
     */
    public function addRecord($processor, $handler, $unformattedArray, $priority)
    {
        foreach ($unformattedArray as $data) {
            $processor->insert($handler->format($data['record']), $priority);
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
            // if (isset($this->push[$handler]) OR key($this->handlers) == $handler) { // if handler is primary writer or push data available !
                
                $this->handlers[$handler]->write(); // do write process in task mode.
            //}
            unset($val);
        }
        $this->push = array(); // reset push data
    }

}

// END Logger class

/* End of file Logger.php */
/* Location: .Obullo/Log/Logger.php */