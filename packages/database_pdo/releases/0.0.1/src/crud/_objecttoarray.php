<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Object to Array
    *
    * Takes an object as input and converts the class variables to array key/vals
    *
    * @access   public
    * @param    object
    * @return   array
    */
    function _objectToArray($object)
    {
        if ( ! is_object($object))
        {
            return $object;
        }
        
        $array = array();
        foreach (get_object_vars($object) as $key => $val)
        {
            // There are some built in keys we need to ignore for this conversion
            if ( ! is_object($val) AND ! is_array($val) )
            {
                $array[$key] = $val;
            }
        }
    
        return $array;
    }

}