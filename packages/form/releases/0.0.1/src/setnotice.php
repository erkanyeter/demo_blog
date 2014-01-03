<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     * @return void
     */
    function setNotice($message, $key = 'error', $suffix = null)
    {
        $Class  = getComponent('sess');
        $sess   = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;
        $suffix = ($suffix === null) ? uniqid() : $suffix;

        switch ($key)  // set custom notice
        {
            case 'error':
                $sess->setFlash('errorMessage_'.$suffix, $message);
                break;
            
            case 'success':
                $sess->setFlash('successMessage_'.$suffix, $message);
                break;

            case 'info':
                $sess->setFlash('infoMessage_'.$suffix, $message);
                break;

            default:
                $sess->setFlash('errorMessage_'.$suffix, $message);
                break;
        }
    }
    
}