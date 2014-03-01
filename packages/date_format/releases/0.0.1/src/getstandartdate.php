<?php
namespace Date_Format\Src {

    // ------------------------------------------------------------------------

    /**
    * Standard Date
    *
    * Returns a date formatted according to the submitted standard.
    *
    * @access	public
    * @param	string	the chosen format
    * @param	integer	Unix timestamp
    * @return	string
    */
    function getStandardDate($fmt = 'DATE_RFC822', $time = '')
    {
        $formats = array(
                        'DATE_ATOM'    =>	'%Y-%m-%dT%H:%i:%s%Q',
                        'DATE_COOKIE'  =>	'%l, %d-%M-%y %H:%i:%s UTC',
                        'DATE_ISO8601' =>	'%Y-%m-%dT%H:%i:%s%O',
                        'DATE_RFC822'  =>	'%D, %d %M %y %H:%i:%s %O',
                        'DATE_RFC850'  =>	'%l, %d-%M-%y %H:%m:%i UTC',
                        'DATE_RFC1036' =>	'%D, %d %M %y %H:%i:%s %O',
                        'DATE_RFC1123' =>	'%D, %d %M %Y %H:%i:%s %O',
                        'DATE_RSS'     =>	'%D, %d %M %Y %H:%i:%s %O',
                        'DATE_W3C'     =>	'%Y-%m-%dT%H:%i:%s%Q'
                        );

        if ( ! isset($formats[$fmt]))
        {
            return false;
        }

        return mdate($formats[$fmt], $time);
    }

}