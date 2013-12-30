<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Get notification message you set it before.
     *
     * @param string $errorKey 'success' or 'errorMessage'
     * @return string notification
     */
    function getNotice($error = '', $prefix = '')
    {
        $Class = getComponent('sess');
        $sess  = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;

        $form     = getConfig('form');
        $errorKey = 'errorMessage';

        if(empty($error)) // If parameter empty check last sess flash notice
        {
            $error = $sess->getFlash($prefix.'_lastNotice');
        }

        switch ($error)  // get custom notice
        {
            case 'error':
                $errorKey = 'errorMessage';
                $notice = $sess->getFlash($prefix.'_errorMessage');
                break;
            
            case 'success':
                $errorKey = 'successMessage';
                $notice = $sess->getFlash($prefix.'_successMessage');
                break;

            case 'info':
                $errorKey = 'infoMessage';
                $notice = $sess->getFlash($prefix.'_infoMessage');
                break;

            default:
                $errorKey = 'errorMessage';
                $notice = $sess->getFlash($prefix.'_errorMessage');
                break;
        }

        if( ! empty($notice))
        {
            return sprintf($form['notifications'][$errorKey], $notice);
        }

        return;
    }

}