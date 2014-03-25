<?php 
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Declaration - Multipart type
    *
    * Creates the opening portion of the form, but with "multipart/form-data".
    *
    * @access	public
    * @param	string	the URI segments of the form destination
    * @param	array	a key/value pair of attributes
    * @param	array	a key/value pair hidden data
    * @return	string
    */ 
    function openMultipart($action, $attributes = array(), $hidden = array())
    {
        if (is_string($attributes))
        {
            $attributes .= ' enctype="multipart/form-data"';
        }
        else
        {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return getInstance()->form->open($action, $attributes, $hidden);
    }
    
}