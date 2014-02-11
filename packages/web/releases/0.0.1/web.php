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
    
    public $data          = array();   // request data
    public $uri_extension = 'json';    // response format
    public $array_output  = array();   // array output of json response

    // ------------------------------------------------------------------------

    protected $raw_output;             // response raw output of current web request
    protected $web_service_directory;  // default web service directory

    // ------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct($directory = 'web_model')
    {
        $this->web_service_directory = $directory;

        if( ! isset(getInstance()->web))
        {
            getInstance()->web = $this; // Make available it in the controller $this->web->method();
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
     * @param  closure $data        sending post query string data
     * @param  string  $method      method.name
     * @param  integer $ttl         cache expiration time
     * @return object
     */
    public function query($methodQueryString, $data = '', $method = 'post', $ttl = 0)
    {
        if(strpos($methodQueryString, '.') !== false)
        {
            $extension           = explode('.', $methodQueryString);
            $this->uri_extension = strtolower(end($extension));
        }

        $this->exec(strtoupper($method), $this->web_service_directory.'/'.$methodQueryString, $data, $ttl);

        // Decode json data.
        $this->array_output = json_decode($this->getRawOutput(), true);

        return $this->array_output['messages']; // return to validator messages
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
     * @param int $ttl expire time
     * @return string | boolean false
     */
    public function exec($method = 'get', $request_uri = '', $data = '', $ttl = '0')
    {
        $method = strtoupper($method);

        if( ! in_array($method, array('GET','POST','PUT','DELETE'))) // allowed methods
        {
            throw new Exception(
                sprintf('Method "%s" is not allowed available '.get_class().' methods listed below.'.
                "\n<pre>GET\nPOST\nPUT\nDELETE\n</pre>", $method)
                );
        }

        if(is_callable($data))  // run closure data
        {
            call_user_func_array(Closure::bind($data, $this, get_class()), array());
        } 
        
        if(is_array($data)) // if data is array
        {
            $this->data = array_merge($this->data, $data);
        }

        $hmvc = new Hmvc();
        $hmvc->clear();
        $hmvc->noLoop();                 
        $hmvc->setRequestUrl($request_uri, $ttl);
        $hmvc->setMethod($method, $this->data);
        
        $this->raw_output = $hmvc->exec(); // return to hmvc object
        
        $this->clear();    // reset Object Variables

        logMe('debug', 'Web Class '.$method.' Executed');

        if(empty($this->raw_output)) // return false if output is empty.
        {
            return false;
        }

        return $this->raw_output;
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
        if( ! method_exists($this, $method))  // Call the Web_Results object methods
        {   
            $resultObject  = new Web_Results($this->array_output); // Send raw output to result object.

            return call_user_func_array(array($resultObject, $method), $arguments);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Clear variables after that
     * for each execute of web request.
     * 
     * @return void
     */
    public function clear()
    {
        $this->uri_extension = 'json';
        $this->data          = array();
    }

    // ------------------------------------------------------------------------

    /**
     * Get raw output of the hmvc
     * response.
     * 
     * @return string
     */
    public function getRawOutput()
    {
        return (string)$this->raw_output;
    }

}

// END Web Class

/* End of file web.php */
/* Location: ./packages/web/releases/0.0.1/web.php */