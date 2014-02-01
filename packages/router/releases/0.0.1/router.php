<?php

 /**
 * Router Class
 * Parses URIs and determines routing
 *
 * @package       packages
 * @subpackage    router
 * @category      uri
 * @link
 */

Class Router {

    public $uri;
    public $response             = array();
    public $routes               = array();
    public $error_routes         = array();
    public $class                = '';
    public $method               = 'index';
    public $directory            = '';
    public $uri_protocol         = 'auto';
    public $controller_directory = 'controller';
    public $default_controller;
    
    public static $instance;

    /**
    * Constructor
    * Runs the route mapping function.
    *
    * @return void
    */
    public function __construct()
    {                
        $routes    = getConfig('routes');
        $this->uri = getComponentInstance('uri'); // Warning : Don't load any library in core level.

        $this->routes = ( ! isset($routes) OR ! is_array($routes) ) ? array() : $routes;
        unset($routes);

        $this->method = $this->routes['index_method'];
        $this->_setRouting();
        
        logMe('debug', 'Router Class Initialized');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get Instance of Router
     * 
     * @return object
     */
    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set Instance of Router for Hmvc
     * 
     * @param object
     */
    public static function setInstance($object)
    {
        if(is_object($object))
        {
            self::$instance = $object;
        }
        
        return self::$instance;
    }

    // --------------------------------------------------------------------

    /**
    * Clean all data for Hmvc.
    *
    * @return  void
    */
    public function clear()
    {
        $this->uri                 = getComponentInstance('uri');   // reset cloned URI object.
        $this->response            = array();
        
        // route config dont't reset "$this->routes" there cause some isset errors
        
        $this->error_routes         = array();
        $this->class                = '';
        $this->method               = 'index';
        $this->directory            = '';
        $this->uri_protocol         = 'auto';
        $this->controller_directory = 'controller';
        $this->default_controller   = '';
    }

    // --------------------------------------------------------------------

    /**
    * Clone URI object for HMVC Requests, When we
    * use HMVC we use $this->uri = clone lib('ob/Uri');
    * that means we say to Router class when Clone word used in HMVC library
    * use cloned URI object instead of orginal.
    */
    public function __clone()
    {
        $this->uri = clone $this->uri;
    }

    // --------------------------------------------------------------------

    /**
    * Set the route mapping ( Access must be public for HMVC Class. )
    *
    * This function determines what should be served based on the URI request,
    * as well as any "routes" that have been set in the routing config file.
    *
    * @access    public
    * @version   0.1
    * @return    void
    */
    public function _setRouting()
    {
        global $config;

        if( ! isset($_SERVER['HMVC_REQUEST']))    // GET request valid for standart router requests not HMVC.
        {
            // Are query strings enabled in the config file?
            // If so, we're done since segment based URIs are not used with query strings.
            
            $dt = $config['directory_trigger'];
            $ct = $config['controller_trigger'];
            $mt = $config['function_trigger'];

            if ($config['enable_query_strings'] === true AND isset($_GET[$ct]) AND isset($_GET[$dt]))
            {
                $this->setDirectory(trim($this->uri->_filterUri($_GET[$dt])));
                $this->setClass(trim($this->uri->_filterUri($_GET[$ct])));

                if (isset($_GET[$mt]))
                {
                    $this->setMethod(trim($this->uri->_filterUri($_GET[$mt])));
                }

                return;
            }
        }

        // Set the default controller so we can display it in the event
        // the URI doesn't correlated to a valid controller.
        
        $this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? false : strtolower($this->routes['default_controller']);

        $this->uri->_fetchUriString(); // Detect the complete URI string

        if ($this->uri->getUriString() == '')       // Is there a URI string? 
        {                                           // If not, the default controller specified in the "routes" file will be shown.
            if ($this->default_controller === false)
            {
                $this->response['404'] = 'Unable to determine what should be displayed. A default route has not been specified in the routing file.';
                
                if(isset($_SERVER['HMVC_REQUEST'])) // HMVC Connection
                {
                    return false; // Returns to false if we have hmvc connection error.
                }

                $response = getComponentInstance('response');
                $reponse->showError($this->response['404'], 404);
            }

            // Turn the default route into an array.  We explode it in the event that
            // the controller is located in a subfolder
            
            $segments = $this->_validateRequest(explode('/', $this->default_controller));

            if(isset($_SERVER['HMVC_REQUEST']))
            {
                if($segments === false) // HMVC Connection
                {
                    return false; // Returns to false if we have hmvc connection error.
                }
            }

            $this->setClass($segments[1]);
            $this->setMethod($this->routes['index_method']);  // index

            $this->uri->rsegments = $segments;  // Assign the segments to the URI class

            // re-index the routed segments array so it starts with 1 rather than 0
            // $this->uri->_reindex_segments();

            logMe('debug', 'No URI present. Default controller set.');
            
            return;
        }

        // unset($this->routes['default_controller']); WHY WE UNSET DEFAULT CONTROLLER IS IT FOR HMVC ?????

        $this->uri->_removeUrlSuffix();   // Do we need to remove the URL suffix?
        $this->uri->_explodeSegments();   // Compile the segments into an array 
        $this->_parseRoutes();        // Parse any custom routing that may exist

        // Re-index the segment array so that it starts with 1 rather than 0
        // $this->uri->_reindex_segments();
    }

    // --------------------------------------------------------------------

    /**
    * Set the Route
    *
    * This function takes an array of URI segments as
    * input, and sets the current class/method
    *
    * @access   public
    * @param    array
    * @param    bool
    * @version  0.1
    * @version  0.2 Changed $segments[0] as $segments[1]  and
    *           $segments[1] as $segments[2]
    * @return   void
    */
    public function _setRequest($segments = array())
    {
        $segments = $this->_validateRequest($segments);
        
        if (count($segments) == 0)
        {
            return;
        }

        $this->setClass($segments[1]);

        if (isset($segments[2]))
        {
           $this->setMethod($segments[2]);  // A standard method request
        }
        else
        {
            $segments[2] = $this->routes['index_method'];   // This lets the "routed" segment array identify that the default
                                                            // index method is being used.
        }

        $this->uri->rsegments = $segments;  // Update our "routed" segment array to contain the segments.
                                            // identical to $this->uri->segments
                                            // Note: If there is no custom routing, this array will be         
    }

    // --------------------------------------------------------------------

    /**
    * Validates the supplied segments.  Attempts to determine the path to
    * the controller.
    *
    * $segments[0] = module
    * $segments[1] = controller
    *
    *       0      1           2
    * module / controller /  method  /
    *
    * @access   public
    * @param    array
    * @version  Changed segments[0] as segments[1]
    *           added directory set to segments[0]
    * @return   array
    */
    public function _validateRequest($segments)
    {   
        if( ! isset($segments[0]) )
        { 
            return $segments;
        }
        
        // TASK OPERATIONS
        //----------------------------
        
        if(defined('STDIN') AND ! isset($_SERVER['HMVC_REQUEST']))  // Command Line Request
        { 
            array_unshift($segments, 'tasks');
        }

        // WEB SERVICE REQUEST
        //----------------------------

        if($this->uri->getExtension() != '' AND ! defined('STDIN'))
        {
            // $backup_directory = $this->controller_directory;  // Backup for task operations.
            // $backup_segments  = $segments;

            $this->controller_directory = $segments[1];
            unset($segments[1]);
            $segments = array_values($segments);
        }

        // SET DIRECTORY
        //----------------------------

        $this->setDirectory($segments[0]); // Set first segment as a module

        if( ! empty($segments[1]))
        {
            if (file_exists(PUBLIC_DIR .$this->fetchDirectory(). DS .$this->getControllerDirectory(). DS .$segments[1]. EXT))
            {   
                return $segments; 
            }
        }
        
        // WEB SERVICE REQUEST TASK ( Restore segments & controller dir for Cli mode )
        //----------------------------
        /*
        if(defined('STDIN')) // Web request backup for task operations.
        {
            $segments                   = $backup_segments;
            $this->controller_directory = $backup_directory;
        }
        */
        //----------------------------

        if(file_exists(PUBLIC_DIR .$this->fetchDirectory(). DS .$this->getControllerDirectory(). DS .$this->fetchDirectory(). EXT))
        {
            array_unshift($segments, $this->fetchDirectory());

            if( empty($segments[2]) )
            {
                $segments[2] = $this->routes['index_method'];
            }

            return $segments;
        }

        // HMVC 404
        //----------------------------
        
        if(isset($_SERVER['HMVC_REQUEST']))
        {
            $this->response['404'] = 'Request not found.';

            logMe('debug', 'Hmvc request not found.');

            return false;
        }

        // HTTP 404
        //----------------------------

        // If we've gotten this far it means that the URI does not correlate to a valid
        // controller class.  We will now see if there is an override
        
        if ( ! empty($this->routes['404_override']))
        {
            $x = explode('/', $this->routes['404_override']);

            $this->setDirectory($x[0]);
            $this->setClass($x[1]);
            $this->setMethod(isset($x[2]) ? $x[2] : 'index');

            return $x;
        }

        $error_page = (isset($segments[1])) ? $segments[0].'/'.$segments[1] : $segments[0];

        $response = getComponentInstance('response');
        $response->show404($error_page);
    }
               
    // --------------------------------------------------------------------
    
    /**
    * Parse Routes
    *
    * This function matches any routes that may exist in
    * the config/routes.php file against the URI to
    * determine if the class/method need to be remapped.
    *
    * @access    public
    * @return    void
    */
    public function _parseRoutes()
    { 
        // Do we even have any custom routing to deal with?
        // There is a default scaffolding trigger, so we'll look just for 1
        
        if (count($this->routes) == 1)
        {
            $this->_setRequest($this->uri->segments);
            return;
        }
        
        $uri = implode('/', $this->uri->segments);

        if (isset($this->routes[$uri]))  // Is there a literal match?  If so we're done
        { 
            $this->_setRequest(explode('/', $this->routes[$uri]));
            return;
        }

        foreach ($this->routes as $key => $val) // Loop through the route array looking for wild-cards
        {
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key)); // Convert wild-cards to RegEx

            if (preg_match('#^'.$key.'$#', $uri))  // Does the RegEx match?
            {
                if (strpos($val, '$') !== false AND strpos($key, '(') !== false)  // Do we have a back-reference ?
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }

                $this->_setRequest(explode('/', $val));
                return;
            }
        }
        
        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        
        $this->_setRequest($this->uri->segments);
    }

    // --------------------------------------------------------------------

    /**
    * Set the class name
    *
    * @access    public
    * @param     string
    * @return    void
    */
    public function setClass($class)
    {
        $this->class = $class;
    }

    // --------------------------------------------------------------------

    /**
    * Fetch the current class
    *
    * @access    public
    * @return    string
    */
    public function fetchClass()
    {
        return $this->class;
    }

    // --------------------------------------------------------------------

    /**
    *  Set the method name
    *
    * @access    public
    * @param     string
    * @return    void
    */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    // --------------------------------------------------------------------

    /**
    *  Fetch the current method
    *
    * @access    public
    * @return    string
    */
    public function fetchMethod()
    {
        if ($this->method == $this->fetchClass())
        {
            return $this->routes['index_method'];
        }

        return $this->method;
    }

    // --------------------------------------------------------------------

    /**
    *  Set the directory name
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function setDirectory($dir)
    {
        $this->directory = (string)$dir;
    }

    // --------------------------------------------------------------------

    /**
    * Fetch the directory (if any) that contains the requested controller class
    *
    * @access    public
    * @return    string
    */
    public function fetchDirectory()
    {
        return $this->directory;
    }

    // --------------------------------------------------------------------

    /**
     * Fetches the current controller directory name
     * 
     * @return string
     */
    public function getControllerDirectory()
    {
        return $this->controller_directory;
    }

    // --------------------------------------------------------------------

    /**
    * Fetch hmvc response.
    *
    * @access public
    * @return array
    */
    public function getResponse()
    {
        if(count($this->response) > 0)
        {
            return $this->response;
        }

        return false;
    }

}

// END Router Class

/* End of file Router.php */
/* Location: ./packages/router/releases/0.0.1/router.php */