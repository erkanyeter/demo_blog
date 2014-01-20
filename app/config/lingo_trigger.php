<?php

/*
|--------------------------------------------------------------------------
| Lingo Trigger
|--------------------------------------------------------------------------
| 
| Configure lingo_trigger package options.
|
*/

$lingo_trigger['cookie_lang_name']     = 'langName';  // Cookie name e.g. $_COOKIE['langName'];
$lingo_trigger['cookie_langcode_name'] = 'langCode';  // Cookie name e.g. $_COOKIE['langCode'];
$lingo_trigger['uri_get_name']         = 'langCode';  // Uri query string name e.g. http://example.com/home?langCode=en
$lingo_trigger['uri_segment_number']   = 1;			  // Uri segment number e.g. http://example.com/home/en

// ISO-639 Language Codes
// Table 20-2 ISO-639 Language Codes
// 
// http://docs.oracle.com/cd/E13214_01/wli/docs92/xref/xqisocodes.html

$lingo_trigger['languages'] = array(
								'abkhazian' => 'ab',
								'afar'      => 'aa',
								'english'   => 'en',
								'turkish'   => 'tr',
								'deutsch'   => 'de',
								);