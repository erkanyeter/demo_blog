<?php
Class Validator_Alpha {

    // --------------------------------------------------------------------
    
    /**
     * Alpha
     *
     * @access   public
     * @param    string
     * @return   bool
     */        
    public function __invoke($str)
    {
        return ( ! preg_match("/^([a-z])+$/i", $str)) ? false : true;
    }

}