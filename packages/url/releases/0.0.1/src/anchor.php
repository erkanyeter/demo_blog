<?php
namespace Url\Src {

    // ------------------------------------------------------------------------

	/**
    * Anchor Link
    *
    * Creates an anchor based on the local URL.
    *
    * @access    public
    * @param     string    the URL
    * @param     string    the link title
    * @param     mixed     any attributes
    * @param     bool      switch off suffix by manually
    * @return    string
    */
    function anchor($uri = '', $title = '', $attributes = '', $suffix = true)
    {
        $title = (string) $title;
        
        $sharp = false;
        if(strpos($uri, '#') > 0)  // ' # ' sharp support for anchors.
        {
            $sharp_uri = explode('#', $uri);
            $uri       = $sharp_uri[0];
            $sharp     = true;
        }

        $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? getInstance()->uri->siteUrl($uri, $suffix) : $uri;

        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes != '')
        {
            $attributes = getInstance()->url->_parseAttributes($attributes);
        }

        if($sharp == true AND isset($sharp_uri[1]))
        {
            $site_url = $site_url.'#'.$sharp_uri[1];  // sharp support
        }

        return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
    }

}