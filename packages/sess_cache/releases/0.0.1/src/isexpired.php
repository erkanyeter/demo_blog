<?php
namespace Sess_Cache\Src {

    // --------------------------------------------------------------------
    
    /**
     * Check session id is expired
     * 
     * @return boolean 
     */
    function isExpired()
    {
        $sess = \Sess::$driver;

        if ( ! isset( $sess->userdata['last_activity'] ) )
        {
            return false;
        }

        $expire = $this->now - $this->expiration;
        
        if ( $sess->userdata['last_activity'] <= $expire )
        {
            return true;
        }

        return false;
    }

}