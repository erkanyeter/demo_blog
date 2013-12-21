<?php
namespace Html\Src {

    // ------------------------------------------------------------------------ 

    /**
     * Parse Regex Css and Js source.
     * 
     * @param  string $src regex string
     * @param  array $exp explode array
     * @return array
     */
    function _parseRegex($src, $exp)
    {
        $data = array();
        $data['includeFiles'] = array();
        $data['excludeFiles'] = array();

        if(strpos($exp[1], '^(') === 0)  // remove unwanted files
        {
            $matches = array();
            if(preg_match('|\^\((.*)\)|', $src, $matches))
            {
                $data['excludeFiles'] = explode('|', $matches[1]);
            }
        }
        elseif(strpos($exp[1], '(') === 0)
        {
            $matches = array();
            if(preg_match('|\((.*)\)|', $src, $matches))
            {
                $data['includeFiles'] = explode('|', $matches[1]);
            }
        }

        return $data;
    }

}