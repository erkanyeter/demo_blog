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
     * POST Request
     * 
     * @param  string  $methodString method.name
     * @param  array $data           sending post data
     * @param  integer $ttl          cache expiration time
     * @return object
     */
    public function post($methodString, $data, $ttl = 0)
    {
        $hmvc = new Hmvc(); // create new hmvc request instance
        $hmvc->clear();     // clear object variables

        $hmvc->noLoop();    // open anti crash feature
        $hmvc->setRequest($request_uri, $ttl); // set request
        $hmvc->setMethod('POST', $data);    // set method
        
        return $hmvc->exec();   // return to hmvc object
    }

    // ------------------------------------------------------------------------



}

// END Rest Class

/* End of file rest.php */
/* Location: ./packages/rest/releases/0.0.1/rest.php */