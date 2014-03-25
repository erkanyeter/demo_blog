<?php

namespace Obullo\Logger;

/**
 * Logger Output Class
 * 
 * @category  Logger
 * @package   Logger_Output
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class Output
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
    public function __toString()
    {
        $isXmlHttp = ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;
        $output    = '';
        if ( ! $isXmlHttp AND ! defined('STDIN')) {      // disable html output for ajax and task requests
            foreach ($this->logger->getRecordArray() as $value) {
                $output.= str_replace('\n', '<br />', $value);
            }
            
            $template ='<div style="
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
</div><style>html{position:relative !important;} body{position:static;min-height:100% !important;height: 100% !important;};</style>';

            return str_replace(array('{output}','{title}'), array($output,'LOGGER OUTPUT'), $template);
        }
        return $output;
    }
}

// END Output class
/* End of file Output.php */

/* Location: .Obullo/Logger/Output.php */