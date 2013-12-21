<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
    * Generate a key value pair from the URI string
    *
    * This function generates and associative array of URI data starting
    * at the supplied segment. For example, if this is your URI:
    *
    *    example.com/user/search/name/joe/location/UK/gender/male
    *
    * You can use this function to generate an array with this prototype:
    *
    * array (
    *            name => joe
    *            location => UK
    *            gender => male
    *         )
    *
    * @access   public
    * @param    integer    the starting segment number
    * @param    array    an array of default values
    * @return   array
    */
    function uriToAssoc($n = 3, $default = array())
    {
        $uriObject = getComponentInstance('uri');

        return $uriObject->_uriToAssoc($n, $default, 'segment');
    }
    
}