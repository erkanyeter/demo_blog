<?php
Class Validator_AlphaNumeric {

    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function __invoke($str)
    {
        return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? false : true;
    }

}