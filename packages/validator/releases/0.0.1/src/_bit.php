<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Leave as "number"
     *
     * @access   public
     * @param    string
     * @return   bool
     */        
    function _bit($number)
    {
        if(is_numeric($number))
        {
            return $number;
        }

        return 0;  // If its not a number return to 0.
                   // http://stackoverflow.com/questions/8529656/how-to-convert-string-to-number-in-php
    }

}