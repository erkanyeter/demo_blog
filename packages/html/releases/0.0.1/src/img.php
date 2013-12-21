<?php
namespace Html\Src {

    // ------------------------------------------------------------------------

    /**
    * Image
    *
    * Generates an <img /> element
    *
    * @access   public
    * @param    mixed    $src  sources folder image path via filename
    * @param    boolean  $index_page
    * @param    string   $attributes
    * @version  0.1
    * @return   string
    */
    function img($src = '', $attributes = '', $index_page = false)
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        $img = '<img';

        foreach ($src as $k => $v)
        {
            $v = ltrim($v, '/');   // remove first slash
            
            if ($k == 'src' AND strpos($v, '://') === false)
            {
                if ($index_page === true)
                {
                    $img .= ' src="'. getInstance()->uri->siteUrl($v, false).'" ';
                }
                else
                {
                    $img .= ' src="' . \Html::_getAssetPath($v, 'images'. $extra_path = '') .'" ';
                }
            }
            else
            {
                $img .= " $k=\"$v\" ";   // for http://
            }
        }

        $img .= $attributes . ' />';

        return $img;
    }

}