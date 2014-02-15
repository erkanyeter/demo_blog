<?php
namespace Response\Src {

    // --------------------------------------------------------------------
    
    /**
    * Set Output
    *
    * Sets the output string
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    function setOutput($output)
    {
        \Response::getInstance()->final_output = $output;
    }
}