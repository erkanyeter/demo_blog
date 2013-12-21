<?php
namespace Date_Get\Src {

    // ------------------------------------------------------------------------

    /**
    * Convert "human" date to GMT
    *
    * Reverses the above process
    *
    * @access	public
    * @param	string	format: us or euro
    * @return	integer
    */
    function humanToUnix($datestr = '')
    {
        if ($datestr == '')
        {
            return false;
        }

        $datestr = trim($datestr);
        $datestr = preg_replace("/\040+/", ' ', $datestr);

        if ( ! preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr))
        {
            return false;
        }

        $split = explode(' ', $datestr);
        $ex    = explode("-", $split['0']);

        $year  = (strlen($ex['0']) == 2) ? '20'.$ex['0'] : $ex['0'];
        $month = (strlen($ex['1']) == 1) ? '0'.$ex['1']  : $ex['1'];
        $day   = (strlen($ex['2']) == 1) ? '0'.$ex['2']  : $ex['2'];

        $ex    = explode(":", $split['1']);

        $hour = (strlen($ex['0']) == 1) ? '0'.$ex['0'] : $ex['0'];
        $min  = (strlen($ex['1']) == 1) ? '0'.$ex['1'] : $ex['1'];

        if (isset($ex['2']) && preg_match('/[0-9]{1,2}/', $ex['2']))
        {
            $sec  = (strlen($ex['2']) == 1) ? '0'.$ex['2'] : $ex['2'];
        }
        else
        {
            // Unless specified, seconds get set to zero.
            $sec = '00';
        }

        if (isset($split['2']))
        {
            $ampm = strtolower($split['2']);

            if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
                    $hour = $hour + 12;

            if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
                    $hour =  '00';

            if (strlen($hour) == 1)
                    $hour = '0'.$hour;
        }

        return mktime($hour, $min, $sec, $month, $day, $year);
    }

}