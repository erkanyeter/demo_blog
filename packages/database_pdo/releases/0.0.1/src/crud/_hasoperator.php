<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------

    /**
    * Tests whether the string has an SQL operator
    *
    * @access   private
    * @param    string
    * @return   bool
    */ 
    function _hasOperator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return false;
        }

        return true;
    }
    
}