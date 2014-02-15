<?php

/**
* GET Class ( Fetch data from superglobal $_GET variable ).
*
* @package       packages
* @subpackage    get
* @category      request
* @link
* 
*/

Class Get
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if( ! isset(getInstance()->get))
        {
            getInstance()->get = $this; // Make available it in the controller $this->get->method();
        }

        logMe('debug', 'Get Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
    * Fetch an item from the GET array
    *
    * @access   public
    * @param    string
    * @param    bool
    * @param    bool    Use global post values instead of HMVC scope.
    * @return   string
    */
    public function get($index = NULL, $xss_clean = FALSE, $use_global_var = false)
    {
        $VAR = ($use_global_var) ? $GLOBALS['_GET_BACKUP'] : $_GET;   // People may want to use hmvc or app superglobals.

        if ($index === NULL AND ! empty($VAR))  // Check if a field has been provided
        {
            $get = array();
            
            foreach (array_keys($VAR) as $key)  // loop through the full _GET array
            {
                $get[$key] = self::fetchFromArray($VAR, $key, $xss_clean);
            }

            return $get;
        }

        return self::fetchFromArray($VAR, $index, $xss_clean);
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
            return Security::getInstance()->xssClean($array[$index]);
        }

        return $array[$index];
    }

}

// END Get Class

/* End of file get.php */
/* Location: ./packages/get/releases/0.0.1/get.php */