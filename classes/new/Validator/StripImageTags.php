<?php
Class Validator_StripImageTags {

    // --------------------------------------------------------------------
    
    /**
     * Strip Image Tags
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    public function __invoke($str)
    {
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

        return $str;
    }
    
}