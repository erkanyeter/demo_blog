<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Close Tag
    *
    * @access   public
    * @param    string
    * @return   string
    */
    function close($extra = '')
    {
        return "</form>".$extra;
    }
    
}