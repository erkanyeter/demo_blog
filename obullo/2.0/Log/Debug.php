<?php

namespace Obullo\Log;

/**
 * Logger Output Class
 * 
 * @category  Log
 * @package   Debug
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log/debug
 */
Class Debug
{
    public $logger;

    /**
     * Constructor
     * 
     * @param object $logger logger object
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    // ------------------------------------------------------------------------

    /**
     * Log html output
     * 
     * @return string echo the log output
     */
    public function printDebugger()
    {
        $isXmlHttp = ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;

        if ( ! $isXmlHttp AND ! defined('STDIN')) {      // disable html output for ajax and task requests

            // Queue
            $this->logger->processor->setExtractFlags(\Obullo\Log\PriorityQueue::EXTR_BOTH); // mode of extraction 

            // Go to Top
            $this->logger->processor->top(); 
            
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
            while ($this->logger->processor->valid()) {         // prepare Lines
                $output = $this->logger->processor->current(); 
                $lines.= str_replace('\n', '<br />', $output['data']['file']);  // output handler must be file for debugging
                $this->logger->processor->next();                                       
            }
            return str_replace(
                array('{output}','{title}'), 
                array($lines,'LOGGER DEBUG'), 
                '<div style="
                overflow-y:scroll;
                background:#fff;
                border-top: 2px solid #006857;
                color:#006857;
                padding:5px 5px;
                position: absolute;
                left: 0;
                line-height:15px;
                width: 100%;
                height: 100%;
                border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;font-size:12px;"><b>{title}</b>
                <pre style="
                white-space: pre-wrap;       /* css-3 */
                white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
                white-space: -pre-wrap;      /* Opera 4-6 */
                white-space: -o-pre-wrap;    /* Opera 7 */
                word-wrap: break-word;       /* Internet Explorer 5.5+ */
                background:#fff;
                border: none;
                color:#006857;
                border-radius:4px;
                -moz-border-radius:4px;
                -webkit-
                border-radius:4px;
                padding:5px 10px;
                font-size:12px;
                padding:0;
                margin-top:8px;">{output}</pre>
                </div><style>html{position:relative !important;} body{position:static;min-height:100% !important;height: 100% !important;};</style>'
            );
        }
        return null;
    }
}

// END Debug class
/* End of file Debug.php */

/* Location: .Obullo/Log/Debug.php */