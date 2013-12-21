<?php
namespace Sess_Database\Src {

    // --------------------------------------------------------------------

    /**
    * Destroy the current session
    *
    * @access    public
    * @return    void
    */
    function destroy()
    {
        $sess = \Sess::$driver;        

        // Db driver changes..
        // -------------------------------------------------------------------
        if(isset($sess->userdata['session_id'])) // Kill the session DB row
        {
            $sess->db->where('session_id', $sess->userdata['session_id']);
            $sess->db->delete($sess->table_name);
        }
        // -------------------------------------------------------------------
        
        // Kill the cookie
        setcookie(           
                    $sess->cookie_name, 
                    addslashes(serialize(array())), 
                    ($sess->now - 31500000), 
                    $sess->cookie_path, 
                    $sess->cookie_domain, 
                    false
        );
    }

}