<?php
namespace Html\Src {

    // --------------------------------------------------------------------
    
    /**
    * Build css files.
    *
    * @param    string $href
    * @param    string $tit title or $sort of directory list
    * @param    string $media
    * @param    string $rel
    * @param    boolean $index_page
    * @return   string
    */
    function css($href, $tit = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        $title = is_string($tit) ? $tit : ''; // is reverse sort true ?

        if(strpos($href, '/*') !== false)   // Is it folder ?
        {
            $files      = '';
            $exp        = explode('/*', $href);
            $data       = getInstance()->html->_parseRegex($src, $exp);
            $source_dir = ASSETS.'css'. DS . str_replace('/', DS, $exp[0]);

            foreach (scandir($source_dir, ($tit === true) ? 1 : 0) as $filename)
            {   
                if(pathinfo($source_dir.$filename, PATHINFO_EXTENSION) == 'css')
                {
                    if(count($data['includeFiles']) > 0 AND in_array($filename, $data['includeFiles']))
                    {
                        $files .= _css($exp[0].'/'.$filename, $title, $media, $rel, $index_page = false);
                    }
                    
                    if(count($data['excludeFiles']) > 0 AND ! in_array($filename, $data['excludeFiles']))
                    {
                        $files .= _css($exp[0].'/'.$filename, $title, $media, $rel, $index_page = false);
                    }
                    
                    if(count($data['includeFiles']) == 0 AND count($data['excludeFiles']) == 0)
                    {
                        $files .= _css($exp[0].'/'.$filename, $title, $media, $rel, $index_page = false);
                    }
                }
            }

            return $files;
        }

        return _css($href, $title, $media, $rel, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
     * Build css link
     * 
     * @param  string  $href       
     * @param  string  $title      
     * @param  string  $media     
     * @param  string  $rel        
     * @param  boolean $index_page 
     * @return string             
     */
    function _css($href, $title = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        $link = '<link ';           
        $ext  = 'css';
        
        if(strpos($href, 'js/') === 0)
        {
            $ext  = 'js';
            $href = substr($href, 3);
        }

        $href = ltrim($href, '/');  // remove first slash

        if ( strpos($href, '://') !== false)
        {
            $link .= ' href="'.$href.'" ';
        }
        elseif ($index_page === true)
        {
            $link .= ' href="'. getInstance()->uri->getSiteUrl($href, false) .'" ';
        }
        else
        {
            $link .= ' href="'. \Html::_getAssetPath($href, $extra_path = '', $ext) .'" ';
        }

        $link .= 'rel="'.$rel.'" type="text/css" ';

        if ($media    != '')
        {
            $link .= 'media="'.$media.'" ';
        }

        if ($title    != '')
        {
            $link .= 'title="'.$title.'" ';
        }

        $link .= "/>\n";
        
        return $link;
    }    

}