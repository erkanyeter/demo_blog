<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
    * We use this function in HMVC library.
    *
    * @param mixed $uri
    */
    function setUriString($str = '', $filter = true)
    {
        if($filter) // Filter out control characters
        {
            $str = removeInvisibleCharacters($str, false);
        }
        
        \Uri::getInstance()->uri_string = ($str == '/') ? '' : $str;  // If the URI contains only a slash we'll kill it
    }

}