<?php
namespace Sess_Database\Src {

    // --------------------------------------------------------------------

    /**
    * Add or change data in the "userdata" array
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */ 
    function set($newdata = array(), $newval = '', $prefix = '')
    {
        $sess = \Sess::$driver;

        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $sess->userdata[$prefix.$key] = $val;
            }
        }

        $sess->_write();
    }

}