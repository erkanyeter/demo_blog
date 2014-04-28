<?php

namespace Obullo\Error;

use Obullo\Logger\Logger;

/**
 * Exception Class
 * 
 * @category  Error
 * @package   Exception
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/error
 */
Class Exception
{
    /**
     * Display the exception view
     * 
     * @param object  $e          exception object
     * @param boolean $fatalError whether to fatal error
     * 
     * @return string view
     */
    public function showError($e, $fatalError = false)
    {
        global $c;

        if ($fatalError == false) unset($fatalError);

        if (is_object($c)) { 

            if (defined('STDIN')) {      // Cli
                echo 'Exception Error: ' .$e->getMessage().' '. DebugOutput::getSecurePath($e->getFile()).' '. $e->getLine(). "\n";
                return;
            }
            if ($this->isXmlHttp()) {    // Ajax
                echo strip_tags('Exception Error: ' .$e->getMessage().' '. DebugOutput::getSecurePath($e->getFile()).' '. $e->getLine(). "\n");
                return;
            }
            $lastQuery = '';             // Show the last sql query
            if (isset($c['app']->db)) {
                $lastQuery = '';
                if (method_exists($c['app']->db, 'lastQuery')) {
                    $lastQuery = $c['app']->db->lastQuery();
                }
            }
            ob_start();
            include OBULLO . 'Error' . DS . 'ExceptionView' . EXT;  // load view
            echo ob_get_clean();
        }
    }

    /**
     * Check request isXmlHttp ( ajax )
     * 
     * @return boolean
     */
    public function isXmlHttp()
    {
        if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

}

// END Exception class

/* End of file Exception.php */
/* Location: .Obullo/Error/Exception.php */