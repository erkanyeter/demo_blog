<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
     * Generate a URI string from an associative array
     *
     *
     * @access   public
     * @param    array    an associative array of key/values
     * @return   array
     */
    function getAssocToUri($array)
    {
        $temp = array();
        foreach ((array)$array as $key => $val)
        {
            $temp[] = $key;
            $temp[] = $val;
        }

        return implode('/', $temp);
    }

}