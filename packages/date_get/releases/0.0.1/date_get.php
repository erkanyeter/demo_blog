<?php

/**
 * Date Get Class
 *
 * @package     packages
 * @subpackage  date
 * @category    date & time
 * @link        
 */

Class Date_Get {

    function __construct()
    {
        getInstance()->lingo->load('date_get');

        if( ! isset(getInstance()->date_get))
        {
            getInstance()->date_get = $this; // Make available it in the controller $this->date_get->method();
        }

        logMe('debug', 'Date_Get Class Initialized');
    }

    // ------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if($method == 'timeZones' || $method == 'timezoneMenu')
        {
            if( ! function_exists('Date_Get\Src\\timeZones') && ! function_exists('Date_Get\Src\\timezoneMenu'))
            {
                require PACKAGES .'date_get'. DS .'releases'. DS .$packages['dependencies']['date_get']['version']. DS .'src'. DS .'timezones'. EXT;
            }

            return call_user_func_array('Date_Get\Src\\'.$method, $arguments);
        }

        if( ! function_exists('Date_Get\Src\\'.$method))
        {
            require PACKAGES .'date_get'. DS .'releases'. DS .$packages['dependencies']['date_get']['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Date_Get\Src\\'.$method, $arguments);
    }
}

/* End of file date_get.php */
/* Location: ./packages/date_get/releases/0.0.1/date_get.php */