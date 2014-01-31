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

    public static $instance;                        // Controller instance
    public $_controllerAppMethods       = array();  // Controller user defined methods. ( @private )
    public $_controllerAppPublicMethods = array();  // Controller user defined methods. ( @private )

    public $config, $router, $uri, $translator, $response; // Component instances
        
    // ------------------------------------------------------------------------

    /**
     * Closure function for 
     * construction
     * 
     * @param object $closure
     */
    public function __construct($closure, $autorun = true)       
    {   
        self::$instance = &$this;

        // Assign Core Libraries
        // ------------------------------------
        
        $this->config     = getComponentInstance('config');
        $this->router     = getComponentInstance('router');
        $this->uri        = getComponentInstance('uri');
        $this->translator = getComponentInstance('translator');
        $this->response   = getComponentInstance('response');

        // Run Construct Method
        // ------------------------------------

        if (is_callable($closure))
        {
            call_user_func_array(Closure::bind($closure, $this, get_class()), array());
        }
    }

    // ------------------------------------------------------------------------

    /**
     * We prevent custom variables
     *
     * this is not allowed $this->user_variable 
     * 
     * @param string $key
     * @param string $val
     */
    public function __set($key, $val)  // Custom variables is not allowed !!! 
    {
        if( ! is_object($val) AND $key != '_controllerAppMethods' AND $key != '_controllerAppPublicMethods')
        {
            throw new Exception('Manually storing variables into Controller is not allowed');
        }

        $this->{$key} = $val; // store only application classes & packages
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

        //-----------------------------------------------------
        // "One Public Method Per Controller" Rule
        //-----------------------------------------------------
        
        if(strncmp($methodName, '_', 1) !== 0) // if it is not a private method control the "One Public Method Per Controller" rule
        {
            $this->_controllerAppPublicMethods[$method] = $methodName;

            if(sizeof($this->_controllerAppPublicMethods) > 1)
            {
                throw new Exception('Just one public method allowed, framework has a principle "One Public Method Per Controller". If you want to add private methods use underscore ( _methodname ). <pre>$c->func(\'_methodname\', function(){});</pre>');
            }
        }

        if ( ! is_callable($methodCallable))
        {
            throw new InvalidArgumentException('Controller error: Second param must be callable.');
        }
        
        $this->_controllerAppMethods[$method] = Closure::bind($methodCallable, $this, get_class());
    }

    // ------------------------------------------------------------------------

    /**
     * Call the controller method
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

        throw new Exception(get_class().' error: There is no method "'.$method.'()" to call.');
    }

}

// END Controller Class

/* End of file controller.php */
/* Location: ./packages/controller/releases/0.1/controller.php */