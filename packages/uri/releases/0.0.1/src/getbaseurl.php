<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
    * Get Base URL
    * Returns base_url
    * 
    * @access public
    * @param string $uri
    * @return string
    */
    function getBaseUrl($uri = '')
    {
        return \Config::getInstance()->getSlashItem('base_url').ltrim($uri,'/');
    }
    
}