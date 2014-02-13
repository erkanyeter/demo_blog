<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     * @return void
     */
    function setNotice($message, $key = '0', $suffix = null)
    {
        $Class  = getComponent('sess');
        $sess   = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;
        $suffix = ($suffix === null) ? uniqid() : $suffix;

        switch ($key)  // set custom notice
        {
            case '0':
                $sess->setFlash('errorMessage_'.$suffix, $message);
                break;
            
            case '1':
                $sess->setFlash('successMessage_'.$suffix, $message);
                break;

            case '2':
                $sess->setFlash('infoMessage_'.$suffix, $message);
                break;
        }
    }
    
}