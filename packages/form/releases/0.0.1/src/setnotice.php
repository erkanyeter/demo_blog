<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     */
    function setNotice($message)
    {
        $Class = getComponent('sess');
        $sess  = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;

        $sess->setFlash('_validatorNotice', $message);
    }
    
}