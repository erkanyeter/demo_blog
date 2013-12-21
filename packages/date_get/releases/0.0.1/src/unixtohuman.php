<?php
namespace Date_Get\Src {

    // ------------------------------------------------------------------------

    /**
    * Unix to "Human"
    *
    * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
    *
    * @access	public
    * @param	integer Unix timestamp
    * @param	bool	whether to show seconds
    * @param	string	format: us or euro
    * @return	string
    */	
    function unixToHuman($time = '', $seconds = false, $fmt = 'us')
    {
        $r  = date('Y', $time).'-'.date('m', $time).'-'.date('d', $time).' ';

        if ($fmt == 'us')
        {
            $r .= date('h', $time).':'.date('i', $time);
        }
        else
        {
            $r .= date('H', $time).':'.date('i', $time);
        }

        if ($seconds)
        {
            $r .= ':'.date('s', $time);
        }

        if ($fmt == 'us')
        {
            $r .= ' '.date('A', $time);
        }

        return $r;
    }

}