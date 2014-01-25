<?php

/**
* Web Service Client Class
* Dance with web services.
* 
* Send hmvc request to your web_service
* directory.
* 
* @package       packages
* @subpackage    web
* @category      web_service
* @link
*/

Class Web {
    
    public $uri_extension      = 'json';    // response format
    public $data               = array();   // request data

    // ------------------------------------------------------------------------

    protected $web_service_directory;  // default web service directory

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

        logMe('debug', 'Web Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Set $_REQUEST Data
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
     * @param  closure $data        sending post query string data
     * @param  integer $ttl         cache expiration time
     * @return object
     */
    public function query($method = 'post', $methodQueryString, $data = '', $ttl = 0)
    {
        if(strpos($methodQueryString, '.') !== false)
        {
            $extension           = explode('.', $segment);
            $this->uri_extension = end($extension);
        }

        return $this->exec(strtoupper($method), $this->web_service_directory.'/'.$methodQueryString, $data, $ttl);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Hmvc Get Request
     * 
     * @param  string $uri    
     * @param  array  $params 
     * @param  string $ttl    
     * @return string         
     */
    public function get($uri, $data = '', $ttl = '0')
    {
        return $this->exec('get', $uri, $data, $ttl);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Hmvc Post Request
     * 
     * @param  string $uri    
     * @param  array  $params 
     * @param  string $ttl    
     * @return string         
     */
    public function post($uri, $data = '', $ttl = '0')
    {
        return $this->exec('post', $uri, $data, $ttl);
    }

    // ------------------------------------------------------------------------

    /**
     * Hmvc Put Request
     * 
     * @param  string $uri    
     * @param  array  $params 
     * @param  string $ttl    
     * @return string         
     */
    public function put($uri, $data = '', $ttl = '0')
    {
        return $this->exec('put', $uri, $data, $ttl);
    }

    // ------------------------------------------------------------------------

    /**
     * Hmvc Delete Request
     * 
     * @param  string $uri    
     * @param  array  $params 
     * @param  string $ttl    
     * @return string
     */
    public function delete($uri, $data = '', $ttl = '0')
    {
        return $this->exec('delete', $uri, $data, $ttl);
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
    public function exec($method = 'get', $request_uri = '', $data = '', $ttl = '0')
    {
        logMe('debug', 'Web Class '.ucfirst($method).' Executed');

        if( ! in_array($method, array('GET','POST','PUT','DELETE'))) // allowed methods
        {
            throw new Exception(
                sprintf('Method "%s" is not allowed available '.get_class().' methods listed below.'.
                "\n<pre>GET\nPOST\nPUT\nDELETE\n</pre>", $method)
                );
        }

        if( ! is_callable($data))
        {
            throw new Exception(
                sprintf('Third paramater "%s" must be callable.'.
                "\n<pre>\$this->web->query('post','example.method.json',function(){});</pre>", '$data')
                );
        }

        // run closure data
        call_user_func_array(Closure::bind($data, $this, get_class()), array());

        $hmvc = new Hmvc();
        $hmvc->clear();
        $hmvc->noLoop();                 
        $hmvc->setRequestUrl($request_uri, $ttl);
        $hmvc->setMethod($method, $this->data);
        
        $response   = $hmvc->exec();   // return to hmvc object
        $this->data = array();       // Reset Query data

        return $response;
    }

    // ------------------------------------------------------------------------

    /**
     * Generate query results using
     * Web_Results_$driver
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        static $resultObject = null;

        if( ! method_exists($this, $method))  // Call the Validator object methods
        {   
            if( ! is_object($restultObject))
            {
                $resultClass   = 'Web_Results_'.strtoupper($this->uri_extension);
                $resultObject  = new $resultClass();
            }

            return call_user_func_array(array($resultObject, $method), $arguments);
        }
    }

}

// END Web Class

/* End of file web.php */
/* Location: ./packages/web/releases/0.0.1/web.php */