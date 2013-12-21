<?php
namespace Date_Get\Src {
    
    // ------------------------------------------------------------------------

    /**
    * Timespan
    *
    * Returns a span of seconds in this format:
    *	10 days 14 hours 36 minutes 47 seconds
    *
    * @access	public
    * @param	integer	a number of seconds
    * @param	integer	Unix timestamp
    * @return	integer
    */
    function timeSpan($seconds = 1, $time = '')
    {
        if ( ! is_numeric($seconds))
        {
            $seconds = 1;
        }

        if ( ! is_numeric($time))
        {
            $time = time();
        }

        if ($time <= $seconds)
        {
            $seconds = 1;
        }
        else
        {
            $seconds = $time - $seconds;
        }

        $str = '';
        $years = floor($seconds / 31536000);

        if ($years > 0)
        {	
            $str .= $years.' '.lingo((($years > 1) ? 'date_years' : 'date_year')).', ';
        }	

        $seconds -= $years * 31536000;
        $months = floor($seconds / 2628000);

        if ($years > 0 OR $months > 0)
        {
            if ($months > 0)
            {
                $str .= $months.' '.lingo((($months > 1) ? 'date_months' : 'date_month')).', ';
            }	

            $seconds -= $months * 2628000;
        }

        $weeks = floor($seconds / 604800);

        if ($years > 0 OR $months > 0 OR $weeks > 0)
        {
            if ($weeks > 0)
            {
                $str .= $weeks.' '.lingo((($weeks > 1) ? 'date_weeks' : 'date_week')).', ';
            }

            $seconds -= $weeks * 604800;
        }			

        $days = floor($seconds / 86400);

        if ($months > 0 OR $weeks > 0 OR $days > 0)
        {
            if ($days > 0)
            {
                $str .= $days.' '.lingo((($days	> 1) ? 'date_days' : 'date_day')).', ';
            }

            $seconds -= $days * 86400;
        }

        $hours = floor($seconds / 3600);

        if ($days > 0 OR $hours > 0)
        {
            if ($hours > 0)
            {
                $str .= $hours.' '.lingo((($hours > 1) ? 'date_hours' : 'date_hour')).', ';
            }

            $seconds -= $hours * 3600;
        }

        $minutes = floor($seconds / 60);

        if ($days > 0 OR $hours > 0 OR $minutes > 0)
        {
            if ($minutes > 0)
            {
                $str .= $minutes.' '.lingo((($minutes > 1) ? 'date_minutes' : 'date_minute')).', ';
            }

            $seconds -= $minutes * 60;
        }

        if ($str == '')
        {
            $str .= $seconds.' '.lingo((($seconds	> 1) ? 'date_seconds' : 'date_second')).', ';
        }

        return substr(trim($str), 0, -1);
    }

}