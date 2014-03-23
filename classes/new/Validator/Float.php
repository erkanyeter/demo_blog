<?php
Class Validator_Float {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "float"
     *
     * @access   public
     * @param    float
     * @return   float
     */        
    public function __invoke($float)
    {
        return (float)$float;
    }

}