<?php

namespace Obullo\Url;

/**
 * Url Class
 *
 * Modeled after Codeigniter Url helper.
 * 
 * @category  Url
 * @package   Url
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/url
 */
Class Url
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;
        
        $this->uri = $c['uri'];
        $c['logger']->debug('Url Class Initialized');
    }

    /**
     * Anchor Link
     *
     * Creates an anchor based on the local URL.
     *
     * @param string $uri        the URL
     * @param string $title      the link title
     * @param mixed  $attributes any attributes
     * @param bool   $suffix     switch off suffix by manually
     * 
     * @return    string
     */
    public function anchor($uri = '', $title = '', $attributes = '', $suffix = true)
    {
        $title = (string) $title;
        $sharp = false;

        $siteUri = $this->uri->getSiteUrl($uri, $suffix);

        // ' # ' sharp support

        if (strpos($uri, '#') === 0) {  // If we have "#" begining of the uri
            return '<a href="' . trim($siteUri, '/') . '"' . $attributes . '>' . $title . '</a>';
        } elseif (strpos($uri, '#') > 0) {
            $sharp_uri = explode('#', $uri);
            $uri       = $sharp_uri[0];
            $sharp     = true;
        }

        // "?" Question mark support
        // If we have question mark beginning of the  the uri
        // example:  example.com/?service_type=email&user_id=50
        // replace with:  example.com?service_type=email&user_id=50

        if (strpos(trim($uri, '/'), '?') === 0) { 
            $siteUri = (strpos($uri, '/') === 0) ? $siteUri : trim($siteUri, '/');
        }

        $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? $siteUri : $uri;

        if ($title == '') {
            $title = $site_url;
        }
        if ($attributes != '') {
            $attributes = self::parseAttributes($attributes);
        }
        if ($sharp == true AND isset($sharp_uri[1])) {
            $site_url = $site_url . '#' . $sharp_uri[1];  // sharp support
        }

        return '<a href="' . $site_url . '"' . $attributes . '>' . $title . '</a>';
    }
    
    /**
     * Header Redirect
     *
     * Header redirect in two flavors
     * For very fine grained control over headers, you could use the Response
     * package setHeader() function.
     * 
     * @param string  $uri                uri string
     * @param string  $method             method
     * @param integer $http_response_code response code
     * @param boolean $suffix             suffix
     * 
     * @return void
     */
    public function redirect($uri = '', $method = 'location', $http_response_code = 302, $suffix = true)
    {
        if (!preg_match('#^https?://#i', $uri)) {
            $sharp = false;
            if (strpos($uri, '#') > 0) { // ' # ' sharp support for urls. ( Obullo changes )..
                $sharp_uri = explode('#', $uri);
                $uri = $sharp_uri[0];
                $sharp = true;
            }

            $uri = $this->uri->getSiteUrl($uri, $suffix);

            if ($sharp == true AND isset($sharp_uri[1])) {
                $uri = $uri . '#' . $sharp_uri[1];
            }
        }
        if (strpos($method, '[')) {
            $index = explode('[', $method);
            $param = str_replace(']', '', $index[1]);

            header("Refresh:$param;url=" . $uri);
            return;
        }
        switch ($method) {
        case 'refresh' : header("Refresh:0;url=" . $uri);
            break;
        default : header("Location: " . $uri, true, $http_response_code);
            break;
        }
        exit;
    }

    /**
     * Parse out the attributes
     *
     * Some of the functions use this
     *
     * @param array $attributes atributes
     * @param bool  $javascript javascript attributes
     * 
     * @return string
     */
    public static function parseAttributes($attributes, $javascript = false)
    {
        if (is_string($attributes)) {
            return ($attributes != '') ? ' ' . $attributes : '';
        }
        $att = '';
        foreach ($attributes as $key => $val) {
            if ($javascript == true) {
                $att .= $key . '=' . $val . ',';
            } else {
                $att .= ' ' . $key . '="' . $val . '"';
            }
        }
        if ($javascript == true AND $att != '') {
            $att = substr($att, 0, -1);
        }

        return $att;
    }
    
    /**
     * Prep URL
     *
     * Simply adds the http:// part if missing
     *
     * @param string $str the URL
     * 
     * @return string
     */
    public function prep($str = '')
    {
        if ($str == 'http://' OR $str == '') {
            return '';
        }
        if ( ! parse_url($str, PHP_URL_SCHEME)) {
            $str = 'http://' . $str;
        }
        return $str;
    }

}

// END Url Class
/* End of file Url.php

/* Location: .Obullo/Url/Url.php */