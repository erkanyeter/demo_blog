<?php
namespace Date_Get\Src {
    
    /**
    * Get "now" time
    *
    * Returns time() or its GMT equivalent based on the config file preference
    *
    * @access	public
    * @return	integer
    */
    function now()
    {
        $time_ref = config('time_reference');
        
        if (strtolower($time_ref) == 'gmt')
        {
            $now = time();
            $system_time = mktime(
                            gmdate("H", $now), 
                            gmdate("i", $now), 
                            gmdate("s", $now), 
                            gmdate("m", $now), 
                            gmdate("d", $now), 
                            gmdate("Y", $now));

            if (strlen($system_time) < 10)
            {
                $system_time = time();
                
                logMe('error', 'The Date Get helper could not set a proper GMT timestamp so the local time() value was used.');
            } 

            return $system_time;
        }
        else
        {
            return time();
        }
    }

}