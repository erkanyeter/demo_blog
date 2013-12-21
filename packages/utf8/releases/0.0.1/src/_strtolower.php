<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Makes a UTF-8 string lowercase. This is a UTF8-aware version
    * of [strtolower](http://php.net/strtolower).
    *
    *     $str = $this->utf8->strtolower($str);
    *
    * @param   string   mixed case string
    * @return  string
    */
    function _strtolower($str)
    {
        if(strpos($str, 'İ') !== false)  // İ - i problem in just one Turkish Character.
        {
            $str = str_replace('İ', 'i', $str);
        }
        
        return mb_strtolower($str, config('charset')); 
    }
    
}

/* End of file _strtolower.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strtolower.php */