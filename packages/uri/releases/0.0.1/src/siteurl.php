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
    function siteUrl($uri = '', $suffix = true)
    {
        $config    = getComponentInstance('config');
        $uriObject = getComponentInstance('uri');

        if (is_array($uri))
        {
            $uri = implode('/', $uri);
        }
        
        if ($uri == '')
        {
            return $uriObject->baseUrl() . $config->item('index_page');
        }
        else
        {
            $suffix = ($config->item('url_suffix') == false OR $suffix == false) ? '' : $config->item('url_suffix');
            
            return $uriObject->baseUrl() . $config->slashItem('index_page'). trim($uri, '/') . $suffix;
        }
    }

}