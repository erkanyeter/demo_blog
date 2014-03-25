<?php

/**
 * View Class
 *
 * @package       packages
 * @subpackage    view
 * @category      views
 * @link
 */
Class View
{
    /**
     * Data Storage variables
     * 
     * @var array
     */
    private $_string = array(); // String type view variables
    private $_array  = array(); // Array type view variables
    private $_bool   = array(); // Array type view variables
    private $_object = array(); // Object type view variables

    public $logger;
    public $response;

    /**
     * Constructor
     */

    public function __construct()
    {
        global $c;
        $this->logger   = $c['Logger'];
        $this->router   = $c['Router'];
        $this->response = $c['Response'];

        $this->logger->debug('View Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Load view files.
     *
     * @access private
     * @param string $filename view name
     * @param mixed  $data view data
     * @param booelan $include fetch the file as string or include.
     * 
     * @return void | string
     */
    public function fetch($__vPath, $__vFilename, $__vData = null, $__vInclude = true)
    {
        global $c;

        $file_extension = substr($__vFilename, strrpos($__vFilename, '.')); // Detecet the file extension ( e.g. '.tpl' )
        $ext = (strpos($file_extension, '.') === 0) ? '' : EXT;

        if (class_exists('Controller')) {
            foreach (array_keys(get_object_vars($c['App']->instance)) as $key) { // This allows to using "$this" variable in all views files.
                $this->{$key} = $c['App']->instance->{$key}; // e.g. $this->config->getItem('myitem')
            }
        }

        $this->_isCallable($__vData);

        if (count($this->_string) > 0) {  // extract all view variables.
            extract($this->_string, EXTR_SKIP);
        }
        if (sizeof($this->_array) > 0) {
            extract($this->_array, EXTR_SKIP);
        }
        if (count($this->_object) > 0) {
            extract($this->_object, EXTR_SKIP);
        }
        if (count($this->_bool) > 0) {
            extract($this->_bool, EXTR_SKIP);
        }

        $this->logger->debug('View file loaded: ' . $__vPath . $__vFilename . $ext);

        ob_start();   // Please open short tags in your php.ini file. ( short_tag = On ).

        include_once $__vPath . $__vFilename . $ext;

        $output = ob_get_clean();

        if ($__vData === false || $__vInclude === false) {
            return $output;
        }
        $this->response->appendOutput($output);
        return;
    }

    // --------------------------------------------------------------------

    /**
     * Set variables
     * 
     * @param string $key view key data
     * @param mixed  $val mixed
     * 
     * @return void
     */
    public function set($key, $val)
    {
        if (is_string($val) AND strpos($val, '@') === 0 ) {
            global $c;
            $matches = explode('.', $val);
            $method  = trim($matches[0], '@');
            $uri     = $matches[1];
            $param   = (isset($matches[2])) ? $matches[2] : 0;
            $val     = $c['Hvc']->$method($uri, $param);
        }

        $val = $this->_isCallable($val);

        if (is_string($val) OR is_int($val)) {
            $this->_string[$key] = $val;
            return;
        }
        if (is_array($val)) {
            if (count($val) == 0) {
                $this->_array[$key] = array();
                return;
            }
            foreach ($val as $array_key => $value) {
                $this->_array[$key][$array_key] = $value;
            }
            return;
        }
        if (is_object($val)) {
            $this->_object[$key] = $val;
            return;
        }
        if (is_bool($val)) {
            $this->_bool[$key] = $val;
            return;
        }
        $this->_string[$key] = (string) $val;
        return;
    }

    // --------------------------------------------------------------------

    /**
     * Use a view scheme
     * 
     * @param  string $scheme scheme name
     * @return void
     */
    public function getScheme($schemeName = 'default')
    {
        $schemes = getConfig('scheme');
        if (isset($schemes[$schemeName]) AND is_callable($schemes[$schemeName])) {
            call_user_func_array(Closure::bind($schemes[$schemeName], $this, get_class()), array());
        }
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Check $this->set() value is Closure ?
     *
     * @access private
     * @param  string | closure  $val
     * @return mixed
     */
    private function _isCallable($val)
    {
        if (is_callable($val)) { // Is callable function ?
            $func = Closure::bind($val, $this, get_class());
            return $func();
        }
        return $val;
    }

    // ------------------------------------------------------------------------

    /**
     * Load view file from /view folder
     * 
     * @param  string  $filename           filename
     * @param  mixed  $data_or_no_include  Closure data, array data or boolean ( fetch as string )
     * @param  boolean $include            no include ( fetch as string )
     * @return string                      
     */
    public function get($filename, $data_or_no_include = null, $include = true)
    {
        $folder = PUBLIC_DIR;
        if (isset($_SERVER['HVC_REQUEST']) AND $_SERVER['HVC_REQUEST'] == true) {
            $folder = PRIVATE_DIR;
        }
        return $this->fetch($folder . $this->router->fetchDirectory() . DS . 'view' . DS, $filename, $data_or_no_include, $include);
    }

    // ------------------------------------------------------------------------

    /**
     * Load view file app / templates folder
     * 
     * @param  string  $filename           filename
     * @param  mixed  $data_or_no_include  Closure data, array data or boolean ( fetch as string )
     * @param  boolean $include            no include ( fetch as string )
     * @return string                      
     */
    public function getTpl($filename, $data_or_no_include = null, $include = true)
    {
        return $this->fetch(APP . 'templates' . DS, $filename, $data_or_no_include, $include);
    }

}

// END View Class

/* End of file View.php */
/* Location: ./packages/view/releases/0.0.1/view.php */