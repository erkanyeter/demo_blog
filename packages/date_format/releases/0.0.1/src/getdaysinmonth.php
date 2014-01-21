<?php
namespace Date_Format\Src {

    // ------------------------------------------------------------------------

    /**
    * Number of days in a month
    *
    * Takes a month/year as input and returns the number of days
    * for the given month/year. Takes leap years into consideration.
    *
    * @access	public
    * @param	integer a numeric month
    * @param	integer	a numeric year
    * @return	integer
    */
    function getDaysInMonth($month = 0, $year = '')
    {
        if ($month < 1 OR $month > 12)
        {
            return 0;
        }

        if ( ! is_numeric($year) OR strlen($year) != 4)
        {
            $year = date('Y');
        }

        if ($month == 2)
        {
            if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0))
            {
                return 29;
            }
        }

        $days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        
        return $days_in_month[$month - 1];
    }

}