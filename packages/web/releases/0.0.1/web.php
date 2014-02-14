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

    // ------------------------------------------------------------------------

    protected $raw_output;             // response raw output of the current web request
    protected $web_service_directory;  // default web service directory

    // ------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct($directory = 'web_model')
    {
        $this->web_service_directory = $directory;

        getInstance()->translator->load('web');

        $this->__assignObjects();   // Assign all controller objects and make available them in this class.

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
        if($key == 'data')
        {
            $this->data[$key] = $val;   // set query string params
        } 
        else 
        {
            $this->{$key} = $val;       // assign controller variables
        }
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

        $r = json_decode($this->getRawOutput(), true); // Decode json data.

        //-------- Check Web Model Standarts ----------//
        
        $standards_of_web_model = "The web model standards requires below the structure. <pre>
\$r = array(

    'success' => 1,
    'results' => array(),
    'message' => '',         // optional key

)</pre>";
        $standards_of_fail_e    = "The web model standards requires below the structure for failure operations. <pre>
\$r = array(

    'success' => 0,
    'message' => '',
    'e' => \$e->getMessage(), // optional key

)</pre>";

        if(isset($r['success']))
        {
            $r['success'] = ($r['success'] === '1' 
                OR $r['success'] === 1 
                OR $r['success'] === 'true' 
                OR $r['success'] === true) ? true : false;

            if($r['success'] AND ! isset($r['results']))  // Successful operation.
            {
                throw new Exception($standards_of_web_model);
            } 
            elseif( $r['success'] == 0 AND ! isset($r['message']))  // Unsuccessful operation.
            {
                throw new Exception($standards_of_fail_e);
            }

            if(isset($r['message']))
            {
                if(strpos($r['message'], 'translate:') === 0)  // Translate the message
                {
                    $line = substr($r['message'], 10);

                    $r['message'] = translate($line); // failure translation
                }
            }
        } 
        else 
        {
            throw new Exception($standards_of_web_model);
        }

        return $r; // return to validator messages
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

    // ------------------------------------------------------------------------

    /**
     * Assign all objects.
     * 
     * @return void
     */
    private function __assignObjects()
    {
        foreach(get_object_vars(getInstance()) as $k => $v)  // Get object variables
        {
            if(is_object($v)) // Do not assign again reserved variables
            {
                $this->{$k} = getInstance()->{$k};
            }
        }
    }

}

// END Web Class

/* End of file web.php */
/* Location: ./packages/web/releases/0.0.1/web.php */