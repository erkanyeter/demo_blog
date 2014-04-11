<?php

namespace Obullo\Logger\Handler;

use Obullo\Logger\Logger;
use Obullo\Logger\HandlerInterface;
use Exception;
use MongoDate;

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
Class Mongo implements HandlerInterface
{
    public $logger;     // logger instance
    public $mongo;      // mongo database instance
    public $config;     // logger file configuration

    /**
     * Constructor
     *
     * @param object $logger logger class
     * @param array  $params file handler configuration
     */
    public function __construct($logger, $params = array())
    {        
        global $c;

        $this->logger    = $logger;             // logger object
        $this->processor = $logger->processor;  // processor object
        
        if ( ! isset($params['collection'])) {
            throw new Exception(
                'The log handler "mongo" requires collection parameter: <pre>\$logger->addHandler(
        \'mongo\', 
        function () use ($logger) { 
            return new Obullo\Logger\Handler\Mongo($logger, array(\'collection\' => \'name\'));
        },
        1
    );</pre>'
            );
        }
        $this->mongo      = $c['mongo'];    // mongo instance
        $this->collection = $params['collection'];
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
            'datetime' => new MongoDate,
            'channel'  => $this->getProperty('channel'),
            'level'    => $unformatted_record['level'],
            'message'  => $unformatted_record['message'],
            'context'  => null,
            'extra'    => null,
        );
        if (count($unformatted_record['context']) > 0) {
            $record['context'] = json_encode($unformatted_record['context'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 
        }
        if (isset($unformatted_record['context']['extra']) AND count($unformatted_record['context']['extra']) > 0) {
            $record['extra'] = json_encode($unformatted_record['context']['extra'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 
        }
        return $record;  // formatted record
    }

    /**
     * Write processor output to file
     * 
     * @return boolean
     */
    public function write()
    {
        global $c;

        // Queue
        $this->processor->setExtractFlags(\Obullo\Logger\PriorityQueue::EXTR_BOTH); // mode of extraction 

        if ($this->processor->count() > 0) {
            $this->processor->top();  // Go to Top
        }

        /**
         * Using SplPriorityQueue Class we add log
         * messages to Queue like below : 
         *
         * $processor = new SplPriorityQueue();
         * $processor->insert(array('file' => $record), $priority = 0); 
         * $processor->insert(array('mongo' => $record), $priority = 2); 
         * $processor->insert(array('email' => $record), $priority = 3); 
         */
        
        // $collection = new MongoCollection($c['mongo'], 'users');
        // $cursor = $collection->find(array('username' => 'guest_3941574'));

        while ($this->processor->valid()) {         // Prepare Lines
            $output = $this->processor->current(); 
            $lines.= $output['data']['mongo'];       // Get mongo handler data from queue
            $this->processor->next(); 
        } 

        echo $lines;

        // INSERT TO MONGO TABLE

        // foreach ($cursor as $doc) {
        //     var_dump($doc);
        // }

        return true;
    }

}

// END Mongo class

/* End of file Mongo.php */
/* Location: .Obullo/Logger/Handler/Mongo.php */