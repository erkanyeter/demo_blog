<?php
Class Validator_Array {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "array"
     *
     * @access   public
     * @param    binary
     * @return   binary
     */        
    public function __invoke($array)
    {
        return (array)$array;
    }

}