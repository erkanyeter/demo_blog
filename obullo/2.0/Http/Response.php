<?php

namespace Obullo\Http;

/**
 * Response Class.
 * 
 * Set Http Response, Set Output Errors
 * Get Output
 * 
 * @category  Http
 * @package   Response
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/http/response
 */
Class Response
{
    public $logger;
    public $final_output;
    public $error;
    public $headers = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;
        $this->logger = $c['logger'];
        
        $this->logger->debug('Response Class Initialized');
    }

    /**
     * Append Output
     *
     * Appends data onto the output string
     *
     * @param string $output output
     * 
     * @return void
     */
    public function appendOutput($output)
    {
        if ($this->final_output == '') {
            $this->final_output = $output;
        } else {
            $this->final_output.= $output;
        }
    }

    /**
     * Display Output
     *
     * This function sends the finalized output data to the browser along
     * with any server headers and profile data.  It also stops the
     * benchmark timer so the page rendering speed and memory usage can be shown.
     *
     * @param string $output output
     * 
     * @return void
     */
    public function sendOutput($output = '')
    {
        global $c;

        if ($output == '') {                       // Set the output data
            $output = & $this->final_output;
        }
        if ($c['config']['output']['compress']  // Is compression requested ?
            AND extension_loaded('zlib') 
            AND isset($_SERVER['HTTP_ACCEPT_ENCODING']) 
            AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false
        ) {
            ob_start('ob_gzhandler');
        }
        if (count($this->headers) > 0 AND ! headers_sent()) {          // Are there any server headers to send ?
            foreach ($this->headers as $header) {
                header($header[0], $header[1]);
            }            
        }
        if (method_exists($c['app'], '_output')) {    // Does the controller contain a function named _output()?
            $c['app']->_output($output);              // If so send the output there.  Otherwise, echo it.
            return;
        } 
        echo $output;  // Send it to the browser!
    }

    // ------------------------------------------------------------------------
    
    /**
    * Get Output
    *
    * Returns the current output string
    *
    * @access    public
    * @return    string
    */    
    public function getOutput()
    {
        return $this->final_output;
    }

    // --------------------------------------------------------------------
    
    /**
    * Set HTTP Status Header
    * 
    * @param int $code the status code
    * 
    * @return   void
    */    
    public function setHttpResponse($code = 200)
    {
        http_response_code($code);  // Php >= 5.4.0 
    }
        
    // --------------------------------------------------------------------

    /**
    * Set Header
    *
    * Lets you set a server header which will be outputted with the final display.
    *
    * Note:  If a file is cached, headers will not be sent.  We need to figure out
    * how to permit header data to be saved with the cache data...
    *
    * @param string  $header  header
    * @param boolean $replace replace override header
    * 
    * @return void
    */    
    public function setHeader($header, $replace = true)
    {
        // If zlib.output_compression is enabled it will compress the output,
        // but it will not modify the content-length header to compensate for
        // the reduction, causing the browser to hang waiting for more data.
        // We'll just skip content-length in those cases.

        if (@ini_get('zlib.output_compression') AND strncasecmp($header, 'content-length', 14) == 0) {
            return;
        }
        $this->headers[] = array($header, $replace);
    }

    /**
    * Set Output
    *
    * Sets the output string
    *
    * @param string $output output
    * 
    * @return void
    */    
    public function setOutput($output)
    {
        $this->final_output = $output;
    }

    /**
    * 404 Page Not Found Handler
    *
    * @param string  $page     page name
    * @param boolean $http_404 http 404 or hvc 404
    * 
    * @return string
    */
    public function show404($page = '', $http_404 = true)
    {
        $message = '404 Page Not Found --> '.$page;
        $this->logger->error($message);

        if ($http_404 == false) {
            $this->error = $message;
            return $message;
        }
        echo $this->showHttpError('404 Page Not Found', $page, '404', 404);
        exit();
    }

    /**
    * Manually Set General Http Errors
    *
    * @param string $message    message
    * @param int    $statusCode status
    * @param int    $heading    heading text
    *
    * @return void
    */
    public function showError($message, $statusCode = 500, $heading = 'An Error Was Encountered')
    {
        global $c;
        $this->logger->error($heading.' --> '.$message, false);

        if ($statusCode === false) {
            $this->error = $message;
            return $message;
        }
        header('Content-type: text/html; charset='.$c['config']['locale']['charset']); // Some times we use utf8 chars in errors.
        echo $this->showHttpError($heading, $message, 'general', $statusCode);
        exit();
    }

    /**
    * General Http Errors
    *
    * @param string $heading    the heading
    * @param string $message    the message
    * @param string $template   the template name
    * @param int    $statusCode header status code
    * 
    * @return   string
    */
    public function showHttpError($heading, $message, $template = 'general', $statusCode = 500)
    {
        $this->setHttpResponse($statusCode);
        $message = implode('<br />', ( ! is_array($message)) ? array($message) : $message);

        if (defined('STDIN')) { // If Command Line Request
            return '['.$heading.']: The url ' .$message. ' you requested was not found.'."\n";
        }
        ob_start();
        include APP .'errors'. DS .$template. EXT;
        $buffer = ob_get_clean();
        return $buffer;
    }

    /**
     * Get last response error
     * 
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Clear variables.
     * 
     * @return void
     */
    public function clear()
    {
        $this->error = null;
    }

}

// END Response.php File
/* End of file Response.php

/* Location: .Obullo/Http/Response.php */