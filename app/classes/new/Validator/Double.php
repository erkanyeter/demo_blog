<?php
Class Validator_Double {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "double"
     *
     * @access   public
     * @param    double
     * @return   double
     */        
    public function __invoke($double)
    {
        return (double)$double;
    }

}