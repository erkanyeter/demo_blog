<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Password Field
    *
    * Identical to the input function but adds the "password" type
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	string
    * @return	string
    */
    function password($data = '', $value = '', $extra = '')
    {
        if(is_object($value)) // $_POST & Db value schema sync
        {
            $value = getInstance()->form->_getRowValue($value, $data); 
        }
        
        if ( ! is_array($data))
        {
            $data = array('name' => $data);
        }

        $data['type'] = 'password';
        
        $form = \Form::getConfig();

        return sprintf($form['templates'][\Form::$template]['password'], getInstance()->form->input($data, $value, $extra));
    }
    
}