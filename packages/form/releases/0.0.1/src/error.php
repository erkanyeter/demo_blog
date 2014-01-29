<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Error
    *
    * Returns the error for a specific form field.  This is a helper for the
    * form validation class.
    *
    * @access	public
    * @param	string | object
    * @param	string
    * @param	string
    * @return	string
    */
    function error($field = '', $prefix = '', $suffix = '')
    {       
        return getInstance()->form->getErrors($field, $prefix, $suffix);
    }

}