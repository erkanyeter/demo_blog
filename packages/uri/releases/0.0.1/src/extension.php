<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
    * Get extension of uri
    *
    * @return  string
    */
    function extension()
    {
        $uriObject = getComponentInstance('uri');
        
        if(isset($uriObject->extension))
        {
            return $uriObject->extension;
        }

        return str_replace('.', '', EXT);
    }

}