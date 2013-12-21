<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Get notification message you set it before.
     *
     * @param string $errorKey 'success' or 'errorMessage'
     * @return string notification
     */
    function getNotice($errorKey = 'success')
    {
        $Class = getComponent('sess');
        $sess  = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;

        $form   = getConfig('form');
        $notice = $sess->getFlash('_validatorNotice');

        if(getInstance()->validator->_validation == false)
        {
            $errorKey = 'errorMessage';
        }

        if( ! empty($notice))
        {
            return sprintf($form['errors'][$errorKey], $notice);
        }

        return;
    }

}