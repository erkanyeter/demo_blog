<?php
Class Validator_ValidEmail {
    
    // --------------------------------------------------------------------
    
    /**
     * Valid Email
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function __invoke($str)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
    }

}