<?php
Class Utf8_TransliterateAll {

    // ------------------------------------------------------------------------
    
    /**
     * Transliterate the all foreign 
     * characters
     * 
     * @param  string $str
     * @param  string $direction
     * @param  string $locale
     * @return string
     */
    public function __invoke($str, $direction = 'general', $locale = 'en_US')
    {
        $transliteration = getConfig('i18n/'.$locale.'/transliteration');

        $patterns = array_map(function($key){

            return '/'.trim($key,'/').'/'; // Build patterns.

        }, array_keys($transliteration[$direction]));
    
        // Fix the foreign characters usign transliteration tables.
        $str = preg_replace($patterns, array_values($transliteration[$direction]), $str);

        return $str;
    }
    
}

/* End of file transliterateall.php */
/* Location: ./packages/utf8/releases/0.0.1/src/transliterateall.php */