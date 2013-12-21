<?php

/**
* GET Helper ( Manage the $_GET, $_POST, $_SERVER variables ).
*
* @package       packages
* @subpackage    get
* @category      request
* @link
* 
*/

Class Get
{
    public static $method;  // Request method.

    /**
     * Constructor
     */
    public function __construct()
    {
        if( ! isset(getInstance()->get))
        {
            getInstance()->get = $this; // Make available it in the controller $this->get->method();
        }

        logMe('debug', 'Get Helper Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Call defined methods POST, GET, SERVER, REQUEST, COOKIE
     * executes the 
     * 
     *    -> _method($method, $arguments)
     * 
     * @param  string $method Request Method
     * @param  array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        global $packages;

        self::$method = $method;

        $execMethod = array('Get', '_method');

        if($method == 'ipAddress' || $method == 'validIp')
        {
            if( ! function_exists($method))
            {
                require PACKAGES .'get'. DS .'releases'. DS .$packages['dependencies']['get']['version']. DS .'src'. DS .'ipaddress'. EXT;
            }

            $execMethod = $method;
        }

        return call_user_func_array($execMethod, $arguments);
    }

    // --------------------------------------------------------------------

    /**
    * Fetch an item from either the POST array or the GET
    *
    * @access   public
    * @param    string  The index key
    * @param    bool    XSS cleaning
    * @param    bool    Use global post values instead of HMVC values.
    * @return   string
    */
    private function _method($index = '', $xss_clean = false, $use_global_var = false)
    {
        $prefix = ''; // Cookie prefix.

        $REQUEST_VAR['post']    = $_POST;
        $REQUEST_VAR['get']     = $_GET;
        $REQUEST_VAR['server']  = $_SERVER;
        $REQUEST_VAR['request'] = $_REQUEST;
        $REQUEST_VAR['cookie']  = $_COOKIE;

        switch (self::$method)
        {
            case 'post':
                $VAR = ($use_global_var) ? $GLOBALS['_POST_BACKUP']: $_POST;  // If you want to use hmvc's _POST_BACKUP variable
                break;
            case 'get':
                $VAR = ($use_global_var) ? $GLOBALS['_GET_BACKUP']: $_GET; 
                break;
            case 'server':
                $VAR = ($use_global_var) ? $GLOBALS['_SERVER_BACKUP']: $_SERVER;  
                break;
            case 'request':
                $VAR = ($use_global_var) ? $GLOBALS['_REQUEST_BACKUP']: $_REQUEST; 
                break;
            case 'cookie':
                if ( ! isset($_COOKIE[$index]) AND config('cookie_prefix') != '')
                {
                    $prefix = config('cookie_prefix');
                }                
                break;
        }

        if ($index === null AND ! empty($VAR)) // Check if a field has been provided
        {
            $request = array();
            foreach (array_keys($VAR) as $key) // Loop through the full _POST or _GET array and return it
            {
                $request[$key] = self::fetchFromArray($VAR, $key, $xss_clean);
            }

            return $request;
        }

        if(self::$method == 'post' AND isset($VAR[$index])) // First check $_POST variable otherwise use $_GET
    	{
            return self::fetchFromArray($VAR, $index, $xss_clean);
    	} 

        return self::fetchFromArray($REQUEST_VAR[self::$method], $prefix.$index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
    * Fetch from array
    *
    * This is a helper function to retrieve values from global arrays
    *
    * @access   public
    * @param    $method string
    * @param    $array array
    * @param    $index string
    * @param    $xss_clean bool
    * @return   string
    */
    public static function fetchFromArray(&$array, $index = '', $xss_clean = false)
    {
        if ( ! isset($array[$index]))
        {
            return false;
        }
        
        if ($xss_clean)
        {
            return getComponentInstance('security')->xssClean($array[$index]);
        }

        return $array[$index];
    }

}

// END Get Class

/* End of file get.php */
/* Location: ./packages/get/releases/0.0.1/get.php */