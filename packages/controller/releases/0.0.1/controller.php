<?php

 /**
 * Controller Class.
 *
 * Main Controller class.
 *
 * @package       packages
 * @subpackage    controller     
 * @category      controllers
 * @link
 */

Class Controller {

    public static $instance;   // Controller instance
    public $_controllerAppMethods = array();  // Controller user defined methods. ( @private )
    public $config, $router, $uri, $output, $lingo; // Component instances
        
    // ------------------------------------------------------------------------

    /**
     * Closure function for 
     * construction
     * 
     * @param object $closure
     */
    public function __construct($constructClosure = '')       
    {   
        self::$instance = &$this;

        // Assign Core Libraries
        // ------------------------------------
        
        $this->config = getComponentInstance('config');
        $this->router = getComponentInstance('router');
        $this->uri    = getComponentInstance('uri');
        $this->output = getComponentInstance('output');
        $this->lingo  = getComponentInstance('lingo');
        
        $currentRoute = $this->router->fetchDirectory().'/'.$this->router->fetchClass().'/'.$this->router->fetchMethod();

        // Initialize to Autorun
        // ------------------------------------

        $autorun = getConfig('autorun');

        if(isset($autorun['controller']) AND count($autorun['controller']) > 0)
        {
            foreach($autorun['controller'] as $funcName)
            {
                call_user_func_array(Closure::bind($autorun['func'][$funcName], $this, get_class()), array());
            }
            
            logMe('debug', 'Autorun Closure Initialized');
        } 

        if(isset($autorun['routes'])) // Autorun for routes
        {
            foreach($autorun['routes'] as $route => $funcVal)
            {
                $uriRoute = trim($route, '/');
                if($currentRoute == $uriRoute AND count($funcVal) > 0)
                {
                    foreach($funcVal as $funcRouteName)
                    {
                        call_user_func_array(Closure::bind($autorun['func'][$funcRouteName], $this, get_class()), array());
                    }
                }
            }
        }

        // Run Construct Method
        // ------------------------------------

        if (is_callable($constructClosure))
        {
            call_user_func_array(Closure::bind($constructClosure, $this, get_class()), array());
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Create the controller methods.
     * 
     * @param  string $methodName  
     * @param  closure $methodCallable
     * @return void
     */
    public function func($methodName, $methodCallable)
    {
        $method = strtolower($methodName);

        if($method == 'view' OR $method == 'tpl')
        {
            throw new Exception("view() and tpl() are reserved methods for view component. 
                Please use different names. Reserved Controller Methods : <pre>- view()\n- tpl()</pre>");
        }

        if ( ! is_callable($methodCallable))
        {
            throw new InvalidArgumentException('Model '.get_class().' error: Second param must be callable.');
        }
        
        $this->_controllerAppMethods[$method] = Closure::bind($methodCallable, $this, get_class());
    }

    // ------------------------------------------------------------------------

    /**
     * Set controller method
     * 
     * @param  string $methodName method
     * @param  array $args  closure function arguments
     * @return void
     */
    public function __call($methodName, $args)
    {
        $method = strtolower($methodName);

        if (isset($this->_controllerAppMethods[$method]))
        {
            return call_user_func_array($this->_controllerAppMethods[$method], $args);
        }

        throw new Exception('Controller '.get_class().' error: There is no method "'.$method.'()" to call.');
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch view file from /view folder
     * 
     * @param  string  $filename           filename
     * @param  mixed  $data_or_no_include  Closure data, array data or boolean ( fetch as string )
     * @param  boolean $include            no include ( fetch as string )
     * @return string                      
     */
    public function view($filename, $data_or_no_include = null, $include = true)
    {
        return getComponentInstance('view')->fetch(PUBLIC_DIR .getInstance()->router->fetchDirectory(). DS .'view'. DS, $filename, $data_or_no_include, $include);    
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch view file app / templates folder
     * 
     * @param  string  $filename           filename
     * @param  mixed  $data_or_no_include  Closure data, array data or boolean ( fetch as string )
     * @param  boolean $include            no include ( fetch as string )
     * @return string                      
     */
    public function tpl($filename, $data_or_no_include = null, $include = true)
    {
        return getComponentInstance('view')->fetch(APP .'templates'. DS, $filename, $data_or_no_include, $include);
    }

}

// END Controller Class

/* End of file controller.php */
/* Location: ./packages/controller/releases/0.1/controller.php */