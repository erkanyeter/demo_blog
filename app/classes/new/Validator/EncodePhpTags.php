<?php
Class Validator_EncodePhpTags {

    // --------------------------------------------------------------------
    
    /**
     * Convert PHP tags to entities
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    public function __invoke($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }

}