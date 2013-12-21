<?php
namespace Sess_Database\Src {

    // ------------------------------------------------------------------------

    /**
    * Add or change flashdata, only available
    * until the next request
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */
    function setFlash($newdata = array(), $newval = '')  // ( obullo changes ... )
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
                $flashdata_key = $sess->flashdata_key.':new:'.$key;
                $sess->set($flashdata_key, $val);
            }
        }
    } 

}