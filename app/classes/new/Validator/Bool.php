<?php
Class Validator_Bool {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "boolean" ( Alias of "boolean" )
     *
     * @access   public
     * @param    boolean
     * @return   boolean
     */        
    public function __invoke($boolean)
    {
        return (bool)$boolean;
    }

}