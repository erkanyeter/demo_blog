<?php

/*
|--------------------------------------------------------------------------
| Culture
|--------------------------------------------------------------------------
| 
| Configure culture package options.
|
*/

$culture['cookie_lang_name']     = 'langName';  // Cookie name e.g. $_COOKIE['langName'];
$culture['cookie_langcode_name'] = 'langCode';  // Cookie name e.g. $_COOKIE['langCode'];
$culture['uri_get_name']         = 'langCode';  // Uri query string name e.g. http://example.com/home?langCode=en
$culture['uri_segment_number']   = 1;			  // Uri segment number e.g. http://example.com/home/en

// ISO-639 Language Codes
// Table 20-2 ISO-639 Language Codes
// 
// http://docs.oracle.com/cd/E13214_01/wli/docs92/xref/xqisocodes.html

$culture['languages'] = array(
								'abkhazian' => 'ab_AB',
								'afar'      => 'aa_AA',
								'english'   => 'en_US',
								'turkish'   => 'tr_TR',
								'deutsch'   => 'de_DE',
								);