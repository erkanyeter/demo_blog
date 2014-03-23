<?php
Class Validator_Boolean {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "boolean"
     *
     * @access   public
     * @param    boolean
     * @return   boolean
     */        
    public function __invoke($boolean)
    {
        return (boolean)$boolean;
    }

}