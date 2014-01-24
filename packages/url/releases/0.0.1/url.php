<?php

/**
* Url Helper
*
* @package       packages
* @subpackage    url
* @category      url
* @link
* 
*/

Class Url {

    public function __construct()
    {   
        if( ! isset(getInstance()->url))
        {
            getInstance()->url = $this; // Make available it in the controller $this->url->method();
        }

        logMe('debug', 'Url Class Initialized');
    }

    // ------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Url\Src\\'.$method))
        {
            require PACKAGES .'url'. DS .'releases'. DS .$packages['dependencies']['url']['version']. DS .'src'. DS .mb_strtolower($method). EXT;
        }

        return call_user_func_array('Url\Src\\'.$method, $arguments);
    }
}

/* End of file url.php */
/* Location: ./packages/url/releases/0.0.1/url.php */