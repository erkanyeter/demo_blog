<?php

/**
 * Translator Class ( Language )
 *
 * @package       packages
 * @subpackage    translator
 * @category      translations
 * @link
 */

Class Translator {
    
    public $language  = array();
    public $is_loaded = array();
    
    public static $instance;

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        logMe('debug', 'Translator Class Initialized');
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
    * Load a translation file
    *
    * @access   public
    * @param    string   $filename the name of the language file to be loaded. Can be an array
    * @param    string   $idiom the language folder (english, etc.)
    * @param    bool     $return return to $lang variable if you don't merge
    * @return   mixed
    */
    public function load($filename = '', $idiom = '', $return = false)
    {
        $config = getConfig();

        if ($idiom == '' OR $idiom === false)
        {
            $default = $config['default_translation'];
            $idiom   = ($default == '') ? 'en_US' : $default;
        }
        
        if (in_array($filename, $this->is_loaded, true))
        {
            return;
        }
        
        if( ! is_dir(APP .'translations'. DS .$idiom))
        {
            throw new Exception('The language folder '.APP .'translations'. DS .$idiom.' seems not a folder.');
        }
        
        require(APP .'translations'. DS .$idiom. DS .$filename. EXT);

        if ( ! isset($lang))
        {
            logMe('error', 'Translator file does not contain $lang variable: '. APP .'translations'. DS .$idiom. DS .$filename. EXT);
            
            return;
        }

        if ($return)
        {
            return $lang;
        }

        $this->is_loaded[] = $filename;
        $this->language    = array_merge($this->language, $lang);

        unset($lang);

        logMe('debug', 'Translator file loaded: '. APP .'lingo'. DS .$idiom. DS .$filename. EXT);
        
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