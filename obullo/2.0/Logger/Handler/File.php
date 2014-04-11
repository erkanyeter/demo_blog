<?php

namespace Obullo\Logger\Handler;

/**
 * File Handler Class
 * 
 * @category  Logger
 * @package   File
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class File
{
    public $logger;     // logger object
    public $path;       // log path for file driver
    public $config;     // logger config

    /**
     * Config Constructor
     *
     * @param object $logger class
     */
    public function __construct($logger)
    {
        global $c;

        $this->logger    = $logger;             // logger object
        $this->processor = $logger->processor;  // processor object
        
        $this->path = self::replacePath($c['config']['logger']['path']['app']);   // Application request path

        if (defined('STDIN') AND defined('TASK')) {     // Task request
            $this->path = self::replacePath($c['config']['logger']['path']['cli']);
        } elseif (defined('STDIN')) {                   // Cli request
            if (isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') {   //  Do not keep clear command logs.
                $this->setProperty('enabled', false);
            }
            $this->path = self::replacePath($c['config']['logger']['path']['task']);
        }
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
        return $this->lineFormat($record); // formatted record
    }

    /**
     * Format the line which is defined in app/config/$env/config.php
     * This feature just for line based loggers.
     * 
     * 'log_line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',
     * 
     * @param array $record array of log data
     * 
     * @return string returns to formated string
     */
    public function lineFormat($record)
    {
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
     * Write processor output to file
     * 
     * @return boolean
     */
    public function write()
    {
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
        $lines = '';
        while ($this->processor->valid()) {         // Prepare Lines
            $output = $this->processor->current(); 
            $lines.= $output['data']['file'];       // Get file handler data from queue
            $this->processor->next(); 
        } 
        if ( ! $fop = fopen($this->path, 'ab')) {
            return false;
        }
        flock($fop, LOCK_EX);
        fwrite($fop, $lines);
        flock($fop, LOCK_UN);
        fclose($fop);
        if ( ! defined('STDIN')) {   // Do not do ( chmod ) in CLI mode, it cause write errors
            chmod($this->path, 0666);
        }
        return true;
    }

    /**
     * If you keep logs in data/logs folder, we replace /data
     * keyword with directory seperator.
     * 
     * @param string $path log path
     * 
     * @return string current path
     */
    public static function replacePath($path)
    {
        if (strpos($path, 'data') === 0) {
            $path = str_replace('/', DS, trim($path, '/'));
            $path = DATA .substr($path, 5);
        }
        return $path;
    }
}

// END File class

/* End of file File.php */
/* Location: .Obullo/Logger/Handler/File.php */