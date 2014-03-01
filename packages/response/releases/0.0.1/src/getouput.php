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
        global $response;

        return $response->final_output;
    }

}