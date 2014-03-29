<?php
Class Validator_Null {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "null"
     *
     * @access   public
     * @param    null
     * @return   null
     */        
    public function __invoke($null)
    {
        return ($null == null);
    }

}