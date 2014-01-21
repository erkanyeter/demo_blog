<?php
namespace Date_Format\Src {

    // ------------------------------------------------------------------------

    /**
    * Converts GMT time to a localized value
    *
    * Takes a Unix timestamp (in GMT) as input, and returns
    * at the local value based on the timezone and DST setting
    * submitted
    *
    * @access	public
    * @param	integer Unix timestamp
    * @param	string	timezone
    * @param	bool	whether DST is active
    * @return	integer
    */
    function gmtToLocal($time = '', $timezone = 'UTC', $dst = false)
    {			
        if ($time == '')
        {
            return getInstance()->date_format->getNow();
        }

        $time += getInstance()->date_format->getTimeZones($timezone) * 3600;

        if ($dst == true)
        {
            $time += 3600;
        }

        return $time;
    }
    
}