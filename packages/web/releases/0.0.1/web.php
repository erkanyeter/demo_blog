<?php

/**
* Web Service Client Class
* Dance with web services.
* 
* Send hmvc request to your web_service
* directory.
* 
* @package       packages
* @subpackage    mambo
* @category      web_service
* @link
*/

Class Web {
    
    protected $web_service_directory; // default web service directory
    public $data = array();  // database column keys & values *

    // ------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct($directory = 'web_service')
    {
        $this->web_service_directory = $directory;

        if( ! isset(getInstance()->web))
        {
            getInstance()->web = $this; // Make available it in the controller $this->mambo->method();
        }

        logMe('debug', 'Mambo Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Set data for Rest CRUD data
     * 
     * @param string $key
     * @param string $val
     */
    public function __set($key, $val)
    {
        $this->data[$key] = $val;   // set query string params
    }

    // ------------------------------------------------------------------------

    /**
     * new Web ( Hmvc ) Request
     * 
     * @param  string  $methodString method.name
     * @param  array $params         sending post query string data
     * @param  integer $ttl          cache expiration time
     * @return object
     */
    public function query($method = 'post', $methodQueryString, $params = array(), $ttl = 0)
    {
        $exp    = explode('.',$methodQueryString);
        $method = strtoupper($method);

        if( ! in_array($method, array('GET','POST','PUT','DELETE'))) // allowed methods
        {
            throw new Exception(
                sprintf('Method "%s" is not allowed available '.get_class().' methods listed below.'.
                "\n<pre>GET\nPOST\nPUT\nDELETE\n</pre>", $method)
                );
        }

        //------------- START HMVC PROCESS -----------//

        $hmvc = new Hmvc();
        $hmvc->clear();     // clear hmvc for multiple requests
        $hmvc->noLoop();                 
        $hmvc->setRequestUrl($this->web_service_directory.'/'.$methodQueryString, $ttl);
        $hmvc->setMethod($method, array_merge(array('data' => $this->data), $params));

        $response = $hmvc->exec();

        //------------- END HMVC PROCESS -----------//

        $this->data = array();  // Reset Query data ( database column names which are set by rest query method )

        return $response;
    }

    // ------------------------------------------------------------------------
    
    public function get()
    {
        return $this->exec('get', func_get_args());
    }
    
    // ------------------------------------------------------------------------

    public function post()
    {
        return $this->exec('post', func_get_args());
    }

    // ------------------------------------------------------------------------

    public function put()
    {
        return $this->exec('put', func_get_args());
    }

    // ------------------------------------------------------------------------

    public function delete()
    {
        return $this->exec('delete', func_get_args());
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
    public function exec($method = 'get', $request_uri = '', $params = array(), $ttl = '0')
    {
        logMe('debug', 'Web Class '.ucfirst($method).' Executed');

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
                $hmvc = new Hmvc();   // Every hmvc request must create new instance.
                $hmvc->clear();       // Clear variables for each request.
                $hmvc->noLoop();
                $hmvc->setRequestUrl($method);
                
                return $hmvc->exec();
            }
            
            $request_uri = $method;
            $method      = 'GET';   // Set default method
        }

        $hmvc = new Hmvc();
        $hmvc->clear();
        $hmvc->noLoop();                 
        $hmvc->setRequestUrl($request_uri, $ttl);
        $hmvc->setMethod($method, $params);
    
        return $hmvc->exec();   // return to hmvc object
    }

}

// END Web Class

/* End of file web.php */
/* Location: ./packages/web/releases/0.0.1/web.php */