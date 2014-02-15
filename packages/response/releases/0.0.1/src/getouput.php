<?php
namespace Response\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Get Output
    *
    * Returns the current output string
    *
    * @access    public
    * @return    string
    */    
    function getOutput()
    {
        return \Response::getInstance()->final_output;
    }

}