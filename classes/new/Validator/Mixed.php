<?php
Class Validator_Mixed {

    // --------------------------------------------------------------------
    
    /**
     * Keeps the native type.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */        
    public function __invoke($mixed)
    {
        return $mixed;
    }

}