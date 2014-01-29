<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
    * Get Assets URL
    * 
    * @access public
    * @param string $uri
    * @return string
    */
    function getAssetsUrl($uri = '')
    {
        return getComponentInstance('config')->getSlashItem('assets_url').ltrim($uri,'/');
    }
    
}