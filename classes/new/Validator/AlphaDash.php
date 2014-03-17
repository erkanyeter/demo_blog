<?php
Class Validator_AlphaDash {

    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric with underscores and dashes
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function __invoke($str)
    {
        return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? false : true;
    }

}