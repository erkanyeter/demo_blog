<?php

/**
 * Date Format Class
 *
 * @package     packages
 * @subpackage  date
 * @category    date & time
 * @link        
 */

Class Date_Format {

    function __construct()
    {
        getInstance()->lingo->load('date_format');

        if( ! isset(getInstance()->date_format))
        {
            getInstance()->date_format = $this; // Make available it in the controller $this->date_format->method();
        }

        logMe('debug', 'Date_Format Class Initialized');
    }

    // ------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if($method == 'getTimeZones' OR $method == 'getTimezoneMenu')
        {
            if( ! function_exists('Date_Format\Src\\getTimeZones') AND ! function_exists('Date_Get\Src\\getTimezoneMenu'))
            {
                require PACKAGES .'date_format'. DS .'releases'. DS .$packages['dependencies']['date_format']['version']. DS .'src'. DS .'gettimezones'. EXT;
            }

            return call_user_func_array('Date_Format\Src\\'.$method, $arguments);
        }

        if( ! function_exists('Date_Format\Src\\'.$method))
        {
            require PACKAGES .'date_format'. DS .'releases'. DS .$packages['dependencies']['date_format']['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Date_Format\Src\\'.$method, $arguments);
    }
}

/* End of file date_format.php */
/* Location: ./packages/date_format/releases/0.0.1/date_format.php */