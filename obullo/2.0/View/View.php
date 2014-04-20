<?php

namespace Obullo\View;

use Closure;

/**
 * View Class
 * 
 * @category  View
 * @package   View
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/view
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
    public $router;
    public $response;

    /**
     * Constructor
     */

    public function __construct()
    {
        global $c;

        $this->logger   = $c['logger'];
        $this->router   = $c['router'];
        $this->response = $c['response'];

        $this->logger->debug('View Class Initialized');
    }

    /**
     * Fetch view
     * 
     * @param string  $__vPath     full path
     * @param string  $__vFilename filename
     * @param string  $__vData     mixed data
     * @param boolean $__vInclude  fetch as string or include
     * 
     * @return void
     */
    public function fetch($__vPath, $__vFilename, $__vData = null, $__vInclude = true)
    {
        global $c;

        $file_extension = substr($__vFilename, strrpos($__vFilename, '.')); 	// Detect the file extension ( e.g. '.tpl' )
        $ext = (strpos($file_extension, '.') === 0) ? '' : EXT;

        if (class_exists('Controller')) {
            foreach (array_keys(get_object_vars($c['app']->instance)) as $key) {	 // This allows to using "$this" variable in all views files.
                $this->{$key} = $c['app']->instance->{$key}; 	// e.g. $this->config->getItem('myitem')
            }
        }
        if (is_callable($__vData)) {
            $this->bind($__vData);
        }
        extract($this->_string, EXTR_SKIP);
        extract($this->_array, EXTR_SKIP);
        extract($this->_object, EXTR_SKIP);
        extract($this->_bool, EXTR_SKIP);
        
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
        if (is_int($val)) {
            $this->_string[$key] = $val;
            return;
        }
        if (is_string($val)) {
            if (strpos($val, '@') === 0 ) {
                global $c;
                $matches = explode('.', $val);
                $method  = trim($matches[0], '@');
                $uri     = $matches[1];

                if ($uri == 'tpl') {
                    $val = $this->getTpl($matches[2], false);
                } else {
                    $param = (isset($matches[2])) ? $matches[2] : 0;
                    $val   = $c['hvc']->$method($uri, $param);
                }
            }
            $this->_string[$key] = $val;
            return;
        }

        $this->_array[$key] = array();

        if (is_array($val) AND count($val) > 0) {
            foreach ($val as $array_key => $value) {
                $this->_array[$key][$array_key] = $value;
            }
            return;
        }
        if (is_object($val)) {
            $this->_object[$key] = $val;
            $this->_array = array();
            return;
        }
        if (is_bool($val)) {
            $this->_bool[$key] = $val;
            $this->_array = array();
            return;
        }
        $this->_string[$key] = $val;
        $this->_array = array();
        return;
    }

    /**
     * Use a view scheme
     * 
     * @param string $name scheme name
     * 
     * @return void
     */
    public function getScheme($name = 'default')
    {
        global $c;
        $schemes = $c['config']->load('scheme');
        if (isset($schemes[$name]) AND is_callable($schemes[$name])) {
            $this->bind($schemes[$name]);
        }
        return $this;
    }

    /**
     * Run Closure
     *
     * @param mixed $val closure or string
     * 
     * @return mixed
     */
    public function bind($val)
    {
        $closure = Closure::bind($val, $this, get_class());
        return $closure();
    }

    /**
     * Load view file from /view folder
     * 
     * @param string  $filename           filename
     * @param mixed   $data_or_no_include closure data, array data or boolean ( fetch as string )
     * @param boolean $include            no include ( fetch as string )
     * 
     * @return string                      
     */
    public function load($filename, $data_or_no_include = null, $include = true)
    {
        $folder = PUBLIC_DIR;
        if (isset($_SERVER['HVC_REQUEST']) AND $_SERVER['HVC_REQUEST'] == true) {
            $folder = PRIVATE_DIR;
        }
        return $this->fetch($folder . $this->router->fetchDirectory() . DS . 'view' . DS, $filename, $data_or_no_include, $include);
    }

    /**
     * Load view file app / templates folder
     * 
     * @param string  $filename           filename
     * @param mixed   $data_or_no_include closure data, array data or boolean ( fetch as string )
     * @param boolean $include            no include ( fetch as string )
     * 
     * @return string                      
     */
    public function getTpl($filename, $data_or_no_include = null, $include = true)
    {
        return $this->fetch(APP . 'templates' . DS, $filename, $data_or_no_include, $include);
    }

}

// END View Class
/* End of file View.php

/* Location: .Obullo/View/View.php */