<?php
Class Form_GetError {

    // ------------------------------------------------------------------------

    /**
    * Form Error
    *
    * Returns the error for a specific form field.  This is a helper for the
    * form validation class.
    *
    * @access   public
    * @param    string
    * @param    string
    * @param    string
    * @return   string
    */
    public function __invoke($field, $prefix = '', $suffix = '')
    {   
        return getInstance()->form->getErrors($field, $prefix, $suffix);
    }
}