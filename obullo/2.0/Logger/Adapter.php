<?php

namespace Obullo\Logger;

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
Class Adapter
{
    /**
     * Log priority contants
     */
    const EMERGENCY = 0;
    const ALERT     = 1;
    const CRITICAL  = 2;
    const ERROR     = 3;
    const WARNING   = 4;
    const NOTICE    = 5;
    const INFO      = 6;
    const DEBUG     = 7;

    /**
     * Log priorities
     * @var array
     */
    protected static $priorities = array(
        'emergency' => self::EMERGENCY,
        'alert'     => self::ALERT,
        'critical'  => self::CRITICAL,
        'error'     => self::ERROR,
        'warning'   => self::WARNING,
        'notice'    => self::NOTICE,
        'info'      => self::INFO,
        'debug'     => self::DEBUG,
    );

    /**
     * Write all outputs to end of the page
     * @var boolean
     */
    public $output        = false;    // Write all outputs to end of the page
    /**
     * On / Off Logging
     * @var boolean
     */
    public $enabled       = true;
    /**
     * Batch On / Off
     * @var boolean
     */
    public $batch         = false;
    /**
     * Threshold array
     * @var array
     */
    public $threshold_array = array();
    /**
     * Sql Queries
     * @var boolean
     */
    public $queries       = false;    // log sql queries
    /**
     * Benchmark Data, Memory usage, Cpu info
     * @var boolean
     */
    public $benchmark     = false;    // log bechmark data, memory usage etc.
    /**
     * Output format for line based handlers
     * @var string
     */
    public $line          = '';       // line output format
    /**
     * Available  writers: file, email, mongo
     * @var array
     */
    public $writers = array();
    /**
     * Default channel
     * @var string
     */
    public $channel       = 'system'; // default log channel
    /**
     * Push Handler Object Storage
     * @var array
     */
    protected $handlers  = array();

    /**
    * Constructor
    */
    public function __construct()
    {
        global $c;

        $this->output          = $c['config']['log']['output'];
        $this->channel         = $c['config']['log']['channel'];
        $this->threshold_array = $c['config']['log']['threshold'];
        $this->queries         = $c['config']['log']['queries'];
        $this->benchmark       = $c['config']['log']['benchmark'];
        $this->line            = $c['config']['log']['line'];
        $this->writers         = $c['config']['log']['writers'];

        $this->processor = new PriorityQueue;  // Load SplPriorityQueue
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
     * Push to another handler
     * 
     * $logger->channel('security');
     * $logger->alert('Possible hacking attempt !', array('username' => $username));
     * $logger->push('email');  // send log data using email handler
     * $logger->push('mongo');  // send log data to mongo db
     * 
     * @param string $handler set channel handler
     * 
     * @return void
     */
    public function push($handler = 'email')
    {
        if ( ! isset($this->record['level']) OR ! $this->isAllowed($this->record['level'])) {  // check allowed
            return;
        }
        if ( ! isset($this->writers[$handler])) {

            $this->addWriter($handler);  // store writer
        }
    }

    /**
     * Add writer
     * 
     * @param string  $handlerName log handler
     * @param integer $priority    priority of handler
     *
     * @return void
     */
    public function addWriter($handlerName, $priority = 1)
    {
        $this->writers[$handlerName] = array('handler' => new $handlerName, 'priority' => $priority);
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
     * Send logs to Queue
     *
     * Using SplPriorityQueue Class we send
     * messages to Queue like below : 
     *
     * $processor = new SplPriorityQueue();
     * $processor->insert(array('file' => $record), $priority = 0); 
     * $processor->insert(array('mongo' => $record), $priority = 2); 
     * $processor->insert(array('email' => $record), $priority = 3); 
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
        foreach ($this->writers as $handler => $priority) {
            $record_formatted[$handler] = $handler->format($record_unformatted);  // create log data
            $this->processor->insert($record_formatted, $priority);
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
        if ($this->output) {
            $output = new DebugOutput($this);
            echo $output;
        }
        foreach ($this->writers as $handler) {
            $handler->write();
        }
    }

}

// END Logger class

/* End of file Adapter.php */
/* Location: .Obullo/Logger/Adapter.php */