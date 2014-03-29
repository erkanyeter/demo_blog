<?php

namespace Obullo\I18n;

/**
 * Translator Class ( Language )
 *
 * @package       packages
 * @subpackage    translator
 * @category      translations
 * @link
 */

/*
MUST BE EXTEND TO ARRAY ACCESS
 */
Class Translator
{
    public $logger;
    public $language  = array(); // langCode folder ( e.g. en_US/ )
    public $is_loaded = array(); // Let we know if its loaded

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;

        $this->logger = $c['logger'];
        $this->logger->debug('Translator Class Initialized');
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
        global $c;

        if ($idiom == '' OR $idiom === false) {
            $default = $c['config']['locale']['default_translation'];
            $idiom   = ($default == '') ? 'en_US' : $default;
        }
        if (in_array($filename, $this->is_loaded, true)) {
            return;
        }
        if ( ! is_dir(APP .'translations'. DS .$idiom)) {
            throw new Exception('The language folder '.APP .'translations'. DS .$idiom.' seems not a folder.');
        }
        
        include APP .'translations'. DS .$idiom. DS .$filename. EXT;

        if ( ! isset($translate)) {
            $this->logger->error('Language file does not contain $translate variable: '. APP .'translations'. DS .$idiom. DS .$filename. EXT);
            return;
        }
        if ($return) {
            return $translate;
        }
        $this->is_loaded[] = $filename;
        $this->language    = array_merge($this->language, $translate);

        unset($translate);

        $this->logger->debug('Language file loaded: '. APP .'translations'. DS .$idiom. DS .$filename. EXT);
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

// --------------------------------------------------------------------
// @@ Translator Helper Functions  
// --------------------------------------------------------------------

/**
 * Check item has translate
 * 
 * @param  string  $item 
 * @return boolean
 */
function hasTranslate($item)
{        
    global $translator;

    if (isset($translator->language[$item])) {
        return true;
    }

    return false;
}

// --------------------------------------------------------------------

/**
 * Fetch the language item using sprintf().
 *
 * @access public
 * 
 * @param string $item
 * @return string
 */
function translate()
{
    global $c;

    $args  = func_get_args();
    $item  = $args[0];

    if (strpos($item, 'translate:') === 0) {    // Do we need to translate the message ?
        $item = substr($item, 10);              // Grab the variable
    }
    if ( isset($c['translator']->language[$item])) {
        $translated = $c['translator']->language[$item];

        if (sizeof($args) > 1) {
            $args[0] = $translated;
            return call_user_func_array('sprintf', $args);
        }
        return $translated;
    }
    $translate_notice = ($c['config']['locale']['translate_notice']) ? 'translate:' : '';
    return $translate_notice.$item;  // Let's notice the developers this line has no translate text
}

// END Translator.php File
/* End of file Translator.php

/* Location: .Obullo/I18n/Translator.php */