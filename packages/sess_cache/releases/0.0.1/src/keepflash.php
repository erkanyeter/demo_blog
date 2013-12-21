<?php
namespace Sess_Cache\Src {

    // ------------------------------------------------------------------------

    /**
    * Keeps existing flashdata available to next request.
    *
    * @access   public
    * @param    string
    * @return   void
    */
    function keepFlash($key) // ( obullo changes ...)
    {
        $sess = \Sess::$driver;

        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdataSweep()
        // Note the function will return false if the $key 
        // provided cannot be found
        $old_flashdata_key = $sess->flashdata_key.':old:'.$key;
        $value = $sess->get($old_flashdata_key);

        $new_flashdata_key = $sess->flashdata_key.':new:'.$key;
        $sess->set($new_flashdata_key, $value);
    }
    
}