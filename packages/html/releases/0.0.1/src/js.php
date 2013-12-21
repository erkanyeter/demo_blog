<?php
namespace Html\Src {

    // ------------------------------------------------------------------------

    /**
    * Build js files.
    *
    * @param    string $href
    * @param    mixed $args js arguments or $sort of directory list
    * @param    string $media
    * @param    string $rel
    * @param    boolean $index_page
    * @return   string
    */
    function js($src, $args = '', $type = 'text/javascript', $index_page = false)
    {
        $arguments = is_string($args) ? $args : '';  // is reverse sort true ?

        if(strpos($src, '/*') !== false)  // Is it folder ?
        {
            $files = '';
            $exp   = explode('/*', $src);
            $data  = getInstance()->html->_parseRegex($src, $exp);
            $source_dir = ASSETS .'js'. DS . str_replace('/', DS, $exp[0]);

            foreach (scandir($source_dir, ($args === true) ? 1 : 0) as $filename)
            {   
                if(pathinfo($source_dir.$filename, PATHINFO_EXTENSION) == 'js')
                {
                    if( count($data['includeFiles']) > 0 AND in_array($filename, $data['includeFiles']))
                    {
                        $files .= _js($exp[0].'/'.$filename, $arguments, $type, $index_page = false);
                    }
                    
                    if( count($data['excludeFiles']) > 0 AND ! in_array($filename, $data['excludeFiles']))
                    {
                        $files .= _js($exp[0].'/'.$filename, $arguments, $type, $index_page = false);
                    }
                    
                    if(count($data['includeFiles']) == 0 AND count($data['excludeFiles']) == 0)
                    {
                        $files .= _js($exp[0].'/'.$filename, $arguments, $type, $index_page = false);
                    }
                }
            }

            return $files;
        }

        return _js($src, $arguments, $type, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
     * Build js link
     * 
     * @param  string  $src       
     * @param  string  $arguments 
     * @param  string  $type       
     * @param  boolean $index_page
     * @return string             
     */
    function _js($src, $arguments = '', $type = 'text/javascript', $index_page = false)
    {
        $link = '<script type="'.$type.'" ';        
        $src  = ltrim($src, '/');   // remove first slash

        if ( strpos($src, '://') !== false)
        {
            $link .= ' src="'. $src .'" ';
        }
        elseif ($index_page === true)  // .js file as PHP
        {
            $link .= ' src="'. getInstance()->uri->siteUrl($src, false) .'" ';
        }
        else
        {
            $link .= ' src="'. \Html::_getAssetPath($src, $extra_path = '', 'js') .'" ';
        }

        $link .= $arguments;
        $link .= "></script>\n";
       
        return $link;
    }

}