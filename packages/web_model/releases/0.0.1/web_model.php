<?php

/**
* Web Model
* 
* Send model based hmvc request to 
* your "web_models" directory.
* 
* @package       packages
* @subpackage    web_model
* @category      web_services
* @link
*/

Class Web_Model {
    
    public $modelName = '';

    /**
     * Constructor
     */
    public function __construct($modelName, $method = 'POST')
    {
        $modelName  = strtolower($modelName);

        eval('Class WebModel_'.$modelName.' extends Web_Model_Request {
            public $modelName = "'.$modelName.'";
            public $data = array();   // request data
            public function __set($k, $v){
                if( $k != $this->modelName AND $k != "data") {  // Only data variable allowed !
                    throw new Exception(sprintf("Only \"data\" variable allowed in web model class.
                            <pre>\$this->%s->data = \'\';</pre>", \''.$modelName.'\'));
                }
                $this->$k = $v;
            }
        }');

        $class = 'WebModel_'.$modelName;

        getInstance()->{$modelName} = new $class; // Store model to controller

        logMe('debug', 'Web Model Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Parse outputs and do validation
     * then set error & and values
     * 
     * to Form object
     * 
     * @return boolean
     */
    public function isValid()
    {

    }

}

/**
 * Web model request object
 * This class do hmvc request 
 * using web class.
 */
Class Web_Model_Request
{
    // ------------------------------------------------------------------------

    public function __call($method, $arguments = array())
    {
        $web_model = getConfig('web_model');

        $web  = new Web($web_model['service_folder']);
        $data = $this->data; // Set request data

        $output = $web->query('post', '/users/create_one.json',function() use($data){
            $this->data = $data;
        });

        $this->clear(); // Reset Variables


        // if( ! method_exists($this->users, $method))  // Call the Validator object methods
        // {   
        //     $resultClass   = 'Web_Results_'.ucfirst($this->uri_extension);
        //     $resultObject  = new $resultClass($output); // Send raw output to result object.

        //     return call_user_func_array(array($resultObject, $method), $arguments);
        // }
        
        return $output;
    }

    public function clear()
    {
        $this->modelName = '';
        $this->data      = array();
    }
}

// END Web Class

/* End of file web_model.php */
/* Location: ./packages/web/releases/0.0.1/web_model.php */