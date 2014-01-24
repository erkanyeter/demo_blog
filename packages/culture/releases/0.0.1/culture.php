<?php

// http://us.php.net/manual/tr/book.intl.php

/**
 * Culture Class ( Language Initializer )
 *
 * @package       packages
 * @subpackage    lingo
 * @category      language
 * @link
 */

Class Culture {

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

        if( ! isset(getInstance()->culture))
        { 
            getInstance()->culture = $this;  // Make available it in the controller $this->culture->method();
        }

        $this->uri = getInstance()->uri;

        $this->langArray   = getConfig('culture');

        $this->langDefault = getInstance()->config->getItem('lingo');
        $this->langCode    = $this->langArray['languages'][$this->langDefault]; //default dil tanımlaması
        $this->langName    = $this->langDefault;

        logMe('debug', 'Culture Class Initialized');

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
        }

    }

    // ------------------------------------------------------------------------

    // http://us.php.net/manual/tr/book.intl.php
    public function setDefault($locale = 'en-US')
    {
        locale_set_default((string)$locale)
    }

    // ------------------------------------------------------------------------
    
    // http://us.php.net/manual/tr/book.intl.php
    public function getDefault()
    {
        return locale_get_default();
    }

    // ------------------------------------------------------------------------

    /**
     * Set language code
     * 
     * @param string $langCode
     */
    private function setCode($langCode = 'en_US')
    {
        $this->langCode = (string)$langCode;
    }

    // ------------------------------------------------------------------------

    /**
     * Set language name using lang code
     */
    private function setName()
    {
        $langNames      = array_flip($this->langArray['languages']);  // Convert to langCode => langName
        $this->langName = isset($langNames[$this->langCode]) ? $langNames[$this->langCode] : $this->langDefault;
    }

    // ------------------------------------------------------------------------

    /**
     * Get current langName
     * 
     * @return string
     */
    public function getName()
    {
        return $this->langName;
    }

    // -----------------------------------------------------------------------

    /**
     * Get curent langCode
     * 
     * @return string
     */
    public function getCode()
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

/* End of file culture.php */
/* Location: ./packages/culture/releases/0.0.1/culture.php */