<?php

/**
* Rest Request Class
* 
* Send rest request to your rest service
* controller.
* 
* @package       packages
* @subpackage    rest
* @category      request
* @link
*/

Class Rest {
    
    protected $service_request_uri;
    protected $service_name;

    public $data = array();  // database column keys & values *

    // ------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        if( ! isset(getInstance()->rest))
        {
            getInstance()->rest = $this; // Make available it in the controller $this->rest->method();
        }

        logMe('debug', 'Rest Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Set data for Rest CRUD operations
     * 
     * post.save 
     * post.insert
     * post.delete
     * post.put
     * post.replace
     * 
     * @param [type] $key [description]
     * @param [type] $val [description]
     */
    public function __set($key, $val)
    {
        $this->data[$key] = $val;
    }

    // ------------------------------------------------------------------------

    /**
     * POST Request
     * 
     * @param  string  $methodString method.name
     * @param  array $params         sending post query string data
     * @param  integer $ttl          cache expiration time
     * @return object
     */
    public function post($methodString, $params, $ttl = 0)
    {
        $hmvc = new Hmvc(); // create new hmvc request instance
        $hmvc->clear();     // clear object variables

        $hmvc->noLoop();    // open anti crash feature
        $hmvc->setRequest($request_uri, $ttl); // set request

        // Merge Query Data & Post Data

        $hmvc->setMethod('POST', array_merge(array('data' => $this->data), $params));    // set method
        
        // Reset Query data ( database column names which are set by rest query method )
        $this->data = array();

        return $hmvc->exec();   // return to hmvc object
    }

    // ------------------------------------------------------------------------
}

// END Rest Class

/* End of file rest.php */
/* Location: ./packages/rest/releases/0.0.1/rest.php */