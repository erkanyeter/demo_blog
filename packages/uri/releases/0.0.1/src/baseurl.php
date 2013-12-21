<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
    * Base URL
    * Returns base_url
    * 
    * @access public
    * @param string $uri
    * @return string
    */
    function baseUrl($uri = '')
    {
        return getComponentInstance('config')->slashItem('base_url').ltrim($uri,'/');
    }
    
}