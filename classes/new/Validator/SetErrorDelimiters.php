<?php
Class Validator_SetErrorDelimiters {

    // --------------------------------------------------------------------
    
    /**
     * Set The Error Delimiter
     *
     * Permits a prefix/suffix to be added to each error message
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   void
     */    
    public function __invoke($prefix = '<p>', $suffix = '</p>')
    {
        $validator = getInstance()->validator;

        $validator->_error_prefix = $prefix;
        $validator->_error_suffix = $suffix;
    }
    
}