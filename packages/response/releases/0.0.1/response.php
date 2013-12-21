<?php

 /**
 * Response Helper
 * 
 * Http Headers, Errors
 *
 * @package       packages
 * @subpackage    response
 * @category      http responses
 * @link
 */

Class Response {

    public function __construct()
    {
        // Check the controller instance is Object ?
        // When we build the 404 page errors getInstance() comes as null
        
        if( is_object(getInstance()) AND ! isset(getInstance()->response))
        {
            getInstance()->response = $this; // Make available it in the controller $this->response->method();
        }

        logMe('debug', 'Response Class Initialized');
    }

    // ------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Response\Src\\'.$method))
        {
            require PACKAGES .'response'. DS .'releases'. DS .$packages['dependencies']['response']['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Response\Src\\'.$method, $arguments);
    }

}

// END Response Class

/* End of file Response.php */
/* Location: ./packages/response/releases/0.0.1/response.php */