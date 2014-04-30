<?php

/**
 * Obullo Common Functions
 * 
 * @category  Core
 * @package   Obullo
 * @author    Obullo Lvc Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/obullo
 */

$c = new Obullo\Container\Pimple;

require OBULLO_CONFIG;

$c['config'] = function () {
    return new Obullo\Config\Config;
};

/**
 * Clean Input Data
 *
 * This is a helper function. It escapes data and
 * standardizes newline characters to \n
 *
 * @param string $str input
 * 
 * @return   string
 */
function cleanInputData($str)
{
    global $c;
    if (is_array($str)) {
        $new_array = array();
        foreach ($str as $key => $val) {
            $new_array[cleanInputKeys($key)] = cleanInputData($val);
        }
        return $new_array;
    }
    $str = removeInvisibleCharacters($str); // Remove control characters
    if ($c['config']['security']['xss_filtering']) {  // Should we filter the input data?
        $str = $c['security']->xssClean($str);
    }
    return $str;
}

/**
 * Clean Keys
 *
 * This is a helper function. To prevent malicious users
 * from trying to exploit keys we make sure that keys are
 * only named with alpha-numeric text and a few other items.
 *
 * @param string $str input 
 * 
 * @return string
 */
function cleanInputKeys($str)
{
    if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
        die('Malicious Key Characters.');
    }
    return $str;
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @param string  $str         text
 * @param boolean $url_encoded encode option
 * 
 * @return string
 */
function removeInvisibleCharacters($str, $url_encoded = true)
{
    $non_displayables = array();  // every control character except newline (dec 10)
    if ($url_encoded) {           // carriage return (dec 13), and horizontal tab (dec 09)
        $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
    }
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127
    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);
    return $str;
}

// END Common.php File
/* End of file Common.php

/* Location: .Obullo/Obullo/Common.php */