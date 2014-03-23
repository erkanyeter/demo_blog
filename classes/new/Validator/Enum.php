<?php
Class Validator_Enum {

    // --------------------------------------------------------------------
    
    /**
     * Keep the native type.
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