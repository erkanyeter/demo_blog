<?php

 /**
 * Hvc Class
 * "Hierarcial View Controller" Library
 * 2009 -2014
 * 
 * @author        Obullo - obulloframework@gmail.com
 * @package       packages
 * @subpackage    hvc
 * @category      hvc
 * 
 */

Class Hvc {

    // Controller Object
    public $global = null;     // Global instance of the controller object we need to clone it.
    public $config = array();  // hvc configuration
    
    // Request, Response, Reset
    public $query_string   = '';
    public $response       = '';
    public $request_keys   = array();
    public $request_method = 'GET';
    public $process_done   = false;
    public $no_loop        = true;
    
    // Clone objects
    public $uri        = null;
    public $router     = null;
    public $cfg        = null;
    public $translator = null;

    // Cache and Connection
    public $connection      = true;
    protected $_conn_string = '';       // Unique HVC connection string that we need to convert it to conn_id.
    protected static $cid   = array();  // Static HVC Connection ids. DO NOT CLEAR IT !!!

    // Benchmark
    public static $start_time    = '';     // benchmark start time
    public static $request_count = 0;      // request count for profiler

    // --------------------------------------------------------------------

    /**
    * Reset all variables for multiple
    * HVC requests.
    *
    * @return   void
    */
    public function clear()
    {
        // Controller Object
        $this->global          = null;     // Global instance of the controller object

        // Request, Response, Reset
        $this->reponse         = '';
        $this->request_keys    = array();
        $this->request_method  = 'GET';
        $this->process_done    = false;
        $this->no_loop         = true;

        // Clone objects
        $this->uri             = null;
        $this->router          = null;
        $this->cfg             = null;

        // Cache and Connection
        $this->connection      = true;
        $this->_conn_string    = '';
        
        $GLOBALS['_GET_BACKUP']     = array();    // Reset global variables
        $GLOBALS['_POST_BACKUP']    = array();
        $GLOBALS['_SERVER_BACKUP']  = array();
        $GLOBALS['_REQUEST_BACKUP'] = array();

        unset($_SERVER['HVC_REQUEST']);
        unset($_SERVER['HVC_REQUEST_URI']);
        unset($_SERVER['HVC_REQUEST_TYPE']);
    }

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {        
        global $logger;

        if( ! isset(getInstance()->hvc))  // Like Singleton
        {
            $this->config = getConfig('hvc');   // Get hvc configuration

            getInstance()->translator->load('hvc'); // Load translate file
            getInstance()->hvc = $this;             // Make available it in the controller $this->hvc->method();
        }

        $logger->debug('Hvc Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
    * Prepare HVC Request (Set the URI String).
    *
    * @access    private
    * @param     string $uri
    * @param     integer $expiration whether to use "Cache" package
    * @return    void
    */
    public function setRequestUrl($uriString = '', $expiration = 0)
    {
        // ----------- Visibility -----------------
        
        $type      = 'public';
        $uriString = trim($uriString, '/');

        if(strpos($uriString, 'private/') === 0)  // Set the visibility of hvc request
        {
            $type      = 'private';
            $uriString = substr($uriString, 8);
        }

        if(strpos($uriString, 'public/') === 0)  // Set the visibility of hvc request
        {
            $type      = 'public';
            $uriString = substr($uriString, 6);
        }

        //-------- BACKUP GLOBALS ( We need them in Get Class ) ---------//
        
        $GLOBALS['_GET_BACKUP']     = $_GET;    // Original request variables
        $GLOBALS['_POST_BACKUP']    = $_POST;
        $GLOBALS['_SERVER_BACKUP']  = $_SERVER;
        $GLOBALS['_REQUEST_BACKUP'] = $_REQUEST;
        
        //--------- 

        $GLOBALS['PUT'] = $_POST = $_GET = $_REQUEST = array();   // reset global variables
        
        unset($_SERVER['HTTP_ACCEPT']);    // Don't touch global server items 
        unset($_SERVER['REQUEST_METHOD']);

        $_SERVER['HVC_REQUEST']      = true;   // Set Hvc Headers
        $_SERVER['HVC_REQUEST_TYPE'] = $type;  // "public" or "private"

        //--------------------------

        $this->_setConnString($uriString);

        // Don't clone getInstance(), we just do backup.
        
        #######################################

        $this->global = getInstance();     // We need create backup $this object of main controller
                                           // becuse of it will change when HVC process is done.
                                           //
        #######################################

        if( ! empty($uriString)) // empty control
        {
            global $uri, $router, $translator, $cfg;

            // Clone Objects
            // -----------------------------------------
            
            $this->uri        = clone $uri;         // Create copy of original Uri class.
            $this->router     = clone $router;      // Create copy of original Router class.
            $this->cfg        = clone $cfg;         // Create copy of original Config class.
            $this->translator = clone $translator;  // Create copy of original Config class.

            // Clear
            // -----------------------------------------

            $uri->clear();           // Reset uri objects we will reuse it for hvc
            $router->clear();        // Reset router objects we will reuse it for hvc.

            // -----------------------------------------

            //----------------------------------------------
            // Set Uri String to Uri Object
            //----------------------------------------------

            if(strpos($uriString, '?') > 0)
            {
                $uri_part           = explode('?', urldecode($uriString));  // support any possible url encode operation
                $this->query_string = $uri_part[1];     // .json?id=2

                $uri->setUriString($uri_part[0], false); // false = null filter
            }
            else
            {
                $uri->setUriString($uriString);
            }

            // Set uri string to $_SERVER GLOBAL
            //----------------------------------------------
            
            $_SERVER['HVC_REQUEST_URI'] = $uriString;

            //----------------------------------------------

            $this->connection = $router->_setRouting(); // Returns false if we have hvc connection error.

            //----------------------------------------------
        }
    
    }

    // --------------------------------------------------------------------

    /**
    * Set Hvc Request Method
    *
    * @param    string $method
    * @param    mixed  $data   params or data
    * @return   void
    */
    public function setMethod($method = 'GET' , $data = '')
    {
        if(empty($data))
        {
            $data = array();
        }

        $method = $this->request_method = strtoupper($method);

        $this->_setConnString($method);        // Set Unique connection string foreach HVC requests
        $this->_setConnString(serialize($data));

        if($this->query_string != '')
        {
            $querStringParams = $this->parseQuery($this->query_string);

            if(is_array($data) AND sizeof($data) > 0)
            {
                $data = array_merge($querStringParams, $data);
            }
        }

        switch ($method)
        {
           case 'POST':
            
            if( ! is_array($data))
            {
                throw new Exception('Data must be array when using Hvc POST method.');
            }

            foreach($data as $key => $val)
            {
                $_POST[$key]    = is_string($val) ? urldecode($val) : $val;  // url support
                $this->request_keys[$key] = '';
            }

            if($this->getVisibility() == 'private')   // use global post variables for model requests.
            {
                $_POST = array_merge($_POST, $GLOBALS['_POST_BACKUP']);
            }

             break;

           case ($method == 'GET' OR $method == 'DELETE'):
            
            if( ! is_array($data))
            {
                throw new Exception('Data must be array when using Hvc GET or DELETE methods.');
            }
               
            foreach($data as $key => $val)
            {
                $_GET[$key]     = is_string($val) ? urldecode($val) : $val;
                $this->request_keys[$key] = '';
            }

            if($this->getVisibility() == 'private')   // use global variables for private requests.
            {
                $_GET = array_merge($_GET, $GLOBALS['_GET_BACKUP']);
            }

             break;

           case 'PUT':

            if(is_array($data) AND sizeof($data) > 0)
            {
                foreach($data as $key => $val)
                {
                    $_REQUEST[$key] = is_string($val) ? urldecode($val) : $val;
                    $this->request_keys[$key] = '';
                }
            }
            else
            {
                $GLOBALS['PUT'] = $_REQUEST['PUT'] = $data;
            }

            if($this->getVisibility() == 'private')   // use global variables for private requests.
            {
                $_REQUEST = array_merge($_REQUEST, $GLOBALS['_REQUEST_BACKUP']);
            }
             break;
        }

        $_SERVER['REQUEST_METHOD'] = $method;  // Set request method ..
    }

    // --------------------------------------------------------------------

    /**
    * Parse Url if there is any possible query string like this
    *
    * $this->hvc->get('welcome/test/index?foo=im_foo&bar=im_bar');
    *
    * @param  string $query_string
    * @return array  $segments
    */
    public function parseQuery($query_string = '')
    {
        if($query_string == '')
        {
            return array();
        }

        parse_str(html_entity_decode($query_string), $segments);

        return $segments;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Hvc Get Request
     * 
     * @param  string $uri    
     * @param  array  $data request data ( $_POST or $_GET )
     * @param  integer $expiration whether to use cache
     * @return string         
     */
    public function get($uri, $data = '', $expiration = 0)
    {
        return $this->request('GET', $uri, $data, $expiration);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Hvc Post Request
     * 
     * @param  string $uri    
     * @param  mixed  $data request data ( $_POST or $_GET )
     * @param  integer $expiration whether to use cache
     * @return string         
     */
    public function post($uri, $data = '', $expiration = 0)
    {
        return $this->request('POST', $uri, $data, $expiration);
    }

    // ------------------------------------------------------------------------

    /**
     * Hvc Put Request
     * 
     * @param  string $uri    
     * @param  array  $data request data ( $_POST or $_GET )
     * @return string         
     */
    public function put($uri, $data = '')
    {
        return $this->request('PUT', $uri, $data);
    }

    // ------------------------------------------------------------------------

    /**
     * Hvc Delete Request
     * 
     * @param  string $uri    
     * @param  array  $data request data ( $_POST or $_GET )
     * @return string
     */
    public function delete($uri, $data = '')
    {
        return $this->request('DELETE', $uri, $data);
    }

    // ------------------------------------------------------------------------

    /**
     * Get visibility of request Private / Public
     * 
     * @return string
     */
    public function getVisibility()
    {
        return (isset($_SERVER['HVC_REQUEST_TYPE'])) ? $_SERVER['HVC_REQUEST_TYPE'] : 'public';
    }

    // ------------------------------------------------------------------------

    /**
     * Do request 
     * 
     * @param  string  $uri
     * @param  array   $data request data ( $_POST or $_GET )
     * @param  integer $ttl
     * @return string
     */
    public function request($method, $uri, $data = '', $expiration = 0)
    {
        if(is_numeric($data)) // set expiration as second param if data not provided
        {
            $expiration = $data;
            $data       = array();
        }

        $this->clear();
        $this->setRequestUrl($uri, $expiration);
        $this->setMethod($method, $data);
        
        $v = $this->getVisibility();
        $r = $this->exec($expiration); // execute the process

        $errorHeader = '<div style="white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;
  background:#fff;border:1px solid #ddd;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#666;font-size:12px;">';
        $errorFooter = '</div>';

        $isXmlHttp = (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;

        if (is_string($r) AND strpos($r, '404') === 0) {

            if ($isXmlHttp) {
                 return array(
                    'success' => 0,
                    'message' => translate('404'),
                    'errors'  => array()
                );
            }
            echo ($errorHeader.$r.$errorFooter);
            return;
        }

        //------------ Private Request Header -------------//

            if ($v == 'private') // Private Request
            {
                if(strpos(trim($uri, '/'), 'private/views') === 0)  // if request goes to view folder don't check the format
                {
                    return $r;
                }

                $r = json_decode($r, true); // Convert to array

                $hvc_error = $errorHeader.'<span style="font-weight:bold;">Private hvc response must contain at least one of the following keys. ( "private/views" route is excluded ).</span><pre style="border:none;">
$r = array(
    \'success\' => integer     // optional
    \'message\' => string,     // optional
    \'errors\'  => array(),    // optional
    \'results\' => array(),    // optional
    \'e\' => $e->getMessage(), // optional
)

echo json_encode($r); // required</pre>'.$errorFooter;

                if( ! is_array($r)) // If success not exists !
                {            
                    if ($isXmlHttp) {
                         return array(
                            'success' => 0,
                            'message' => $hvc_error,
                            'errors'  => array()
                        );
                    }
                    echo ($hvc_error);
                    return;
                }

                //--------- Check the Private Response Format -----------//
            
                $key_errors = array_map(function($v){  
                    return in_array($v, array('success','message','errors','results','e')); // Get the keys of hvc result array
                }, array_keys($r));

                if(in_array(false, $key_errors, true))  // throws an exception
                {
                    if ($isXmlHttp) {
                         return array(
                            'success' => 0,
                            'message' => $hvc_error,
                            'errors'  => array()
                        );
                    }
                    echo ($hvc_error);
                    return;
                }

                return $r;
            }

        //------------ Private Request Header End -------------//

        return $r;
    }

    // ------------------------------------------------------------------------

    /**
    * Execute Hvc Request
    *
    * @return   string
    */
    public function exec($expiration = 0)
    {
        global $uri, $router, $logger;
        static $storage = array();      // store "$c " variables ( called controllers )

        // ------------------------------------------------------------------------

        list($sm, $ss)    = explode(' ', microtime());  // Start the Query Timer 
        self::$start_time = ($sm + $ss);

        // ------------------------------------------------------------------------

        $KEY = $this->getKey();  // Get Hvc Key

        // ----------------- Memory Cache Key Exists -------------------//

        if($expiration > 0 AND $this->cache->keyExists($KEY)) // If cache exists return to cached string.
        {
            $cache       = $this->config['cache'];
            $this->cache = $cache();
            
            $data = $this->cache->get($KEY);
            return base64_decode($data);        // encoding for specialchars
        }

        // ----------------- Static Php Cache -------------------//

        if( isset(self::$cid[$KEY]) )   // Cache the multiple HVC requests in the same controller.
        {                               // This cache type not related with Cache package.
            $this->_clear(true);

            return $this->getResponse();
        }

        self::$cid[$KEY] = $KEY;    // store connection id.
    
        // ----------------- Route is Valid -------------------//

        $response = $router->getResponse();

        if($this->connection === false OR $response != false)  // If router dispatch is fail ?
        {
            $k = key($response);
            $this->_clear();

            $this->setResponse($k.' '.$response[$k]);
            return $this->getResponse();
        }
        
        //----------------------------------------------------------------------------
        //  Create an uniq HVC Uri
        //  A Hvc uri must be unique otherwise
        //  may collission with standart uri, also we need it for caching feature.
        //  --------------------------------------------------------------------------

        $uri->setUriString(rtrim($uri->getUriString(), '/').'/'.$this->config['unique_key_prefix'].$KEY); // Create an uniq HVC Uri with md5 hash

        //  --------------------------------------------------------------------------

        $root = PUBLIC_DIR;

        if($this->getVisibility() == 'private') // Send "private" requests to private folder
        {
            $root = PRIVATE_DIR;
        }

        $hvc_uri    = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = $root. $router->fetchDirectory(). DS .'controller'. DS .$router->fetchClass(). EXT;

        // --------- Check class is exists in the storage ----------- //

        if(isset($storage[$router->fetchClass()])) // Check is multiple call to same class.
        {
            $c = $storage[$router->fetchClass()];  // Get stored class.
        } 
        else
        {
            require($controller);        // Call the controller.
        }

        // --------- End storage exists ----------- //

        // $c variable available here !

        if( ! isset($c->request->global))  // ** Let's create new request object for globals
        {
            $c->request = new Request;     // create new request object;
                                           // create a global variable
                                           // keep all original objects in it.
                                           // e.g. $this->request->global->uri->getUriString();
            
            // ** Store global "Uri" and "Router" objects into sub layer
            //---------------------------------------------------------------------------

            $c->request->global             = new stdClass;      // Create an empty class called "global"
            $c->request->global->uri        = $this->uri;        // Let's assign the global uri object
            $c->request->global->router     = $this->router;     // Let's assign the global uri object
            $c->request->global->config     = $this->cfg;        // Let's assign the global config object
            $c->request->global->translator = $this->translator; // Let's assign the global config object
        }

        // End store global variables

        if ( strncmp($router->fetchMethod(), '_', 1) == 0  // Do not run private methods. ( _output, _remap, _getInstance .. )
                OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            $this->setResponse('404 - Hvc request not found: '.$hvc_uri);
            $this->_clear();
            
            return $this->getResponse();
        }
         
        // Get application methods
        //----------------------------

        $_storedMethods = array_keys($c->_controllerAppMethods);

        //----------------------------
        // Check method exist or not
        //----------------------------
        
        if ( ! in_array(strtolower($router->fetchMethod()), $_storedMethods))
        {
            $this->setResponse('404 - Hvc request not found: '.$hvc_uri);
            $this->_clear();

            return $this->getResponse();
        }

        // Slice Arguments
        //----------------------------
        
        $arguments = array_slice($uri->rsegments, 3);

        //----------------------------

        ob_start(); // Start the output buffer.
    
        // Call the requested method. Any URI segments present (besides the directory / class / method) 
        // will be passed to the method for convenience
        // directory = 0, class = 1, method = 2
        call_user_func_array(array($c, $router->fetchMethod()), $arguments);
        
        //----------------------------

        $content = ob_get_contents(); // Get the contents of the output buffer
        
        //----------------------------

        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
                      
        //----------------------------

        $this->setResponse($content); 
        $this->_clear();

        //--------------------------------------
        // Store classes to $storage container
        //--------------------------------------

        $storage[$router->fetchClass()] = $c; // Store class names to storage. We fetch it if its available in storage.

        //----------------------------
        // End storage

        $response = $this->getResponse();

        //------------- Set to Memory Cache -------------//

        if($expiration > 0)
        {
            $cache       = $this->config['cache'];
            $this->cache = $cache();                // load cache library
            
            $data = base64_encode($response);
            $this->cache->set($KEY, $data, (int)$expiration);
        }

        $logger->debug('Hvc process done.');
        $logger->debug('Hvc output: '.$response);
        
        return $response;
    }

    // --------------------------------------------------------------------

    /**
    * Reset router for mutiple hvc requests
    * or who want to close the hvc connection.
    *
    * @param    boolean $no_loop anti loop
    * @return   void
    */
    protected function _clear($no_loop = false)
    {              
        if( ! isset($_SERVER['HVC_REQUEST_URI']))  // if no hvc header return to null;
        {
            return;
        }

        $currentUri = $_SERVER['HVC_REQUEST_URI'];

        // Assign global variables we copied before ..
        // --------------------------------------------------
        
        $GLOBALS['PUT'] = $_SERVER = $_POST = $_GET = $_REQUEST = array(); // reset all globals

        $_GET     = $GLOBALS['_GET_BACKUP'];   // Set back original request variables
        $_POST    = $GLOBALS['_POST_BACKUP'];
        $_SERVER  = $GLOBALS['_SERVER_BACKUP'];
        $_REQUEST = $GLOBALS['_REQUEST_BACKUP'];

        // Set original $this to controller instance that we backup before.
        // --------------------------------------------------
        
        getInstance($this->global);
        
        getInstance()->uri        = $this->uri;     // restore back original objects
        getInstance()->router     = $this->router;
        getInstance()->config     = $this->cfg;
        getInstance()->translator = $this->translator;

        $this->clear();  // reset all HVC variables.

        //---------------------------------------------------

        if($no_loop == false)
        {
            global $logger;

            ++self::$request_count; // store total requests

            list($em, $es) = explode(' ', microtime());
            $end_time = ($em + $es); 

            $logger->info('Hvc request: '.$currentUri.' time: '.number_format($end_time - self::$start_time, 4));
        }

        $this->process_done = true;  // This means hvc process done without any errors.
                                     // If process_done == false we say to destruct method "reset the router" variables 
                                     // and return to original variables of the Framework's before we clone them.
    }

    // --------------------------------------------------------------------

    /**
    * Set $_SERVER vars foreach hvc
    * requests.
    *
    * @param string $key
    * @param mixed  $val
    */
    public function setServer($key, $val)
    {
        $_SERVER[$key] = $val;

        $this->_setConnString($key.$val);
        
        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Set hvc response.
    *
    * @param    mixed $data
    * @return   void
    */
    public function setResponse($data = '')
    {
        $this->response = $data;
    }

    // --------------------------------------------------------------------

    /**
    * Get none-decoded original Hvc
    * response.
    * 
    * @return string
    */
    public function getResponse()
    {
        return $this->response;
    }
   
    // --------------------------------------------------------------------
    
    /**
    * Create HVC connection string next
    * we will convert it to connection id.
    *
    * @param    mixed $id
    */
    protected function _setConnString($id)
    {
        $this->_conn_string .= $id;
    }

    // --------------------------------------------------------------------

    /**
    * Returns Hvc key.
    *
    * @return   string
    */
    public function getKey()
    {
        return hash('md5', trim($this->_conn_string));
    }

    // --------------------------------------------------------------------
    
    /**
    * Close Hvc Connection
    * 
    * If we have any possible hvc exceptions
    * reset the router variables, complete to HVC process
    * and return to original vars.
    * 
    * @return void
    */
    public function __destruct()
    {               
        if($this->process_done == false)
        {                                   
            $this->_clear($this->no_loop);

            return;
        }

        $this->process_done = false;
    }

}

// END Hvc Class

/* End of file hvc.php */
/* Location: ./packages/hvc/releases/0.0.1/hvc.php */