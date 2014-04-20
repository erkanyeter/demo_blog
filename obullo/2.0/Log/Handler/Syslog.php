<?php

namespace Obullo\Log\Handler;

use Obullo\Log\Logger;
use Obullo\Log\HandlerInterface;
use Obullo\Log\PriorityQueue;

use Exception;

/**
 * Syslog Handler Class
 * 
 * @category  Logger
 * @package   File
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class Syslog implements HandlerInterface
{
    public $logger;     // logger instance

    public $facility = LOG_USER;              // facility used by this syslog instance
    public $name     = 'Log/Handler/Syslog';  // syslog application name

    /**
     * Constructor
     *
     * @param object $logger logger class
     * @param array  $params init params
     */
    public function __construct($logger, $params = array())
    {
        $this->logger = $logger;   // logger object

        if (isset($params['app.facility'])) {
            $this->facility = $params['app.facility'];
        }
        if (isset($params['app.name'])) {
            $this->name = $params['app.name'];
        }
        openlog('asdsd', LOG_PID, $this->facility);
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
            'datetime' => date($format),
            'channel'  => $this->logger->getProperty('channel'),
            'level'    => $unformatted_record['level'],
            'message'  => $unformatted_record['message'],
            'context'  => null,
            'extra'    => null,
        );
        if (count($unformatted_record['context']) > 0) {
            $record['context'] = preg_replace('/[\r\n]+/', '', var_export($unformatted_record['context'], true));
        }
        if (isset($unformatted_record['context']['extra']) AND count($unformatted_record['context']['extra']) > 0) {
            $record['extra'] = var_export($unformatted_record['context']['extra'], true);
        }
        return $record; // formatted record
    }

    /**
     * Format the line which is defined in app/config/$env/config.php
     * This feature just for line based loggers.
     * 
     * 'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',
     * 
     * @param array $record array of log data
     * 
     * @return string returns to formated string
     */
    public function lineFormat($record)
    {
        if ( ! is_array($record)) {
            return;
        }
        return str_replace(
            array(
            '%datetime%',
            '%channel%',
            '%level%',
            '%message%',
            '%context%',
            '%extra%',
            ), array(
            $record['datetime'],
            $record['channel'],
            $record['level'],
            $record['message'],
            (empty($record['context'])) ? '' : $record['context'],
            (empty($record['extra'])) ? '' : $record['extra'],
            $record['extra'],
            ),
            str_replace('\n', "\n", $this->logger->getProperty('line'))
        );
    }

    /**
     * Write processor output
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
         * $processor->insert($record, $priority = 0); 
         */
        $processor = $this->logger->getProcessor(LOGGER_SYSLOG); // Get syslog queue

        $processor->setExtractFlags(PriorityQueue::EXTR_DATA);   // Queue mode of extraction 

        if ($processor->count() > 0) {
            $processor->top();  // Go to Top

            $hasThreshold = $this->logger->hasThreshold(LOGGER_SYSLOG);
            $threshold    = $this->logger->getThreshold(LOGGER_SYSLOG);
      
            $i = 0;
            $data = array();
            while ($processor->valid()) {         // Prepare Lines
                $data[$i] = $processor->current(); 
                $processor->next();
                if ($hasThreshold AND $data[$i]['level'] != $threshold) { // threshold filter e.g. LOG_NOTICE
                    unset($data[$i]);   // remove not matched log records with selected filter.
                }
                syslog(Logger::$priorities[$data[$i]['level']], $this->lineFormat($data[$i]));
                $i++;
            }
        }
    }

    /**
     * Close connections
     */
    public function __destruct()
    {
        closelog();
    }

}

// END Syslog class

/* End of file Syslog.php */
/* Location: .Obullo/Log/Handler/Syslog.php */