<?php

/**
 * Lingo Trigger Class ( Language Initializer )
 *
 * @package       packages
 * @subpackage    lingo
 * @category      language
 * @link
 */

Class Lingo_Trigger {

    public $langCode;
    public $langName;
    public $langArray;
    public $langDefault;

    public $cookie_name;
    public $uri_get_name;       // Uri query string name e.g. http://example.com/home?langCode=en
    public $uri_segment_number; // Uri segment number    e.g. http://example.com/home/en
    public $uri;                // Uri object

    public function __construct()
    {
        if( ! isset(getInstance()->lingo_trigger))
        { 
            getInstance()->lingo_trigger = $this;  // Make available it in the controller $this->lingo_trigger->method();
        }

        $this->uri = getInstance()->uri;

        $this->langArray   = getConfig('lingo_starter');
        $this->langDefault = getInstance()->config->getItem('lingo');

        $this->langCode    = $this->langArray[$this->langDefault]; //default dil tanımlaması
        $this->langName    = $this->langDefault;

        logMe('debug', 'Lingo_Starter Class Initialized');

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
                $this->setCode(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
                $this->setName();
                $this->setCookie(); // write to cookie.
            }
        }

    }

    // ------------------------------------------------------------------------

    /**
     * Set language code
     * 
     * @param string $langCode
     */
    private function setCode($langCode = 'en')
    {
        $this->langCode = (string)$langCode;
    }

    // ------------------------------------------------------------------------

    /**
     * Set language name using lang code
     */
    private function setName()
    {
        $langNames      = array_flip($this->langArray);  // Convert to langCode => langName
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

/* End of file lingo_starter.php */
/* Location: ./packages/lingo_starter/releases/0.0.1/lingo_starter.php */