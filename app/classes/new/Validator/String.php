<?php
Class Validator_String {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "string"
     *
     * @access   public
     * @param    string
     * @return   string
     */        
    public function __invoke($str)
    {
        return (string)$str;
    }

}