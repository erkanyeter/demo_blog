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
    function errors($field = '', $prefix = '', $suffix = '')
    {       
        $form = \Form::getFormConfig();

        if(config('enable_query_strings') AND isset($_GET['errors'][$field])) // GET Support
        {
            return sprintf($form['errors']['error'], $_GET['errors'][$field]);
        }
        
        if (false === ($OBJ = getInstance()->form->_getValidatorObject()))
        {
            return '';
        }

        if($prefix == '' AND $suffix == '')
        {
            return sprintf($form['errors']['error'], $OBJ->errors($field, '', ''));
        }

        return $OBJ->errors($field, $prefix, $suffix);
    }

}