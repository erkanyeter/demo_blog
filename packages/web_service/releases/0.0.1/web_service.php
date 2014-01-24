<?php

 /**
 * Web Service Controller Class.
 *
 * @package       packages
 * @subpackage    controller     
 * @category      controllers
 * @link
 */

Class Web_Service extends Controller {

    public $_webServiceAppMethods       = array();  // Controller user defined methods. ( @private )
    public $_webServiceAppPublicMethods = array();  // Controller user defined methods. ( @private )

    /**
     * Constructor for web service
     *
     * @param string $visibility ( public, protected ) default is public
     * @param object $closure
     */
    public function __construct($visibility = '', $closure = '')
    {
        if(is_callable($visibility))  // check is web service closure data is visible.
        {
            $closure    = $visibility;
            $visibility = 'public';
        } 

        switch ($visibility) {
            case 'public':
                
                break;
            
            case 'protected':
                
                break;

            default:
                throw new Exception('There is no access property called '.$visibility.'.');
                break;
        }

        parent::__construct($closure);
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
        if( ! is_object($val) AND $key != '_webServiceAppMethods' AND $key != '_webServiceAppPublicMethods')
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
            $this->_webServiceAppPublicMethods[$method] = $methodName;

            if(sizeof($this->_webServiceAppPublicMethods) > 1)
            {
                throw new Exception('Just one public method allowed, framework has a principle "One Public Method Per Controller". If you want to add private methods use underscore ( _methodname ). <pre>$c->func(\'_methodname\', function(){});</pre>');
            }
        }

        if ( ! is_callable($methodCallable))
        {
            throw new InvalidArgumentException('Controller error: Second param must be callable.');
        }
        
        $this->_webServiceAppMethods[$method] = Closure::bind($methodCallable, $this, get_class());
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

        if (isset($this->_webServiceAppMethods[$method]))
        {
            return call_user_func_array($this->_webServiceAppMethods[$method], $args);
        }

        throw new Exception('Controller '.get_class().' error: There is no method "'.$method.'()" to call.');
    }

}