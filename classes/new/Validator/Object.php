<?php
Class Validator_Object {

    // --------------------------------------------------------------------
    
    /**
     * Set type to "object"
     *
     * @access   public
     * @param    string
     * @return   object
     */        
    public function __invoke($object)
    {
        return (object)$object;
    }

}