<?php
Class Utf8_Strtolower {

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
    public function __invoke($str)
    {
        global $config;

        return mb_strtolower($str, $config['charset']);
    }
    
}

/* End of file _strtolower.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strtolower.php */