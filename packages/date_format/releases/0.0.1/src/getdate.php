<?php
namespace Date_Format\Src {

    // ------------------------------------------------------------------------

    /**
    * Convert MySQL Style Datecodes
    *
    * This function is identical to PHPs date() function,
    * except that it allows date codes to be formatted using
    * the MySQL style, where each code letter is preceded
    * with a percent sign:  %Y %m %d etc...
    *
    * The benefit of doing dates this way is that you don't
    * have to worry about escaping your text letters that
    * match the date codes.
    *
    * @access	public
    * @param	string
    * @param	integer
    * @return	integer
    */
    function getDate($datestr = '', $time = '')
    {
        if ($datestr == '')
        {
            return '';   
        }

        if ($time == '')
        {
            $time = getInstance()->date_format->getNow();
        }

        $datestr = str_replace('%\\', '', preg_replace("/([a-z]+?){1}/i", "\\\\\\1", $datestr));

        return date($datestr, $time);
    }

}