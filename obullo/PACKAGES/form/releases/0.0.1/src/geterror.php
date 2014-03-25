<?php
namespace Form\Src {

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
    function getError($field, $prefix = '', $suffix = '')
    {   
        return getInstance()->form->getErrors($field, $prefix, $suffix);
    }
}