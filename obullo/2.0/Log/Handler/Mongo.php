<?php

namespace Obullo\Log\Handler;

use Obullo\Log\HandlerInterface;
use Obullo\Log\PriorityQueue;

use Exception, MongoDate, MongoCollection, MongoClient;

/**
 * Mongo Handler Class
 * 
 * @category  Log
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class Mongo implements HandlerInterface
{
    public $logger;     // logger instance

    public $mongoClient;      // mongo database client
    public $mongoCollection;  // mongo database collection

    /**
     * Constructor
     *
     * @param object $logger logger class
     * @param array  $params file handler configuration
     */
    public function __construct($logger, $params = array())
    {
        $this->logger = $logger;             // logger object

        if ( ! isset($params['db.collection']) OR empty($params['db.collection'])) {
            throw new RunTimeException('The log handler "mongo" requires collection name please update your components.php');
        }
        $dsn    = explode('/', $params['db.dsn']);
        $dbName = end($dsn);
        
        // create mongo connection
        $this->mongoClient     = new MongoClient($params['db.dsn']);
        $this->mongoCollection = new MongoCollection($this->mongoClient->{$dbName}, $params['db.collection']);
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
        $format = $this->logger->getProperty('format');

        $record = array(
            'datetime' => new MongoDate(strtotime(date($format))),
            'channel'  => $this->logger->getProperty('channel'),
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
        /**
         * Using SplPriorityQueue Class we add log
         * messages to Queue like below : 
         *
         * $processor = new SplPriorityQueue();
         * $processor->insert(array('' => $record), $priority = 0); 
         */
        $processor = $this->logger->getProcessor(LOGGER_MONGO);

        $processor->setExtractFlags(PriorityQueue::EXTR_DATA); // Queue mode of extraction 

        if ($processor->count() > 0) {
            $processor->top();  // Go to Top

            $hasThreshold = $this->logger->hasThreshold(LOGGER_MONGO);
            $threshold    = $this->logger->getThreshold(LOGGER_MONGO);
      
            $data = array();
            $i = 0;
            while ($processor->valid()) {         // Prepare Lines
                $data[$i] = $processor->current(); 
                $processor->next();
                if ($hasThreshold AND $data[$i]['level'] != $threshold) { // threshold filter e.g. LOG_NOTICE
                    unset($data[$i]);   // remove not matched log records with selected filter.
                }
                $i++;
            }
            $this->mongoCollection->batchInsert($data);
            print_r($data);
        }
    }

    /**
     * Close connections
     */
    public function __destruct()
    {
        $this->mongoClient->close();
    }

}

// END Mongo class

/* End of file Mongo.php */
/* Location: .Obullo/Log/Handler/Mongo.php */