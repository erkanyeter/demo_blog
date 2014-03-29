<?php

namespace Obullo\Logger\Handler;

use Obullo\Logger\Adapter;
use Obullo\Logger\HandlerInterface;

/**
 * Mongo Handler Class
 * 
 * @category  Logger
 * @package   File
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class Mongo extends Adapter implements HandlerInterface
{
    public $path;       // current log path for file driver
    public $config;     // logger file configuration

    /**
     * Constructor
     */
    public function __construct()
    {        
        global $c;

        $this->mongo = $c['mongo']->database;

        $this->collection = $c['config']['log']['database']['table'];

        //  $this->db = 

        // connect to mongo database.

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
    * Format log records and build lines
    *
    * @param array $unformatted_record log data
    * 
    * @return array formatted record
    */
    public function format($unformatted_record)
    {
        $record = array(
            'datetime' => date('Y-m-d H:i:s'),
            'channel'  => $this->getProperty('channel'),
            'level'    => $unformatted_record['level'],
            'message'  => $unformatted_record['message'],
            'context'  => $unformatted_record['context'],
            'extra'    => (isset($unformatted_record['context']['extra'])) ? $unformatted_record['context']['extra'] : '',
        );

        if (sizeof($record['context']) > 0) {     // context
            $record['context'] = json_encode($record['context'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 
        }
        $record['datetime'] = new MongoDate();   // datetime
        return $record;  // formatted record
    }

    /**
     * Write processor output to file
     * 
     * @return boolean
     */
    public function write()
    {
        // Queue
        $this->processor->setExtractFlags(PriorityQueue::EXTR_BOTH); // mode of extraction 

        // Go to Top
        $this->processor->top(); 

        /**
         * Using SplPriorityQueue Class we add log
         * messages to Queue like below : 
         *
         * $processor = new SplPriorityQueue();
         * $processor->insert(array('file' => $record), $priority = 0); 
         * $processor->insert(array('mongo' => $record), $priority = 2); 
         * $processor->insert(array('email' => $record), $priority = 3); 
         */
        $lines = '';
        while ($this->processor->valid()) {         // Prepare Lines
            $output = $this->processor->current(); 
            $lines.= $output['data']['file'];       // Get file handler data from queue
            $this->processor->next(); 
        } 

        // INSERT TO MONGO TABLE

        return true;
    }

}

// END Mongo class

/* End of file Mongo.php */
/* Location: .Obullo/Logger/Handler/Mongo.php */