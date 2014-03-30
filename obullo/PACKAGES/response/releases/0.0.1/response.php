<?php

/**
 * Response Class
 * 
 * Set Http Response, Set Output Errors
 * Get Final Output
 *
 * @package       packages
 * @subpackage    response
 * @category      output
 * @link
 */
Class Response
{
    public $logger;
    public $final_output;
    public $headers = array();

    // --------------------------------------------------------------------

    public function __construct()
    {
        global $c;
        $this->logger = $c['logger'];
        $this->logger->debug('Response Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Append Output
     *
     * Appends data onto the output string
     *
     * @access    public
     * @param     string
     * @return    void
     */
    public function appendOutput($output)
    {
        if ($this->final_output == '') {
            $this->final_output = $output;
        } else {
            $this->final_output.= $output;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Display Output
     *
     * All "view" data is automatically put into this variable by the controller class:
     *
     * $this->final_output
     *
     * This function sends the finalized output data to the browser along
     * with any server headers and profile data.  It also stops the
     * benchmark timer so the page rendering speed and memory usage can be shown.
     *
     * @access    public
     * @return    mixed
     */
    public function _sendOutput($output = '')
    {
        global $c;

        if ($output == '') {  // Set the output data
            $output = & $this->final_output;
        }

        if ($c['config']['output']['compress']) {          // Is compression requested ?
            if (extension_loaded('zlib')) {
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                    ob_start('ob_gzhandler');
                }
            }
        }
        if (count($this->headers) > 0) {          // Are there any server headers to send ?
            if ( ! headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header[0], $header[1]);
                }
            }
        }
        if (method_exists($c['app'], '_response')) {    // Does the controller contain a function named _output()?
            $c['app']->_response($output);          // If so send the output there.  Otherwise, echo it.
        } else {
            echo $output;  // Send it to the browser!
        }
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
    * @return void
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
    * @param string $header  header
    * @param bool   $replace override current header
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

    // --------------------------------------------------------------------
    
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

    //----------------------------------------------------------------------- 

    /**
    * 404 Page Not Found Handler
    *
    * @param string $page name
    * 
    * @return string
    */
    public function show404($page = '')
    {
        $this->logger->error('404 Page Not Found --> '.$page);
        echo $this->showHttpError('404 Page Not Found', $page, '404', 404);
        exit();
    }

    // -------------------------------------------------------------------- 

    /**
    * Manually Set General Http Errors
    *
    * @param string $message    string
    * @param int    $statusCode integer
    * @param int    $heading    string
    *
    * @return void
    */
    public function showError($message, $statusCode = 500, $heading = 'An Error Was Encountered')
    {
        global $c;
        header('Content-type: text/html; charset='.$c['config']['locale']['charset']); // Some times we use utf8 chars in errors.
        $this->logger->error('HTTP Error --> '.$message, false);
        echo $this->showHttpError($heading, $message, 'general', $statusCode);
        exit();
    }

}

// END Response Class

/* End of file Response.php */
/* Location: .Obullo/Http/Response.php */