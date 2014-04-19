<?php

namespace Obullo\Log\Task;

/**
 * Send unformatted log data using 
 * command line interface
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
    public $channel  = 'system';
    public $record   = array();
    public $handlers = array();
    public $recordUnformatted = array();

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
     * Constructor
     *
     * @param array $handlers log handler constants
     */
    public function __construct($handlers = array())
    {
        global $c;

        $this->config          = $c['config']['log'];
        $this->enabled         = $this->config['enabled'];
        $this->debug           = $this->config['debug'];
        $this->channel         = $this->config['channel'];
        $this->threshold_array = $this->config['threshold'];
        $this->handlers        = $handlers;
        $this->recordArray     = array();

        $this->priority_values = array_flip(self::$priorities);
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
     * Store log data into array
     * 
     * @param string  $level    log level
     * @param string  $message  log message
     * @param array   $context  context data
     * @param integer $priority message priority
     * 
     * @return void
     */
    public function log($level, $message = '', $context = array(), $priority = 0)
    {
        if (isset(self::$priorities[$level]) AND in_array(self::$priorities[$level], $this->threshold_array)) { // is Allowed level ?

            $this->recordUnformatted[] = array(
                'record' => array(
                    'channel'  => $this->channel,
                    'level'    => $level,
                    'message'  => $message,
                    'context'  => $context,
                ),
                'priority' => $priority,
            );
            $this->channel = 'system';          // reset channel to default
        }
    }

    /**
     * Send log data to command line
     * interface then we send it to log queue.
     * 
     * @return void
     */
    public function shutDown()
    {
        $shell = TASK .'log'. DS .'write.php '.base64_encode(serialize($this->recordUnformatted));
        echo shell_exec($shell);
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .Obullo/Log/Logger.php */