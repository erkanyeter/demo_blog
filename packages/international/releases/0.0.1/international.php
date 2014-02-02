<?php

// http://us.php.net/manual/tr/book.intl.php

/**
 * International ( Localization ) Class
 * This class sets the accepted language code ( en-US )
 * charsets , language name and other things.
 *
 * @package       packages
 * @subpackage    localization
 * @category      locale
 * @link
 */

Class International {

    public $langCode;
    public $langName;
    public $langArray;
    public $langDefault;

    public $cookie_langName_key;    // $_COOKIE['langName']
    public $cookie_langCode_key;    // $_COOKIE['langCode']
    public $uri_get_name;           // Uri query string name e.g. http://example.com/home?langCode=en
    public $uri_segment_number;     // Uri segment number    e.g. http://example.com/home/en
    public $uri;                    // Uri object

    public function __construct()
    {
        if ( ! extension_loaded('intl')) 
        {
            throw new Exception(sprintf(
                '%s package requires the intl PHP extension',
                __CLASS__
            ));
        }

        if( ! isset(getInstance()->language))
        { 
            getInstance()->language = $this;  // Make available it in the controller $this->language->method();
        }

        $this->uri = getInstance()->uri;

        $this->langArray   = getConfig('international'); print_r($this->langArray['languages']); exit;
        $this->langDefault = getInstance()->config->getItem('default_translation');

        $this->langCode    = $this->langArray['languages'][$this->langDefault]; //default dil tanımlaması
        $this->langName    = $this->langDefault;

        logMe('debug', 'Localization Class Initialized');

        $this->_init(); // Initialize 
    }

    // ------------------------------------------------------------------------

    /**
     * Initialize Function
     * 
     * @return void
     */
    public function _init()
    {
        if(defined('STDIN')) // Disable console & task errors
        {
            return; 
        }

        //----------- SET FROM HTTP GET METHOD ------------//

        if( isset($_GET['langCode']) AND ! empty($_GET['langCode'])) // check $_GET
        {
            $this->setCode($_GET['lang']);
            $this->setName();
            $this->setCookie(); // write to cookie

            $this->setDefault(); // Set default locale for php Locale class.

            return;
        } 

        //----------- SET FROM COOKIE ------------//

        if( isset($_COOKIE['langCode']) AND ! empty($_COOKIE['langCode'])) // check cookie
        {
            $this->setCode($_COOKIE['langCode']);
            $this->setName();  

            // do not set cookie we have already data in cookie.

            return;
        } 
        else 
        {
            //----------- SET FROM BROWSER DEFAULT VALUE ------------// 

            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            {
                $this->setCode(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']));
                $this->setName();
                $this->setCookie(); // write to cookie.
            } 
            else 
            {
                $this->getDefault();
            }
        }

    }

    // ------------------------------------------------------------------------

    public function setDefault($locale = 'en-US')   // http://us.php.net/manual/tr/book.intl.php
    {
        locale_set_default((string)$locale);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Get default locale config.php file
     * Use app locale instead of php.ini
     * 
     * @return string
     */
    public function getDefault()     // http://us.php.net/manual/tr/book.intl.php
    {
        global $config;

        return $config['default_translation']; // locale_get_default();
    }

    // ------------------------------------------------------------------------

    /**
     * Set language code
     * 
     * @param string $langCode
     */
    private function setLangCode($langCode = 'en-US')
    {
        $this->langCode = (string)$langCode;
    }

    // ------------------------------------------------------------------------

    /**
     * Set language name using lang code
     */
    private function setLangName()
    {
        $langNames      = array_flip($this->langArray['languages']);  // Convert to langCode => langName
        $this->langName = isset($langNames[$this->langCode]) ? $langNames[$this->langCode] : $this->langDefault;
    }

    // -----------------------------------------------------------------------

    /**
     * Get current langName
     * 
     * @return string
     */
    public function getLangName()
    {
        return $this->langName;
    }

    // -----------------------------------------------------------------------

    /**
     * Get curent langCode
     * 
     * @return string
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    // -----------------------------------------------------------------------

    /**
     * Set cookie to lang code
     * 
     * @param string $langCode
     */
    private function setCookie()
    {
        if(in_array($this->langCode, $this->langArray, true)) // check language available in defined languages.
        {
            setcookie('langName', $this->langName, time() + (60 * 60 * 24 * 30), '/');
            setcookie('langCode', $this->langCode, time() + (60 * 60 * 24 * 30), '/');
        }
    }

}

/* End of file localization.php */
/* Location: ./packages/localization/releases/0.0.1/localization.php */