<?php
namespace Uri\Src {

    // --------------------------------------------------------------------

    /**
    * Site URL
    *
    * @access   public
    * @param    string    the URI string
    * @param    boolean   switch off suffix by manually
    * @return   string
    */
    function getSiteUrl($uri = '', $suffix = true)
    {
        $config    = getComponentInstance('config');
        $uriObject = getComponentInstance('uri');

        if (is_array($uri))
        {
            $uri = implode('/', $uri);
        }
        
        if ($uri == '')
        {
            return $uriObject->getBaseUrl() . $config->getItem('index_page');
        }
        else
        {
            $suffix = ($config->getItem('url_suffix') == false OR $suffix == false) ? '' : $config->getItem('url_suffix');
            
            return $uriObject->getBaseUrl() . $config->getSlashItem('index_page'). trim($uri, '/') . $suffix;
        }
    }

}