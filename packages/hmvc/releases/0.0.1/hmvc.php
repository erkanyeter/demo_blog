<?php

 /**
 * HMVC Class
 * Hierarcial Model View Controller Library
 *
 * @package       packages
 * @subpackage    hmvc
 * @category      hmvc
 * 
 */
Class Hmvc
{
    // Controller Object
    public $_this            = null;  // Clone original getInstance();

    // Request, Response, Reset
    public $query_string     = '';
    public $response         = '';
    public $request_keys     = array();
    public $request_method   = 'GET';
    public $process_done     = false;
    public $no_loop          = false;

    private $class;
    private $method;

    // Clone objects
    public $uri    = '';
    public $router = '';
    public $config = '';

    // Cache and Connection
    public $connection         = true;
    protected $_conn_string    = '';       // Unique HMVC connection string that we need to convert it to conn_id.
    protected static $_conn_id = array();  // Static HMVC Connection ids.

    // Benchmark
    public static $start_time    = '';     // benchmark start time
    public static $request_count = 0;      // request count for profiler
    
    /**
     * Constructor
     */
    public function __construct()
    {        
        if( ! isset(getInstance()->hmvc))
        {
            getInstance()->hmvc = $this; // Make available it in the controller $this->hmvc->method();
        }

        logMe('debug', "Hmvc Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
    * Prepare HMVC Request (Set the URI String).
    *
    * @access    private
    * @param     string $uri
    * @param     int $cache_time
    * @return    void
    */
    public function setRequestUrl($uriString = '', $ttl = 0)
    {
        $this->_setConnString($uriString);

        // Don't clone getInstance(), we just do backup.
        
        #######################################
        
        $this->_this = getInstance();      // We need create backup $this object of main controller
                                           // becuse of it will change when HMVC process is done.
        
        #######################################
        
        if( ! empty($uriString)) // empty control
        {
            $Uri     = getInstance()->uri;
            $Router  = getInstance()->router;
            $Config  = getInstance()->config;

            # CLONE
            #######################################
            
            $this->uri    = clone $Uri;     // Create copy of original URI class.
            $this->router = clone $Router;  // Create copy of original Router class.
            $this->config = clone $Config;  // Create copy of original Config class.

            # CLEAR
            #######################################

            getInstance()->uri->clear();           // Reset uri objects we will reuse it for hmvc
            getInstance()->router->clear();        // Reset router objects we will reuse it for hmvc.

            #######################################
            
            $this->cache_time = $ttl;

            //----------------------------------------------
            // Set Uri String to Uri Object
            //----------------------------------------------

            if(strpos($uriString, '?') > 0)
            {
                $uri_part           = explode('?', urldecode($uriString));  // support any possible url encode operation
                $this->query_string = $uri_part[1]; // .json?id=2

                $Uri->setUriString($uri_part[0], false); // false null filter
            }
            else
            {
                $Uri->setUriString($uriString);
            }
            
            // Set uri string to $_SERVER GLOBAL        
            //----------------------------------------------
            
            $_SERVER['HMVC_REQUEST_URI'] = $uriString;

            //----------------------------------------------

            $this->connection = $Router->_setRouting(); // Returns false if we have hmvc connection error.

            //----------------------------------------------

            return $this;
        }

        return ($this);
    }

    // --------------------------------------------------------------------

    /**
    * Reset all variables for multiple
    * HMVC requests.
    *
    * @return   void
    */
    public function clear()
    {
        // General
        $this->_conn_string    = '';
        $this->query_string    = '';
        $this->reponse         = '';
        $this->request_keys    = array();
        $this->connection      = true;
        
        // Clear clone data
        $this->uri             = '';
        $this->router          = '';
        $this->config          = '';
        
        $this->_this           = '';
        $this->request_method  = 'GET';
        
        $GLOBALS['_GET_BACKUP']     = array();    // Reset global variables
        $GLOBALS['_POST_BACKUP']    = array();
        $GLOBALS['_SERVER_BACKUP']  = array();
        $GLOBALS['_REQUEST_BACKUP'] = array();

        unset($_SERVER['HMVC_REQUEST']);
        unset($_SERVER['HMVC_REQUEST_URI']);
    }

    // --------------------------------------------------------------------

    /**
    * Set HMVC Request Method
    *
    * @param    string $method
    * @param    mixed  $params_or_data
    * @return   void
    */
    public function setMethod($method = 'GET' , $paramsOrData = array())
    {
        $method = $this->request_method = strtoupper($method);

        $this->_setConnString($method);        // Set Unique connection string foreach HMVC requests
        $this->_setConnString(serialize($paramsOrData));

        if($this->query_string != '')
        {
            $querStringParams = $this->parseQuery($this->query_string);

            if(sizeof($querStringParams) > 0)
            {
                if(is_array($paramsOrData) AND sizeof($paramsOrData) > 0)
                {
                    $paramsOrData = array_merge($querStringParams, $paramsOrData);
                }
            }
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
    
        switch ($method)
        {
           case 'POST':
            
            if( ! is_array($paramsOrData))
            {
                throw new Exception('Data must be array when using HMVC POST method.');
            }

            foreach($paramsOrData as $key => $val)
            {
                $_POST[$key]    = is_string($val) ? urldecode($val) : $val;
                $_REQUEST[$key] = is_string($val) ? urldecode($val) : $val;

                $this->request_keys[$key] = '';
            }
             break;

           case ($method == 'GET' || $method == 'DELETE'):
            
            if( ! is_array($paramsOrData))
            {
                throw new Exception('Data must be array when using HMVC GET or DELETE methods.');
            }
               
            foreach($paramsOrData as $key => $val)
            {
                $_GET[$key]     = is_string($val) ? urldecode($val) : $val;
                $_REQUEST[$key] = is_string($val) ? urldecode($val) : $val;

                $this->request_keys[$key] = '';
            }
             break;

           case 'PUT':
            if(is_array($paramsOrData) AND sizeof($paramsOrData) > 0)
            {
                foreach($paramsOrData as $key => $val)
                {
                    $_REQUEST[$key] = is_string($val) ? urldecode($val) : $val;

                    $this->request_keys[$key] = '';
                }
            }
            else
            {
                $GLOBALS['PUT'] = $_REQUEST['PUT'] = $paramsOrData;
            }
             break;
        }

        $_SERVER['REQUEST_METHOD']   = $method;  // Set request method ..
        $_SERVER['HMVC_REQUEST']     = true;
        
        return ($this);
    }

    // --------------------------------------------------------------------

    /**
    * Parse Url if there is any possible query string like this
    *
    * $this->hmvc->setRequestUrl('welcome/test/index?foo=im_foo&bar=im_bar');
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

    // --------------------------------------------------------------------

    /**
    * Execute Hmvc Request
    *
    * @return   string
    */
    public function exec()
    {
        static $storage = array();  // store "$c " variables ( called controllers )

        if($this->no_loop)
        {
            $conn_id = $this->_getId();

            if( isset(self::$_conn_id[$conn_id]) )   // We need that to prevent HMVC loops if someone use hmvc request
            {                                        // in Application or Module Controller.
                $this->_resetRouter(TRUE);

                return $this->_response();
            }

            self::$_conn_id[$conn_id] = $conn_id;    // store connection id.
        }

        $Uri    = getInstance()->uri;
        $router = getInstance()->router;

        //---------- Start the Query Timer -----------//

        list($sm, $ss) = explode(' ', microtime());
        self::$start_time = ($sm + $ss);

        //----------------------------
        // Check the Connection
        //----------------------------

        if($this->connection === false)  // If router dispatch is fail ?
        {
            if($router->getResponse() != false)
            {
                $resp = $router->getResponse();
                $this->setResponse($resp[0].' - '.$resp[1]);
            } 
            else 
            {
                $this->setResponse('404 - Hmvc error: Unable to hmvc connection.');
            }

            $this->_resetRouter();

            return $this->getResponse();
        }
        
        //----------------------------------------------------------------------------
        //  Create an uniq HMVC Uri
        //  A Hmvc uri must be unique otherwise
        //  may collission with standart uri, also we need it for caching feature.
        //  --------------------------------------------------------------------------

        $Uri->setUriString(rtrim($Uri->getUriString(), '/').'/conn_id_'. $this->_getId()); // Create an uniq HMVC Uri

        //  --------------------------------------------------------------------------

        $hmvc_uri   = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = PUBLIC_DIR .$router->fetchDirectory(). DS .$router->getControllerDirectory(). DS .$router->fetchClass(). EXT;

        if ( ! file_exists($controller))   // Check the controller exists or not
        {
            $this->setResponse('404 - Hmvc request not found: Unable to load your controller.');
            $this->_resetRouter();

            return $this->getResponse();
        }

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

        if ( strncmp($router->fetchMethod(), '_', 1) == 0  // Do not run private methods. ( _output, _remap, _getInstance .. )
                OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            $this->setResponse('404 - Hmvc request not found: '.$hmvc_uri);
            $this->_resetRouter();
            
            return $this->getResponse();
        }
         
        // Detect The Application ( Web or Web Service )
        //----------------------------

        $_storedMethods = (get_class($c)) == 'Controller' ? array_keys($c->_controllerAppMethods) : array_keys($c->_webServiceAppMethods);

        //----------------------------
        // Check method exist or not
        //----------------------------
        
        if ( ! in_array(strtolower($router->fetchMethod()), $_storedMethods))
        {
            $this->setResponse('404 - Hmvc request not found: '.$hmvc_uri);
            $this->_resetRouter();

            return $this->getResponse();
        }

        // Slice Arguments
        //----------------------------
        
        $arguments = array_slice($Uri->rsegments, 3);

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
        $this->_resetRouter();    
                       
        //--------------------------------------
        // Store classes to $storage container
        //--------------------------------------

        $storage[$router->fetchClass()] = $c; // Store class names to storage. We fetch it if its available in storage.

        //----------------------------
        // End storage

        $response = $this->getResponse();

        logMe('debug', 'Hmvc process done.');
        logMe('debug', 'Hmvc output: '.$response);
        
        return $response;
    }

    // --------------------------------------------------------------------

    /**
    * Reset router for mutiple hmvc requests
    * or who want to close the hmvc connection.
    *
    * @param    boolean $no_loop anti loop
    * @return   void
    */
    protected function _resetRouter($no_loop = false)
    {              
        $currentUriString = $_SERVER['HMVC_REQUEST_URI'];
        $GLOBALS['PUT']   = $_SERVER = $_POST = $_GET = $_REQUEST = array();

        # Assign global variables we copied before ..
        ######################################
        
        $_GET     = $GLOBALS['_GET_BACKUP'];   // Set back original request variables
        $_POST    = $GLOBALS['_POST_BACKUP'];
        $_SERVER  = $GLOBALS['_SERVER_BACKUP'];
        $_REQUEST = $GLOBALS['_REQUEST_BACKUP'];

        # Set original objects foreach HMVC requests we backup before  ..
        ######################################
        
        $uri    = getComponent('uri');
        $router = getComponent('router');
        $config = getComponent('config');
        
        $this->_this->uri     = $uri::setInstance($this->uri);
        $this->_this->router  = $router::setInstance($this->router);
        $this->_this->config  = $config::setInstance($this->config);
        
        getInstance($this->_this);         // Set original $this to controller instance that we backup before.
    
        # Assign Framework global variables ..
        ######################################

        $this->clear();  // reset all HMVC variables.
        
        ######################################

        //---------- End Query Timer -----------//        

        if($no_loop == FALSE)
        {
            ++self::$request_count; // store total requests

            list($em, $es) = explode(' ', microtime());
            $end_time = ($em + $es); 

            logMe('info', 'Hmvc request: '.$currentUriString.' time: '.number_format($end_time - self::$start_time, 4));

        }

        $this->process_done = true;  // This means hmvc process done without any errors.
                                     // If process_done == false we say to destruct method "reset the router" variables 
                                     // and return to original variables of the Framework's before we clone them.
    }

    // --------------------------------------------------------------------
    
    /**
    * Warning !!!
    * 
    * When we use HMVC in Main Controller
    * HMVC request will be in a unlimited loop, noLoop() function
    * will prevent this loop and any possible http server crashes (ersin).
    *
    * @param  bool $default
    * @return void
    */
    public function noLoop($default = TRUE)
    {
        $this->no_loop = $default;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Set $_SERVER vars foreach hmvc
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
    * Set hmvc response.
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
    * Get none-decoded original Hmvc
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
    * Create HMVC connection string next
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
    * Convert connection string to HMVC
    * connection id.
    *
    * @return   string
    */
    protected function _getId()
    {
        return hash('crc32b', trim($this->_conn_string));
    }

    // --------------------------------------------------------------------
    
    /**
    * Close HMVC Connection
    * 
    * If we have any possible hmvc exceptions
    * reset the router variables, complete to HMVC process
    * and return to original vars.
    * 
    * @return void
    */
    public function __destruct()
    {                 
        if($this->process_done == false)         
        {                                   
            $this->_resetRouter($this->no_loop);

            return;
        }

        $this->process_done = false;
    }

}

// END Hmvc Class

/* End of file hmvc.php */
/* Location: ./packages/hmvc/releases/0.0.1/hmvc.php */