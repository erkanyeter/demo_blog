<?php

namespace Obullo\Html;

/**
 * Html Class.
 * 
 * Control static files like css, js & images.
 * 
 * @category  Html
 * @package   Html
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/html
 */
Class Html
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;
        
        $this->logger = $c['logger'];
        $this->logger->debug('Html Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Build css files.
     *
     * @param string  $href       url
     * @param string  $tit        title or $sort of directory list
     * @param string  $media      media
     * @param string  $rel        rel
     * @param boolean $index_page php access
     * 
     * @return   string
     */
    public function css($href, $tit = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        $title = is_string($tit) ? $tit : ''; // is reverse sort true ?

        if (strpos($href, '/*') !== false) {   // Is it folder ?
            $files = '';
            $exp = explode('/*', $href);
            $data = $this->_parseRegex($href, $exp);
            $source_dir = ASSETS . 'css' . DS . str_replace('/', DS, $exp[0]);

            foreach (scandir($source_dir, ($tit === true) ? 1 : 0) as $filename) {
                if (pathinfo($source_dir . $filename, PATHINFO_EXTENSION) == 'css') {
                    if (count($data['includeFiles']) > 0 AND in_array($filename, $data['includeFiles'])) {
                        $files .= $this->_css($exp[0] . '/' . $filename, $title, $media, $rel, $index_page = false);
                    }
                    if (count($data['excludeFiles']) > 0 AND !in_array($filename, $data['excludeFiles'])) {
                        $files .= $this->_css($exp[0] . '/' . $filename, $title, $media, $rel, $index_page = false);
                    }
                    if (count($data['includeFiles']) == 0 AND count($data['excludeFiles']) == 0) {
                        $files .= $this->_css($exp[0] . '/' . $filename, $title, $media, $rel, $index_page = false);
                    }
                }
            }
            return $files;
        }
        return $this->_css($href, $title, $media, $rel, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
     * Build css link
     * 
     * @param string  $href       css url
     * @param string  $title      title     
     * @param string  $media      media    
     * @param string  $rel        rel = 'stylesheet'
     * @param boolean $index_page php access
     * 
     * @return string             
     */
    private function _css($href, $title = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        global $c;

        $link = '<link ';
        $ext = 'css';

        if (strpos($href, 'js/') === 0) {
            $ext = 'js';
            $href = substr($href, 3);
        }

        $href = ltrim($href, '/');  // remove first slash

        if (strpos($href, '://') !== false) {
            $link .= ' href="' . $href . '" ';
        } elseif ($index_page === true) {
            $link .= ' href="' . $c['uri']->getSiteUrl($href, false) . '" ';
        } else {
            $link .= ' href="' . self::_getAssetPath($href, '', $ext) . '" ';
        }

        $link .= 'rel="' . $rel . '" type="text/css" ';

        if ($media != '') {
            $link .= 'media="' . $media . '" ';
        }
        if ($title != '') {
            $link .= 'title="' . $title . '" ';
        }
        $link .= "/>\n";
        $link = str_replace(DS, '/', $link);

        return $link;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Get assets directory path
     *
     * @param string $file       url
     * @param string $extra_path extra path
     * @param string $ext        extension ( css or js )
     * 
     * @return   string | false
     */
    public static function _getAssetPath($file, $extra_path = '', $ext = '')
    {
        global $c;

        $paths = array();
        if (strpos($file, '/') !== false) {
            $paths = explode('/', $file);
            $file = array_pop($paths);
        }
        $sub_path = '';
        if (count($paths) > 0) {
            $sub_path = implode('/', $paths) . '/';      // .assets/css/sub/welcome.css  sub dir support
        }
        $folder = $ext . '/';
        if ($extra_path != '') {
            $extra_path = trim($extra_path, '/') . '/';
            $folder = '';
        }
        $assets_url = str_replace(DS, '/', ASSETS);
        $assets_url = str_replace(ROOT, '', ASSETS);

        return $c['uri']->getAssetsUrl('', false) . $assets_url . $extra_path . $folder . $sub_path . $file;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Image
     *
     * Generates an <img /> element
     *
     * @param mixed   $src        folder image path via filename
     * @param string  $attributes attributes
     * @param boolean $index_page index page
     * 
     * @return   string
     */
    public function img($src = '', $attributes = '', $index_page = false)
    {
        global $c;

        if (!is_array($src)) {
            $src = array('src' => $src);
        }
        $img = '<img';
        foreach ($src as $k => $v) {
            $v = ltrim($v, '/');   // remove first slash
            if ($k == 'src' AND strpos($v, '://') === false) {
                if ($index_page === true) {
                    $img .= ' src="' . $c['uri']->getSiteUrl($v, false) . '" ';
                } else {
                    $img .= ' src="' . self::_getAssetPath($v, 'images') . '" ';
                }
            } else {
                $img .= " $k=\"$v\" ";   // for http://
            }
        }
        $img .= $attributes . ' />';
        return $img;
    }

    // ------------------------------------------------------------------------


    /**
     * Build js files
     * 
     * @param string  $src        js href
     * @param string  $args       arguments
     * @param string  $type       text/javascript
     * @param boolean $index_page php access
     * 
     * @return string
     */
    public function js($src, $args = '', $type = 'text/javascript', $index_page = false)
    {
        $arguments = is_string($args) ? $args : '';  // is reverse sort true ?

        if (strpos($src, '/*') !== false) {  // Is it folder ?
            $files = '';
            $exp   = explode('/*', $src);
            $data  = $this->_parseRegex($src, $exp);
            $source_dir = ASSETS . 'js' . DS . str_replace('/', DS, $exp[0]);

            foreach (scandir($source_dir, ($args === true) ? 1 : 0) as $filename) {

                if (pathinfo($source_dir . $filename, PATHINFO_EXTENSION) == 'js') {
                    if (count($data['includeFiles']) > 0 AND in_array($filename, $data['includeFiles'])) {
                        $files .= $this->_js($exp[0] . '/' . $filename, $arguments, $type, $index_page = false);
                    }
                    if (count($data['excludeFiles']) > 0 AND !in_array($filename, $data['excludeFiles'])) {
                        $files .= $this->_js($exp[0] . '/' . $filename, $arguments, $type, $index_page = false);
                    }
                    if (count($data['includeFiles']) == 0 AND count($data['excludeFiles']) == 0) {
                        $files .= $this->_js($exp[0] . '/' . $filename, $arguments, $type, $index_page = false);
                    }
                }
            }
            return $files;
        }
        return $this->_js($src, $arguments, $type, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
     * Buil js files.
     * 
     * @param string  $src        js url
     * @param string  $arguments  arguments
     * @param string  $type       text/javscript
     * @param boolean $index_page php access
     * 
     * @return string
     */
    private function _js($src, $arguments = '', $type = 'text/javascript', $index_page = false)
    {
        global $c;

        $link = '<script type="' . $type . '" ';
        $src = ltrim($src, '/');   // remove first slash

        if (strpos($src, '://') !== false) {
            $link .= ' src="' . $src . '" ';
        } elseif ($index_page === true) {  // .js file as PHP
            $link .= ' src="' . $c['uri']->getSiteUrl($src, false) . '" ';
        } else {
            $link .= ' src="' . self::_getAssetPath($src, '', 'js') . '" ';
        }
        $link .= $arguments;
        $link .= "></script>\n";

        $link = str_replace(DS, '/', $link);
        return $link;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Parse Regex Css and Js source.
     * 
     * @param string $src regex   string
     * @param array  $exp explode array
     * 
     * @return array
     */
    private function _parseRegex($src, $exp)
    {
        $data = array();
        $data['includeFiles'] = array();
        $data['excludeFiles'] = array();

        if (strpos($exp[1], '^(') === 0) { // remove unwanted files
            $matches = array();
            if (preg_match('|\^\((.*)\)|', $src, $matches)) {
                $data['excludeFiles'] = explode('|', $matches[1]);
            }
        } elseif (strpos($exp[1], '(') === 0) {
            $matches = array();
            if (preg_match('|\((.*)\)|', $src, $matches)) {
                $data['includeFiles'] = explode('|', $matches[1]);
            }
        }
        return $data;
    }

}

// END Html.php File
/* End of file Html.php

/* Location: .Obullo/Html/Html.php */