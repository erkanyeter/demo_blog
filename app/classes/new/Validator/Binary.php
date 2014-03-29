<?php
Class Validator_Binary{

    // --------------------------------------------------------------------
    
    /**
     * Set type to "binary"
     *
     * @access   public
     * @param    binary
     * @return   binary
     */        
    public function __invoke($binary)
    {
        return (binary)$binary;
    }

}