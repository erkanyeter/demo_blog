<?php

/**
* Web Request Helper
* Do Request to another controller using HMVC Class.
*
* @package       packages
* @subpackage    web_request
* @category      hmvc
* @link
*/

Class Web_Request {
    
    /**
     * Constructor
     */
    public function __construct()
    {
        if( ! isset(getInstance()->web_request))
        {
            getInstance()->web_request = $this; // Make available it in the controller $this->get->method();
        }

        logMe('debug', 'Web Request Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Defined methods GET, POST, PUT, DELETE
     * executes the 
     * 
     *    -> exec($method, $uri, $params, $cache_time_or_config)
     * 
     * @param  string $method Request Method ( GET, POST, PUT, DELETE )
     * @param  array $arguments
     * @return string
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array('Web_Request', 'exec'), $arguments);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Execute the request
     * 
     * @param string $method
     * @param string $request_uri
     * @param array $params
     * @param int | array $cache_time_or_config
     * @return string
     */
    public function exec($method = 'get', $request_uri = '', $params = array(), $cache_time_or_config = '0')
    {
        logMe('debug', 'Web Request Class '.ucfirst($method).' Executed');

        $methods = array('GET' => '', 'POST' => '', 'PUT' => '', 'DELETE' => ''); // Supported request methods

        if( ! isset($methods[strtoupper($method)]))
        {
            if(is_numeric($params))
            {
                $cache_time_or_config = $params;
            }
            
            if(is_array($request_uri))
            {
                $params  = $request_uri;
            }

            if($request_uri === false)  // Long Access request
            {   
                $hmvc = new Hmvc();  // Every hmvc request must create new instance.
                $hmvc->clear();               // Clear variables for each request.
                $hmvc->noLoop();
                $hmvc->request($method);
                
                return $hmvc->exec();
            }
            
            $request_uri = $method;
            $method      = 'GET';   // Set default method
        }

        $hmvc = new Hmvc();
        $hmvc->clear();
        $hmvc->noLoop();                 
        $hmvc->request($request_uri, $cache_time_or_config);
        $hmvc->setMethod($method, $params);
    
        return $hmvc->exec();   // return to hmvc object
    }
    
}

/* End of file web_request.php */
/* Location: ./packages/web_request/releases/0.0.1/web_request.php */