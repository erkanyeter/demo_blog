<?php

/**
 * Lingo Class ( Language )
 *
 * @package       packages
 * @subpackage    lingo
 * @category      language
 * @link
 */

Class Lingo {
    
    public $language  = array();
    public $is_loaded = array();
    
    public static $instance;

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        logMe('debug', 'Lingo Class Initialized');
    }

    // --------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       }
       
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Load a language file
    *
    * @access   public
    * @param    string   $filename the name of the language file to be loaded. Can be an array
    * @param    string   $idiom the language folder (english, etc.)
    * @param    bool     $return return to $lang variable if you don't merge
    * @return   mixed
    */
    public function load($filename = '', $idiom = '', $return = false)
    {
        if ($idiom == '' OR $idiom === false)
        {
            $default = config('lingo');
            $idiom   = ($default == '') ? 'english' : $default;
        }
        
        if (in_array($filename, $this->is_loaded, true))
        {
            return;
        }
        
        if( ! is_dir(APP .'lingo'. DS .$idiom))
        {
            throw new Exception('The language folder '.APP .'lingo'. DS .$idiom.' seems not a folder.');
        }
        
        require(APP .'lingo'. DS .$idiom. DS .$filename. EXT);

        if ( ! isset($lang))
        {
            logMe('error', 'Lingo file does not contain $lang variable: '. APP .'lingo'. DS .$idiom. DS .$filename. EXT);
            
            return;
        }

        if ($return)
        {
            return $lang;
        }

        $this->is_loaded[] = $filename;
        $this->language    = array_merge($this->language, $lang);

        unset($lang);

        logMe('debug', 'Lingo file loaded: '. APP .'lingo'. DS .$idiom. DS .$filename. EXT);
        
        return true;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get all language items
     * 
     * @return array
     */
    public function getAllData()
    {
        return $this->language;
    }

}

/* End of file lingo.php */
/* Location: ./packages/lingo/releases/0.0.1/lingo.php */