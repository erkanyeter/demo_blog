<?php
namespace Date_Get\Src {

    // ------------------------------------------------------------------------

    /**
    * Converts a MySQL Timestamp to Unix
    *
    * @access	public
    * @param	integer Unix timestamp
    * @return	integer
    */	
    function mysqlToUnix($time = '')
    {
        // We'll remove certain characters for backward compatibility
        // since the formatting changed with MySQL 4.1
        // YYYY-MM-DD HH:MM:SS

        $time = str_replace('-', '', $time);
        $time = str_replace(':', '', $time);
        $time = str_replace(' ', '', $time);

        // YYYYMMDDHHMMSS
        return  mktime(
                        substr($time, 8, 2),
                        substr($time, 10, 2),
                        substr($time, 12, 2),
                        substr($time, 4, 2),
                        substr($time, 6, 2),
                        substr($time, 0, 4)
                        );
    }

}