<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     */
    function setNotice($message, $key = 'error', $prefix = '')
    {
        $Class = getComponent('sess');
        $sess  = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;
        
        switch ($key)  // set custom notice
        {
            case 'error':
                $sess->setFlash($prefix.'_errorMessage', $message);
                break;
            
            case 'success':
                $sess->setFlash($prefix.'_successMessage', $message);
                break;

            case 'info':
                $sess->setFlash($prefix.'_infoMessage', $message);
                break;

            default:
                $sess->setFlash($prefix.'_errorMessage', $message);
                break;
        }

        $sess->setFlash($prefix.'_lastNotice', $key);  // keep last notice in memory
    }
    
}