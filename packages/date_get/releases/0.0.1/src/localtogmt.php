<?php
namespace Date_Get\Src {

    // ------------------------------------------------------------------------

    /**
    * Converts a local Unix timestamp to GMT
    *
    * @access	public
    * @param	integer Unix timestamp
    * @return	integer
    */	
    function localToGmt($time = '')
    {
        if ($time == '')
        {
            $time = time();
        }

        return mktime( 
                    gmdate("H", $time), 
                    gmdate("i", $time), 
                    gmdate("s", $time), 
                    gmdate("m", $time), 
                    gmdate("d", $time), 
                    gmdate("Y", $time));
    }

}