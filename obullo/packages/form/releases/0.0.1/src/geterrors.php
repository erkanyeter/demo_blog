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
    * @param    string | object
    * @param    string
    * @param    string
    * @return   string
    */
    function getErrors($field = '', $prefix = '', $suffix = '')
    {   
        global $config;
        
        $form   = \Form::getConfig();

        if($config['enable_query_strings'] AND isset($_GET['errors'][$field])) // GET Support
        {
            return sprintf($form['notifications']['error'], $_GET['errors'][$field]);
        }
        
        if (false === ($OBJ = getInstance()->form->_getValidatorObject()))
        {
            return '';
        }

        if($prefix == '' AND $suffix == '')
        {
            if(empty($field)) // return to all form errors
            {
                return $OBJ->getErrors($field, $prefix, $suffix);
            }

            return sprintf($form['notifications']['error'], $OBJ->getErrors($field, '', ''));
        }

        return $OBJ->getErrors($field, $prefix, $suffix);
    }

}