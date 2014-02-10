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
    
    public $language  = array(); // langCode folder ( e.g. en_US/ )
    public $is_loaded = array(); // Let we know if its loaded
    
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
    * @param    string   $idiom the language code folder (en_US, etc.)
    * @param    bool     $return return to $translate variable if you don't merge
    * @return   mixed
    */
    public function load($filename = '', $idiom = '', $return = false)
    {
        global $config;

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

        if ( ! isset($translate))
        {
            logMe('error', 'Language file does not contain $translate variable: '. APP .'translations'. DS .$idiom. DS .$filename. EXT);
            
            return;
        }

        if ($return)
        {
            return $translate;
        }

        $this->is_loaded[] = $filename;
        $this->language    = array_merge($this->language, $translate);

        unset($translate);

        logMe('debug', 'Language file loaded: '. APP .'translations'. DS .$idiom. DS .$filename. EXT);
        
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

/* End of file translator.php */
/* Location: ./packages/translator/releases/0.0.1/translator.php */