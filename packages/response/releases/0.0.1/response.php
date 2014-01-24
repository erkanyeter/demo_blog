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

Class Response {
    
    public $final_output;
    public $headers      = array();

    public static $instance;

    // --------------------------------------------------------------------

    public function __construct()
    {
        logMe('debug', 'Response Class Initialized');
    }

    // --------------------------------------------------------------------
    
    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
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
    * Set Output
    *
    * Sets the output string
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    public function setOutput($output)
    {
        $this->final_output = $output;
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
        if ($this->final_output == '')
        {
            $this->final_output = $output;
        }
        else
        {
            $this->final_output.= $output;
        }
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
    * @access   public
    * @param    string
    * @return   void
    */    
    public function setHeader($header, $replace = true)
    {
        // If zlib.output_compression is enabled it will compress the output,
        // but it will not modify the content-length header to compensate for
        // the reduction, causing the browser to hang waiting for more data.
        // We'll just skip content-length in those cases.

        if (@ini_get('zlib.output_compression') AND strncasecmp($header, 'content-length', 14) == 0)
        {
            return;
        }
        
        $this->headers[] = array($header, $replace);
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
        if ($output == '')  // Set the output data
        {
            $output =& $this->final_output;
        }

        // Is compression requested?  
        // --------------------------------------------------------------------
        
        if (config('compress_output'))
        {
            if (extension_loaded('zlib'))
            {             
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
                {   
                    ob_start('ob_gzhandler');
                }
            }
        }

        // Are there any server headers to send ?
        // --------------------------------------------------------------------
        if(count($this->headers) > 0 ) 
        {       
            if ( ! headers_sent())
            {
                foreach ($this->headers as $header)
                {
                    header($header[0], $header[1]);
                }
            }        
        }

        // --------------------------------------------------------------------
        
        // Does the getInstance() function exist?
        // If not we know we are dealing with a cache file so we'll
        // simply echo out the data and exit.
        if ( ! function_exists('getInstance'))
        {
            echo $output;
            
            logMe('debug', "Final output sent to browser");
            
            return true;
        }
        
        // Does the controller contain a function named _output()?
        // If so send the output there.  Otherwise, echo it.
        if (method_exists(getInstance(), '_output'))
        {
            getInstance()->_output($output);
        }
        else
        {
            echo $output;  // Send it to the browser!
        }
        
        logMe('debug', "Final output sent to browser");

        if (config('log_benchmark')) // Do we need to generate benchmark data ? If so, enable and run it.
        {
            $memory_usage = "memory_get_usage() function not found on your php configuration.";

            if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
            {
                $memory_usage = number_format($usage)." bytes";
            }
            
            logMe('bench', "Memory Usage: ". $memory_usage); 
        }           
    }

    // --------------------------------------------------------------------

    /**
     * Call helper functions
     * 
     * @param  string $method 
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Response\Src\\'.$method))
        {
            require PACKAGES .'response'. DS .'releases'. DS .$packages['dependencies']['response']['version']. DS .'src'. DS .mb_strtolower($method). EXT;
        }

        return call_user_func_array('Response\Src\\'.$method, $arguments);
    }

}

// END Response Class

/* End of file Response.php */
/* Location: ./packages/response/releases/0.0.1/response.php */