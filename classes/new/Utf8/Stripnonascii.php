<?php
Class Utf8_StripNonAscii {

    // ------------------------------------------------------------------------
    
    /**
    * Strips out all non-7bit ASCII bytes.
    *
    *     $str = $this->utf8->stripNonAscii($str);
    *
    * @param   string  string to clean
    * @return  string
    */
    public function __invoke($str)
    {
        return preg_replace('/[^\x00-\x7F]+/S', '', $str);
    }

}

/* End of file stripnonascii.php */
/* Location: ./packages/utf8/releases/0.0.1/src/stripnonascii.php */