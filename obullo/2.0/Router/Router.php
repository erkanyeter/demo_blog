<?php

namespace Obullo\Router;

/**
 * Router Class
 * 
 * @category  Router
 * @package   Router
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/router
 */
Class Router
{
    public $logger;
    public $uri;
    public $config;
    public $response = array();
    public $routes = array();
    public $error_routes = array();
    public $class = '';
    public $method = 'index';
    public $directory = '';
    public $uri_protocol = 'auto';
    public $default_controller;

    /**
     * Constructor
     * Runs the route mapping function.
     * 
     * @param array $routes route configuration array
     * 
     * @return void
     */
    public function __construct($routes = array())
    {
        global $c;
        $this->routes = $routes;
        $this->logger = $c['logger'];
        $this->uri    = $c['uri'];
        $this->config = $c['config'];
        $this->method = $this->routes['index_method'];

        $this->setRouting();
        $this->logger->debug('Router Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Clean all data for Hmvc.
     *
     * @return  void
     */
    public function clear()
    {
        global $c;
        $this->uri = $c['uri'];   // reset cloned URI object.
        $this->response = array();
        $this->error_routes = array(); // route config dont't reset "$this->routes" there cause some isset errors
        $this->class = '';
        $this->method = 'index';
        $this->directory = '';
        $this->uri_protocol = 'auto';
        $this->controller_directory = 'controller';
        $this->default_controller = '';
    }

    // --------------------------------------------------------------------

    /**
     * Clone URI object for HVC Requests,
     * When we use cloned uri
     * that means we tell to Router class when Clone word used in HVC Class
     * use the cloned URI object instead of orginal.
     *
     * @return void
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
     * @return void
     */
    public function setRouting()
    {
        global $c;
        if ( ! isset($_SERVER['HVC_REQUEST'])) {    // GET request valid for standart router requests not HMVC.

            // Are query strings enabled in the config file?
            // If so, we're done since segment based URIs are not used with query strings.

            $d_key = $c['config']['uri']['directory_trigger'];
            $c_key = $c['config']['uri']['controller_trigger'];
            $m_key = $c['config']['uri']['function_trigger'];

            if ($c['config']['uri']['query_strings'] === true AND isset($_GET[$c_key]) AND isset($_GET[$d_key])) {

                $this->setDirectory(trim($this->uri->filterUri($_GET[$d_key])));
                $this->setClass(trim($this->uri->filterUri($_GET[$c_key])));

                if (isset($_GET[$m_key])) {
                    $this->setMethod(trim($this->uri->filterUri($_GET[$m_key])));
                }
                return;
            }
        }
        // Set the default controller so we can display it in the event
        // the URI doesn't correlated to a valid controller.

        $this->default_controller = (!isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? false : strtolower($this->routes['default_controller']);

        $this->uri->fetchUriString(); // Detect the complete URI string

        if ($this->uri->getUriString() == '') {       // Is there a URI string?                                            // If not, the default controller specified in the "routes" file will be shown.
            if ($this->default_controller === false) {
                $this->response['404'] = 'Unable to determine what should be displayed. A default route has not been specified in the routing file.';

                if (isset($_SERVER['HVC_REQUEST'])) { // HMVC Connection
                    return false; // Returns to false if we have hmvc connection error.
                }
                $c['response']->showError($this->response['404'], 404);
            }

            // Turn the default route into an array.  We explode it in the event that
            // the controller is located in a subfolder

            $segments = $this->_validateRequest(explode('/', $this->default_controller));

            if (isset($_SERVER['HVC_REQUEST'])) {
                if ($segments === false) { // HVC Connection
                    return false; // Returns to false if we have hmvc connection error.
                }
            }

            $this->setClass($segments[1]);
            $this->uri->rsegments = $segments;  // Assign the segments to the URI class

            $this->logger->debug('No URI present. Default controller set.');
            return;
        }

        // unset($this->routes['default_controller']); WHY WE UNSET DEFAULT CONTROLLER IS IT FOR HMVC ?????

        $this->uri->removeUrlSuffix();   // Do we need to remove the URL suffix?
        $this->uri->explodeSegments();   // Compile the segments into an array 

        $this->_parseRoutes();        // Parse any custom routing that may exist
    }

    // --------------------------------------------------------------------

    /**
     * Set the Route
     *
     * This function takes an array of URI segments as
     * input, and sets the current class/method
     *
     * @param array $segments segments
     * 
     * @return void
     */
    private function _setRequest($segments = array())
    {
        $segments = $this->_validateRequest($segments);

        if (count($segments) == 0) {
            return;
        }
        $this->setClass($segments[1]);
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
     * @param  array $segments uri segments
     * 
     * @return array
     */
    public function _validateRequest($segments)
    {
        global $c;

        if ( ! isset($segments[0])) {
            return $segments;
        }

        // TASK OPERATIONS
        //----------------------------

        if (defined('STDIN') AND !isset($_SERVER['HVC_REQUEST'])) {  // Command Line Request
            array_unshift($segments, 'tasks');
        }

        $root = PUBLIC_DIR;
        if (isset($_SERVER['HVC_REQUEST_TYPE']) AND $_SERVER['HVC_REQUEST_TYPE'] == 'private') {
            $root = PRIVATE_DIR;
        }

        // SET DIRECTORY
        //----------------------------

        $this->setDirectory($segments[0]); // Set first segment as a module

        if (!empty($segments[1])) {
            if (file_exists($root . $this->fetchDirectory() . DS . 'controller' . DS . $segments[1] . EXT)) {
                return $segments;
            }
        }

        //----------------------------

        if (file_exists($root . $this->fetchDirectory() . DS . 'controller' . DS . $this->fetchDirectory() . EXT)) {
            array_unshift($segments, $this->fetchDirectory());
            return $segments;
        }

        // HTTP 404
        //----------------------------
        // If we've gotten this far it means that the URI does not correlate to a valid
        // controller class.  We will now see if there is an override

        if ( ! empty($this->routes['404_override'])) {
            $x = explode('/', $this->routes['404_override']);

            $this->setDirectory($x[0]);
            $this->setClass($x[1]);
            $this->setMethod(isset($x[2]) ? $x[2] : 'index');
            return $x;
        }

        $error_page = (isset($segments[1])) ? $segments[0] . '/' . $segments[1] : $segments[0];

        // HVC 404
        //----------------------------

        if (isset($_SERVER['HVC_REQUEST'])) {
            global $logger;
            $this->response['404'] = 'The page "' . $error_page . '" not found.';
            $logger->debug('Hvc request not found ( ' . $error_page . ' ).');
            return false;
        }
        $c['response']->show404($error_page);
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

        if (count($this->routes) == 1) {
            $this->_setRequest($this->uri->segments);
            return;
        }
        $uri = implode('/', $this->uri->segments);

        if (isset($this->routes[$uri])) {  // Is there a literal match?  If so we're done
            $this->_setRequest(explode('/', $this->routes[$uri]));
            return;
        }
        foreach ($this->routes as $key => $val) { // Loop through the route array looking for wild-cards
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key)); // Convert wild-cards to RegEx

            if (preg_match('#^' . $key . '$#', $uri)) {  // Does the RegEx match?
                if (strpos($val, '$') !== false AND strpos($key, '(') !== false) {  // Do we have a back-reference ?
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
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
        return $this->routes['index_method'];   // method  always must be index.
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
        $this->directory = $dir;
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
     * Fetch hmvc response.
     *
     * @access public
     * @return array
     */
    public function getResponse()
    {
        if (count($this->response) > 0) {
            return $this->response;
        }
        return false;
    }

}

// END Router.php File
/* End of file Router.php

/* Location: .Obullo/Router/Router.php */