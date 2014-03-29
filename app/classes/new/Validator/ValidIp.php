<?php
Class Validator_ValidIp {

    // --------------------------------------------------------------------
    
    /**
     * Validate IP Address
     *
     * @access   public
     * @param    string
     * @return   string
     */
    public function __invoke($ip)
    {
        $get = new \Get;
        
        return $get->validIp($ip);
    }
    
}