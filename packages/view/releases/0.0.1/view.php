<?php

/**
 * View Helper
 *
 * @package       packages
 * @subpackage    view
 * @category      views
 * @link
 */

Class View {

    public static $_instance;  // Object instance

    /**
     * Data Storage variables
     * 
     * @var array
     */
    public $_string       = array(); // String type view variables
    public $_array        = array(); // Array type view variables
    public $_object       = array(); // Object type view variables
    
    /**
     * Constructor
     */
    public function __construct()
    {
        logMe('debug', "View Class Initialized");
    }

    // ------------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$_instance instanceof self)
       {
           self::$_instance = new self();
       } 
       
       return self::$_instance;
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
        if(function_exists('getInstance') AND  is_object(getInstance()))
        {
            foreach(array_keys(get_object_vars(getInstance())) as $key) // This allows to using "$this" variable in all views files.
            {                
                $this->{$key} = getInstance()->{$key}; // e.g. $this->config->item('myitem')
            }
        }
        
        $this->_isCallable($__vData);

        if(count($this->_string) > 0)  // extract all view variables.
        {
            extract($this->_string, EXTR_SKIP); 
        }

        if(sizeof($this->_array) > 0)
        {   
            extract($this->_array, EXTR_SKIP); 
        } 

        if(count($this->_object) > 0)
        {
            extract($this->_object, EXTR_SKIP); 
        }

        logMe('debug', 'View file loaded: '.$__vPath. $__vFilename . EXT);

        ob_start();   // Please open short tags in your php.ini file. ( short_tag = On ).

        include($__vPath. $__vFilename . EXT);

        $output = ob_get_clean();

        if($__vData === false  || $__vInclude === false)
        {
            return $output;
        }
        
        getComponentInstance('output')->appendOutput($output);

        return;
    }

    // --------------------------------------------------------------------
    
    /**
     * Set variables
     * 
     * @param string $key 
     * @param mixed $val
     */
    public function set($key, $val)
    {
        $val = $this->_isCallable($val);

        if(is_string($val))
        {
            $this->_string[$key] = $val;
        }

        if(is_array($val))
        {
            if(count($val) == 0)
            {
                $this->_array[$key] = array();
            } 
            else 
            {
                foreach($val as $value)
                {
                    $this->_array[$key][] = $value;
                }
            }
        }

        if(is_object($val))
        {
            $this->_object[$key] = $val;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Use a view scheme
     * 
     * @param  string $scheme scheme name
     * @return void
     */
    public function getScheme($file = '', $schemeName = 'default')
    {
        $args = func_get_args();

        $fileData = (empty($file)) ? array() : array($file);

        $schemes = getConfig('scheme');
        if(isset($schemes[$schemeName]) AND is_callable($schemes[$schemeName]))
        {
            call_user_func_array(Closure::bind($schemes[$schemeName], $this, get_class()), $fileData);
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
        if(is_callable($val)) // Is callable function ?
        {
            $func = Closure::bind($val, $this, get_class());
            return $func();
        }

        return $val;
    }

}

// END View Class

/* End of file View.php */
/* Location: ./packages/view/releases/0.0.1/view.php */