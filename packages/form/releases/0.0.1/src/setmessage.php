<?php
namespace Form\Src {

    // --------------------------------------------------------------------
    
    /**
     * Set Error Message
     *
     * Lets users set their own error messages on the fly.  Note:  The key
     * name has to match the  function name that it corresponds to.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   string
     */
    function setMessage($lang, $val = '')
    {
        $validator = getInstance()->validator;
        $validator->setMessage($lang, $val);
    }

}