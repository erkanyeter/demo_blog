<?php

namespace Lvc;

/*
 * Lvc Class - Layered View Controller
 *
 * Copyright (c) 2009 - 2014 Ersin Guvenc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Lvc Class
 *
 * Obullo core library.
 * 
 * @category  Lvc
 * @package   Lvc
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/lvc
 */
Class Lvc
{
    // Controller Object
    public $global = null;     // Global instance of the controller object we need to clone it.
    public $config = array();  // Lvc configuration

    // Request, Response, Reset
    public $query_string = '';
    public $response;   // response object
    public $responseData = '';
    public $request_method = 'GET';
    public $process_done = false;

    // Objects
    public $uri    = null;
    public $router = null;
    public $logger = null;

    // Cache and Connection
    public $connection = true;
    protected $conn_string = '';       // Unique Lvc connection string that we need to convert it to conn_id.
    protected static $cid  = array();  // Static Lvc Connection ids. DO NOT CLEAR IT !!!
    protected $lvc_uri;

    const KEY = 'Lvc:';                // Lvc key prefix
    public static $start_time = '';    // benchmark start time

    /**
     * Reset all variables for multiple
     * HVC requests.
     *
     * @return   void
     */
    public function clear()
    {
        $this->global = null;     // Global instance of the controller object
        $this->request_method = 'GET';
        $this->process_done   = false;
        $this->uri    = null;
        $this->router = null;

        $this->connection  = true;
        $this->response->clear();

        $GLOBALS['_SERVER_BACKUP']  = array();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;

        $this->config = $c['config']['lvc'];   // Get lvc configuration

        $c['translator']->load('lvc');         // Load translate file

        $this->response = $c['response'];
        $this->logger   = $c['logger'];

        $this->logger->debug('Lvc Class Initialized');
    }

    /**
     * Prepare HVC Request (Set the URI String).
     * 
     * @param string $uriString uri
     * 
     * @return    void
     */
    public function setRequestUrl($uriString = '')
    {
        global $c;

        $uriString = trim($uriString, '/');

        // ----------- Visibility -----------------

        $type = 'public';
        $uriString = trim($uriString, '/');

        if (strpos($uriString, 'private/') === 0) { // Set the visibility of lvc request
            $type = 'private';
            $uriString = substr($uriString, 8);
        }

        if (strpos($uriString, 'public/') === 0) { // Set the visibility of lvc request
            $type = 'public';
            $uriString = substr($uriString, 6);
        }

        //-------- Backup $_SERVER ---------//

        $GLOBALS['_SERVER_BACKUP']  = $_SERVER; // Used in Http/Get class

        unset($_SERVER['HTTP_ACCEPT']);    // Don't touch global server items 
        unset($_SERVER['REQUEST_METHOD']);

        $_SERVER['LVC_REQUEST']      = true;   // Set Hvc Headers
        $_SERVER['LVC_REQUEST_TYPE'] = $type;  // "public" or "private

        $this->setConnString($uriString);

        // don't clone Controller::$instance, we just do backup.
        //----------------------------------------------
        
        $this->global = \Controller::$instance;     // We need create backup $this object of main controller

        // becuse of it will change when HVC process is done.
        //----------------------------------------------

        $uri    = $c['uri'];
        $router = $c['router'];

        // Clone Objects
        // -----------------------------------------

        $this->uri    = clone $uri;         // Create copy of original Uri class.
        $this->router = clone $router;      // Create copy of original Router class.

        // Clear
        // -----------------------------------------

        $uri->clear();           // Reset uri objects we will reuse it for lvc
        $router->clear();        // Reset router objects we will reuse it for lvc

        // Set Uri String to Uri Object
        //----------------------------------------------

        if (strpos($uriString, '?') > 0) {
            $uri_part = explode('?', urldecode($uriString));  // support any possible url encode operation
            $this->query_string = $uri_part[1];     // .json?id=2

            $uri->setUriString($uri_part[0], false); // false = null filter
        } else {
            $uri->setUriString($uriString);
        }

        // Set uri string to $_SERVER GLOBAL
        //----------------------------------------------

        $_SERVER['LVC_REQUEST_URI'] = $uriString;

        $this->connection = $router->setRouting(); // Returns false if we have lvc connection error.
    }

    /**
     * Set Lvc Request Method
     *
     * @param string $method lvc method
     * @param array  $data   params
     * 
     * @return   void
     */
    public function setMethod($method = 'GET', $data = array())
    {
        if (empty($data)) {
            $data = array();
        }
        $method = $this->request_method = strtoupper($method);

        $this->setConnString($method);        // Set Unique connection string foreach HVC requests
        $this->setConnString(serialize($data));

        if ($this->query_string != '') {
            $querStringParams = $this->parseQuery($this->query_string);
            if (sizeof($data) > 0) {
                $data = array_merge($querStringParams, $data);
            }
        }
        foreach ($data as $key => $val) {
            switch ($method) {
            case ($method == 'GET' OR $method == 'DELETE'):
                $_GET[$key] = is_string($val) ? urldecode($val) : $val;
                break;
            default:
                $_POST[$key] = $val;
                break;
            }
            $_REQUEST[$key] = $val;
        }
        $_SERVER['REQUEST_METHOD'] = $method;  // Set request method ..
    }

    /**
     * Parse Url if there is any possible query string like this
     *
     * $this->lvc->get('welcome/test/index?foo=im_foo&bar=im_bar');
     *
     * @param string $query_string string
     * 
     * @return array  $segments
     */
    public function parseQuery($query_string = '')
    {
        if ($query_string == '') {
            return array();
        }
        parse_str(html_entity_decode($query_string), $segments);
        return $segments;
    }

    /**
     * Lvc GET Request
     * 
     * @param string  $uri        uri string
     * @param array   $data       get data
     * @param integer $expiration cache ttl
     * 
     * @return string
     */
    public function get($uri, $data = array(), $expiration = null)
    {
        return $this->request('GET', $uri, $data, $expiration);
    }

    /**
     * Lvc POST Request
     * 
     * @param string  $uri        uri string
     * @param array   $data       post data
     * @param integer $expiration cache ttl
     * 
     * @return string
     */
    public function post($uri, $data = array(), $expiration = null)
    {
        return $this->request('POST', $uri, $data, $expiration);
    }

    /**
     * Lvc PUT ( Update ) Request
     * 
     * @param string $uri  uri string
     * @param array  $data post data
     * 
     * @return string
     */
    public function put($uri, $data = array())
    {
        return $this->request('PUT', $uri, $data);
    }

    /**
     * Alias of PUT
     * 
     * @param string $uri  uri string
     * @param array  $data post data
     * 
     * @return string
     */
    public function update($uri, $data = array())
    {
        return $this->put($uri, $data);
    }   

    /**
     * Lvc Delete Request
     * 
     * @param string $uri  uri string
     * @param array  $data post data
     * 
     * @return string
     */
    public function delete($uri, $data = array())
    {
        return $this->request('DELETE', $uri, $data);
    }

    /**
     * Get visibility of request ( Private / Public / Private_View )
     * 
     * @return string
     */
    public function getVisibility()
    {
        return (isset($_SERVER['LVC_REQUEST_TYPE'])) ? $_SERVER['LVC_REQUEST_TYPE'] : 'public';
    }

    /**
     * Send Request
     * 
     * @param string  $method     request method
     * @param string  $uri        uri string
     * @param array   $data       request data
     * @param integer $expiration cache ttl
     * 
     * @return string
     */
    public function request($method, $uri, $data = array(), $expiration = null)
    {
        if ($expiration === true) {  // delete cache before the request
            $this->deleteCache();
        }
        if (is_numeric($data)) { // set expiration as second param if data not provided
            $expiration = $data;
            $data = array();
        }
        $this->clear(); // clear lvc variables
        $this->setRequestUrl($uri, $expiration);
        $this->setMethod($method, $data);

        $vsb = $this->getVisibility();
        $rsp = $this->exec($expiration); // execute the process

        $errorHeader = '<div style="white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;
  background:#fff;border:1px solid #ddd;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#069586;font-size:12px;"><span style="font-weight:bold;">';
        $errorFooter = '</div>';

        $lvc_error = $errorHeader .'</span><span style="font-weight:bold;">Private lvc response must be array and contain at least one of the following keys. ( "private/views" route is excluded ).</span><pre style="border:none;">
$r = array(
    \'success\' => integer     // optional
    \'message\' => string,     // optional
    \'errors\'  => array(),    // optional
    \'results\' => array(),    // optional
    \'e\' => $e->getMessage(), // optional
)

echo json_encode($r); // required

<b>Actual response:</b> '.$rsp.'
</pre>' . $errorFooter;
        $lvc_view_error = $errorHeader . '</span><span style="font-weight:bold;">View Controller ( ' . $this->getUri() . ' ) method must echo a string, should not be empty.</span><pre style="border:none;">
echo $this->view->get(
    \'header\',
    function () {
    },
    false  // required
);</pre>' . $errorFooter;

        $isXmlHttp = ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;

        if (is_string($rsp) AND strpos($rsp, '404') === 0) {  // 404 support
            if ($isXmlHttp) {  // ajax support
                return array(
                    'success' => 0,
                    'message' => translate('e_404'),
                    'errors' => array()
                );
            }
            echo ($errorHeader . $rsp . $errorFooter);
            return;
        }
        //------------ Private Request Header -------------//

        if ($vsb == 'private') {  // Private Request
            
            if (strpos(trim($uri, '/'), 'private/views') === 0) { // if request goes to view folder don't check the format
                if ( ! is_string($rsp) OR empty($rsp)) {
                    echo $lvc_view_error;
                    return;
                }
                return $rsp;
            }

            $rsp = json_decode($rsp, true); // Decode json to array

            if ( ! is_array($rsp)) { // If success not exists !
                if ($isXmlHttp) {
                    return array(
                        'success' => 0,
                        'message' => $lvc_error,
                        'errors' => array()
                    );
                }
                echo ($lvc_error);
                return;
            }
            //--------- Check the Private Response Format -----------//

            $key_errors = array_map(
                function ($val) {
                    return in_array($val, array('success','message','errors','results','e')); // Get the keys of lvc result array
                }, array_keys($rsp)
            );
            if (in_array(false, $key_errors, true)) {   // throws an exception
                if ($isXmlHttp) {
                    return array(
                        'success' => 0,
                        'message' => $lvc_error,
                        'errors' => array()
                    );
                }
                echo ($lvc_error);
                return;
            }
            // Show exceptional message to developers if environment not LIVE.
            
            if (isset($rsp['success']) AND $rsp['success'] == false AND (isset($rsp['e']) AND ! empty($rsp['e'])) AND (ENV == 'local' OR ENV == 'test')) { 
                $rsp['message'] = $rsp['e'];
            }
        }

        //------------ Private Request Header End -------------//

        if (isset($rsp['results']) AND is_array($rsp['results'])) {  // Automatically add count of the results.
            $rsp['count'] = count($rsp['results']);
        }
        if ($expiration === false) {  // delete cache after the request
            $this->deleteCache();
        }
        return $rsp;
    }

    /**
     * Exec Lvc Request
     * 
     * @param integer $expiration cache ttl
     * 
     * @return string
     */
    public function exec($expiration = null)
    {
        global $c;

        $uri    = $c['uri'];
        $router = $c['router'];
        $logger = $c['logger'];

        static $storage = array();      // store "$c " variables ( called controllers )

        $KEY = $this->getKey();   // Get Lvc Key
        $start = microtime(true); // Start the Query Timer 

        // ----------------- Static Php Cache -------------------//

        if (isset(self::$cid[$KEY])) {      // Cache the multiple Lvc requests in the same controller. 
                                            // This cache type not related with Cache package.
            $response = $this->getResponseData();
            $logger->debug('$_LVC: '.$this->getKey(), array('time' => number_format(microtime(true) - $start, 4), 'key' => $KEY, 'output' => '<br /><div style="float:left;">'.preg_replace('/[\r\n\t]+/', '', $response).'</div><div style="clear:both;"></div>'));
            $this->reset();
            return $response;    // This is native system cache !
        }

        self::$cid[$KEY] = $KEY;    // store connection id.

        // ----------------- Memory Cache -------------------//

        if ($this->config['caching']) {
            $response = $c['cache']->get($KEY);
            if ( ! empty($response)) {              // If cache exists return to cached string.
                $logger->debug('$_LVC_CACHED: '.$uri->getUriString(), array('time' => number_format(microtime(true) - $start, 4), 'key' => $KEY, 'output' => '<br /><div style="float:left;">'.preg_replace('/[\r\n\t]+/', '', $response).'</div><div style="clear:both;"></div>'));
                $this->reset();
                return base64_decode($response);    // encoding for specialchars
            }
        }

        // ----------------- Route is Valid -------------------//

        if ($this->response->getError() != '') {  // If router dispatch fail ?
            $this->reset();
            return $this->response->getError();
        }

        //----------------------------------------------------------------------------
        //  Create an uniq Lvc Uri
        //  A Lvc uri must be unique otherwise
        //  may collission with standart uri, also we need it for caching feature.
        //  --------------------------------------------------------------------------

        $uri->setUriString(rtrim($uri->getUriString(), '/') . '/' .$KEY); // Create an uniq Lvc Uri with md5 hash
        //  --------------------------------------------------------------------------

        $folder = PUBLIC_DIR;
        if ($this->getVisibility() == 'private') { // Send "private" requests to private folder
            $folder = PRIVATE_DIR;
        }

        $this->lvc_uri = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = $folder . $router->fetchDirectory() . DS . 'controller' . DS . $router->fetchClass() . EXT;

        // --------- Check class is exists in the storage ----------- //

        if (isset($storage[$this->lvc_uri])) {    // Don't allow multiple call for same class.
            $app = $storage[$this->lvc_uri];      // Get stored class.
        } else {
            include $controller;        // Call the controller.
        }

        // --------- End storage exists ----------- //
        
        // $app variable available here !

        if ( ! isset($c['request']->global)) { // ** Let's create new request object for globals

            // create a global variable
            // keep all original objects in it.
            // e.g. $this->request->global->uri->getUriString();
            // ** Store global "Uri" and "Router" objects to make available them in sub layers
            //---------------------------------------------------------------------------

            $c['request']->global = new stdClass;      // Create an empty class called "global"
            $c['request']->global->uri    = $this->uri;        // Let's assign the global uri object
            $c['request']->global->router = $this->router;     // Let's assign the global uri object
        }

        // End store global variables

        if (strncmp($router->fetchMethod(), '_', 1) == 0 
            OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
        ) {
            $this->reset();
            return $this->response->show404($this->lvc_uri, false);
        }

        // Get application methods
        //----------------------------

        $storedMethods = array_keys($app->controllerMethods);

        //----------------------------
        // Check method exist or not
        //----------------------------

        if ( ! in_array(strtolower($router->fetchMethod()), $storedMethods)) {
            $this->reset();
            return $this->response->show404($this->lvc_uri, false);
        }

        // Slice Arguments
        //----------------------------

        $arguments = array_slice($uri->rsegments, 2);

        //----------------------------

        ob_start(); // Start the output buffer.

        call_user_func_array(array($app, $router->fetchMethod()), $arguments);

        $response = ob_get_contents(); // Get the contents of the output buffer
        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering

        $this->reset();

        // Store classes to $storage container
        //--------------------------------------
        
        $storage[$this->lvc_uri] = $app; // Store class names to storage. We fetch it if its available in storage.

        // Write to Cache
        //--------------------------------------

        if (is_numeric($expiration) AND $this->config['caching']) {
            $c['cache']->set($KEY, base64_encode($response), (int)$expiration);
        }
        $logger->debug('$_LVC: '.$this->getUri(), array('time' => number_format(microtime(true) - $start, 4), 'key' => $KEY, 'output' => '<br /><div style="float:left;">'.preg_replace('/[\r\n\t]+/', '', $response).'</div><div style="clear:both;"></div>'));

        return $response;
    }

    /**
     * Reset router for mutiple lvc requests
     * or who want to close the lvc connection.
     *
     * @return   void
     */
    protected function reset()
    {
        global $c;
        
        if ( ! isset($_SERVER['LVC_REQUEST_URI'])) { // if no lvc header return to null;
            return;
        }
        // Assign global variables we copied before ..
        // --------------------------------------------------
        
        $_SERVER = array();     // Just reset server variable other wise  we don't use global variables in lvc in lvc.
        $_SERVER = $GLOBALS['_SERVER_BACKUP'];

        // Set original $this to controller instance that we backup before.
        // --------------------------------------------------

        if (is_object($this->global)) {  // fixed Lvc object type of integer bug.
            \Controller::$instance = $this->global;
        }
        $c['app']->uri    = $this->uri;        // restore back original objects
        $c['app']->router = $this->router;   

        $this->clear();  // reset all Lvc variables.
        $this->process_done = true;  

        // This means lvc process done without any errors.
        // If process_done == false we say to destruct method "reset the router" variables 
        // and return to original variables of the Framework's before we clone them.
    }

    /**
     * Set $_SERVER vars foreach lvc
     * requests.
     * 
     * @param string $key key
     * @param string $val val
     *
     * @return void
     */
    public function setServer($key, $val)
    {
        $_SERVER[$key] = $val;
        $this->setConnString($key .'-'. $val);
    }

    /**
     * Set response data
     * 
     * @param string $data string
     *
     * @return void
     */
    public function setResponseData($data)
    {
        $this->responseData = $data;
    }

    /**
     * Get none-decoded original Lvc
     * response.
     * 
     * @return string
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * Create HVC connection string next
     * we will convert it to connection id.
     *
     * @param mixed $id string
     *
     * @return void
     */
    protected function setConnString($id)
    {
        $this->conn_string .= $id;
    }

    /**
     * Returns to Lvc key.
     *
     * @return string
     */
    public function getKey()
    {
        return self::KEY . hash('md5', trim($this->conn_string));
    }

    /**
     * Delete cache for current uri.
     * 
     * @param string $key Lvc id
     * 
     * @return boolean
     */
    public function deleteCache($key = '')
    {
        global $c;
        if (empty($key)) {          // if key not provided the get current Lvc key
            $key = $this->getKey();
        }
        if ($c['cache']->keyExists($key)) {
            return $c['cache']->delete($key);
        }
        return false;
    }

    /**
     * Get last Lvc uri
     * 
     * @return string
     */
    public function getUri()
    {
        return $this->lvc_uri;
    }

    /**
     * Close Lvc Connection
     * 
     * If we have any possible Lvc exceptions
     * reset the router variables, complete to Lvc process
     * and return to original vars.
     * 
     * @return void
     */
    public function __destruct()
    {
        if ($this->process_done == false) {
            $this->reset();
            return;
        }
        $this->process_done = false;
    }

}

// END Lvc class

/* End of file Lvc.php */
/* Location: .Obullo/Lvc/Lvc.php */