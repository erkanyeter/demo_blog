<?php

namespace Obullo\I18n;
use ArrayAccess, Exception;

/**
 * Translator Class
 * 
 * @category  I18n
 * @package   Translator
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/i18n/translator
 */
Class Translator implements ArrayAccess
{
    public $logger;
    public $default_translation;
    public $translate = array();  // Translation array
    public $is_loaded = array();  // Let we know if its loaded

    public $langCode;
    public $langName;
    public $langArray;
    public $langKey = 'locale'; // Default lang code is = "locale"

    // Uri query string based example:  http://example.com/home?locale=en
    // Uri segment based example :      http://example.com/home/en
    
    protected $cookie_prefix = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;

        $this->config              = $c['config']->load('translator');  // get package config file
        $this->default_translation = ($c['config']['locale']['default_translation'] != '') ?  $c['config']['locale']['default_translation'] : 'en_US';

        if ( ! extension_loaded('intl')) {
            throw new Exception(
                sprintf(
                    '%s package requires the intl PHP extension', __CLASS__
                )
            );
        }
        $this->cookie_prefix = $this->config['cookie_prefix'];  // Set cookie prefix

        if ($this->config['query_string']['enabled']) {      // Set language key
            $this->langKey = $this->config['query_string']['key'];
        } elseif ($this->config['uri_segment']['enabled']) {
            $this->langKey = $this->config['uri_segment']['key'];
        }
        $this->langArray = $this->config['languages'];
        $this->langCode  = $this->default_translation;        // default lang code
        $this->langName  = $this->langArray[$this->langCode];  // default lang name

        $this->set(); // Initialize to default language

        $this->logger = $c['logger'];
        $this->logger->debug('Translator Class Initialized');
    }

    /**
     * Sets a parameter or an object.
     *
     * @param string $key   The unique identifier for the parameter
     * @param mixed  $value The value of the parameter
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {        
        $this->translate[$key] = $value;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $key The unique identifier for the parameter
     *
     * @return mixed The value of the parameter or an object
     */
    public function offsetGet($key)
    {
        echo $key;
        if ( ! isset($this->translate[$key])) {
            return false;
        }
        return $this->translate[$key];
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $key The unique identifier for the parameter
     *
     * @return Boolean
     */
    public function offsetExists($key)
    {
        return isset($this->translate[$key]);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $key The unique identifier for the parameter
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->translate[$key]);
    }

    /**
     * Load a translation file
     * 
     * @param string  $filename filename
     * @param string  $code     iso code
     * @param boolean $return   return to array or not
     * 
     * @return mixed
     */
    public function load($filename = '', $code = 'en_US', $return = false)
    {
        if ($code == '' OR $code === false) {
            $code = $this->getLocaleCode();
        }
        if (in_array($filename, $this->is_loaded, true)) {
            return $this->translate;
        }
        if ( ! is_dir(APP . 'translations' . DS . $code)) {
            throw new Exception('The translator folder ' . APP . 'translations' . DS . $code . ' seems not a folder.');
        }
        include APP . 'translations' . DS . $code . DS . $filename . EXT;

        if ( ! isset($translate)) {
            $this->logger->error('Translation file does not contain $translate variable: ' . APP . 'translations' . DS . $code . DS . $filename . EXT);
            return;
        }
        if ($return) {
            return $translate;
        }
        $this->is_loaded[] = $filename;
        $this->translate = array_merge($this->translate, $translate);
        unset($translate);

        $this->logger->debug('Translation file loaded: ' . APP . 'translations' . DS . $code . DS . $filename . EXT);
        return $this->translate;
    }

    /**
     * Get formatted translator item
     * 
     * @return string
     */
    public function sprintf()
    {    
        global $c;
        $args = func_get_args();
        $item = $args[0];

        if (strpos($item, 'translate:') === 0) {    // Do we need to translate the message ?
            $item = substr($item, 10);              // Grab the variable
        }
        if (isset($this->translate[$item])) {
            if (sizeof($args) > 1) {
                $args[0] = $this->translate[$item];
                return call_user_func_array('sprintf', $args);
            }
            return $this->translate[$item];
        }
        $translate_notice = ($c['config']['locale']['translate_notice']) ? 'translate:' : '';
        return $translate_notice . $item;  // Let's notice the developers this line has no translate text
    }

    /**
     * Set translation
     * 
     * @return void
     */
    public function set()
    {
        global $c;

        if (defined('STDIN')) { // Disable console & task errors
            return;
        }
        
        // Set via Http Get method
        
        if ($this->config['query_string']['enabled'] AND isset($_GET[$this->langKey])) { // check $_GET
            $this->setLocale($_GET[$this->langKey]);
            return;
        }

        // Set via URI Segment

        if ($this->config['uri_segment']['enabled'] AND is_numeric($this->config['uri_segment']['segment_number'])) { // check uri segment

            $segment = $c['uri']->getSegment($this->config['uri_segment']['segment_number']);

            if ( ! empty($segment)) {           // empty control
                $this->setLocale($segment);
                return;
            }
        }

        // We have a cookie ? then set using cookie.

        $cookie_name = $this->getCookieKey();

        if (isset($_COOKIE[$cookie_name])) {                 // check cookie if we have not lang cookie
            $this->setLocale($_COOKIE[$cookie_name], false); // DO NOT WRITE TO COOKIE JUST SET TO VARIABLES
            return;
        }

        // Set via browser default value

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->setLocale(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']));
            return;
        }
        $this->setLocale($this->default_translation); // Set from global config file
    }

    /**
     * Get translator class cookie key
     * 
     * @return string
     */
    function getCookieKey()
    {
        return $this->cookie_prefix . $this->langKey;
    }

    /**
     * Set language code
     * 
     * @param string $langCode iso code
     *
     * @return void
     */
    public function setLocaleCode($langCode = 'en_US')
    {
        $this->langCode = (string)$langCode;
    }

    /**
     * Set language name using lang code
     *
     * @return  void
     */
    public function setLocaleName()
    {
        if (isset($this->langArray[$this->langCode])) {
            $this->langName = $this->langArray[$this->langCode];
        }
    }

    /**
     * Set Locale Data ( Lang Code and so on .. )
     * 
     * @param string  $langCode     iso language code
     * @param boolean $write_cookie write cookie on / off
     *
     * @return void
     */
    public function setLocale($langCode = 'en_US', $write_cookie = true)
    {
        if ( ! isset($this->langArray[$langCode])) {    // If its not in defined languages.
            return;  // Good bye ..
        }
        $this->setLocaleCode($langCode);
        $this->setLocaleName();

        if ($this->config['locale_set_default']) {     // use locale_set_default function ?
            locale_set_default($this->getLocaleCode());  // http://www.php.net/manual/bg/function.locale-set-default.php
        }
        if ($write_cookie) {
            $this->setCookie();  // write to cookie
        }
    }

    /**
     * Get current langName
     * 
     * @return string
     */
    public function getLocaleName()
    {
        if (isset($this->langArray[$this->langCode])) {
            return $this->langArray[$this->langCode];
        }
        return $this->langName;
    }

    /**
     * Get curent langCode
     * 
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->langCode;
    }

    /**
     * Write to cookies
     *
     * @return void
     */
    public function setCookie()
    {        
        global $c;

        $this->cookie_domain = ( ! empty($this->config['cookie_domain'])) ? $this->config['cookie_domain'] : $c['config']['cookie']['domain'];
        $this->cookie_path   = ( ! empty($this->config['cookie_path'])) ? $this->config['cookie_path'] : $c['config']['cookie']['path'];
        $this->expiration    = $this->config['cookie_expire'];

        // Set the cookie
        setcookie($this->getCookieKey(), $this->getLocaleCode(), time() + $this->expiration, $this->cookie_path, $this->cookie_domain, 0);
    }

}

// END Translator.php File
/* End of file Translator.php

/* Location: .Obullo/I18n/Translator.php */