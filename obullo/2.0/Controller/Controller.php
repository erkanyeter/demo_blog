<?php
require OBULLO .$version. DS .'Obullo'. DS .'Common'. EXT;

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
Class Controller
{
    public static $instance;              // Controller instance
    public $controllerMethods = array();  // Controller user defined methods. ( @private )
    public $publicMethods     = array();  // Controller user defined methods. ( @private )
    public $config, $uri, $router, $translator, $logger;  // Default packages

    // ------------------------------------------------------------------------

    /**
     * Closure function for 
     * construction
     * 
     * @param null $closure object or null
     */
    public function __construct($closure = null)
    {
        global $c;
        self::$instance = &$this;

        // Assign Default Loaded Packages
        // NOTICE:
        $this->config     = &$c['config'];         // If we don't use assign by reference this will cause some errors in Hvc.
        $this->uri        = &$c['uri'];            // The bug is insteresting, when we work with multiple page not found requests
        $this->router     = &$c['router'];         // The objects of getInstance() keep the last instances of the last request.
        $this->translator = &$c['translator'];     // that means the instance don't do the reset. Keep in your mind we need use pass by reference for variables.
        $this->logger     = &$c['logger'];         // @see http://www.php.net/manual/en/language.references.whatdo.php
        
        // Run Construct Method
        // ------------------------------------

        if (is_callable($closure)) {
            call_user_func_array(Closure::bind($closure, $this, get_class()), array());
        }
    }

    // ------------------------------------------------------------------------

    /**
     * We prevent custom variables
     *
     * this is not allowed $this->user_variable = 'this is disgusting'
     * in controller
     * 
     * @param string $key string
     * @param string $val mixed
     *
     * @return void 
     */
    public function __set($key, $val)  // Custom variables is not allowed !!! 
    {
        if ( ! is_object($val) AND $key != 'controllerAppMethods' AND $key != 'publicMethods') {
            throw new Exception('Manually storing variables into Controller is not allowed');
        }
        $this->{$key} = $val; // store only app classes & packages 
                              // and object types
    }

    // ------------------------------------------------------------------------

    /**
     * Create the controller methods.
     * 
     * @param string  $methodName     method
     * @param closure $methodCallable callable function
     * 
     * @return void
     */
    
    public function func($methodName, $methodCallable)
    {
        $method = strtolower($methodName);
        $hooks  = explode('.', $methodName);

        $method = $hooks[0];
        if (isset($hooks[1])) {  // Run Controler Hooks
            unset($hooks[0]);
            foreach ($hooks as $class) {
                new $class;
            }
        }
        //-----------------------------------------------------
        // "One Public Method Per Controller" Rule
        //-----------------------------------------------------
        // if it is not a private method check the "One Public Method Per Controller" rule

        if (strncmp($methodName, '_', 1) !== 0 AND strpos($methodName, 'callback_') !== 0) {
            $this->publicMethods[$method] = $methodName;
            if (sizeof($this->publicMethods) > 1) {
                throw new Exception('Just one public method allowed, framework has a principle "One Public Method Per Controller". If you want to add private methods use underscore ( _methodname ). <pre>$c->func(\'_methodname\', function(){});</pre>');
            }
        }
        if ( ! is_callable($methodCallable)) {
            throw new InvalidArgumentException('Controller error: Second param must be callable.');
        }
        $this->controllerMethods[$method] = Closure::bind($methodCallable, $this, get_class());
    }

    // ------------------------------------------------------------------------

    /**
     * Call the controller method
     * 
     * @param string $method method
     * @param array  $args   closure function arguments
     * 
     * @return void
     */
    public function __call($method, $args)
    {
        if (isset($this->controllerMethods[$method])) {
            return call_user_func_array($this->controllerMethods[$method], $args);
        }
        throw new Exception(get_class() . ' error: There is no method "' . $method . '()" to call.');
    }

}

// END Controller Class

/* End of file controller.php */
/* Location: ./packages/controller/releases/0.1/controller.php */