<?php
Class Utf8_Ucfirst {

    // ------------------------------------------------------------------------

    /**
    * UTF8 ucfirst
    *
    * @access  public
    * @param   string $str
    * @return  string
    */
    public function __invoke($str)
    {
        $utf8 = getInstance()->utf8;
        
        if($utf8->isAscii($str))
        {
            return ucfirst($str);
        }

        preg_match('/^(.?)(.*)$/us', $str, $matches);

        return $utf8->_strtoupper($matches[1]).$matches[2];
    }

}

/* End of file _ucfirst.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_ucfirst.php */