<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $key set notification message or field.
     * @param string $val set notification message value.
     * 
     * @return void
     */
    function setMessage($key = '', $val = '')
    {
        
        if (empty($val)) {  //  set form validation message

            $form       = \Form::getConfig();
            $formObject = getInstance()->form;
            $message    = ( ! empty($key)) ? $key : $form['response']['error'];

            $formObject->_formMessages['success'] = 0;            
            $formObject->_formMessages['message'] = sprintf($form['notifications']['errorMessage'], translate($message));
            $formObject->_formMessages['e']       = $key;
        
        } else { 
            getInstance()->validator->setMessage($key, $val); // use validator object set message
        }
    }
    
}