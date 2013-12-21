<?php
namespace Sess_Database\Src {

    // --------------------------------------------------------------------

    /**
    * Delete a session variable from the "userdata" array
    *
    * @access    public
    * @return    void
    */       
    function remove($newdata = array(), $prefix = '')
    {
        $sess = \Sess::$driver;

        if (is_string($newdata))
        {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                unset($sess->userdata[$prefix.$key]);
            }
        }

        $sess->_write();
    }

}