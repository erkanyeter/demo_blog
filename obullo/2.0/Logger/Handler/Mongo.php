<?php

namespace Obullo\Logger\Handler;

use Obullo\Logger\Logger;
use Obullo\Logger\HandlerInterface;
use Obullo\Logger\PriorityQueue;

use Exception, MongoDate, MongoCollection, MongoClient;

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
Class Mongo
{
    public $logger;     // logger instance
    public $mongo;      // mongo database instance

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
            throw new Exception(
                'The log handler "mongo" requires collection name: <pre>\$logger->addHandler(
        \'mongo\', 
        function () use ($logger) { 
            return new Obullo\Logger\Handler\Mongo($logger, array(\'collection\' => \'name\'));
        },
        1
    );</pre>'
            );
        }
        // create mongo connection
        
        $dsn    = explode('/', $params['db.dsn']);
        $dbName = end($dsn);

        $mongoClient = new MongoClient($params['db.dsn']);
        $this->mongo = new MongoCollection($mongoClient->{$dbName}, $params['db.collection']);
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
        $processor = $this->logger->getProcessorInstance('mongo');

        // Queue
        $processor->setExtractFlags(PriorityQueue::EXTR_BOTH); // mode of extraction 

        if ($processor->count() > 0) {
            $processor->top();  // Go to Top
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
     
        $lines = '';
        while ($processor->valid()) {         // Prepare Lines
            $record = $processor->current(); 
            $processor->next(); 
            // var_dump($record['data']);
        }
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