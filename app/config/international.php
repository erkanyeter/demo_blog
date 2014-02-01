<?php

/*
|--------------------------------------------------------------------------
| International Package
|--------------------------------------------------------------------------
| 
| Configure set international package options.
|
*/

$international = array(

		'cookie_lang_name'   => 'langName',  // Cookie name e.g. $_COOKIE['langName'];
		'cookie_lang_code'   => 'langCode',  // Cookie name e.g. $_COOKIE['langCode'];
		'uri_get_name'       => 'langCode';  // Uri query string name e.g. http://example.com/home?langCode=en
		'uri_segment_number' => 1;  		 // Uri segment number e.g. http://example.com/home/en
		
		// ISO-639 Language Codes
		// Table 20-2 ISO-639 Language Codes
		// 
		// http://docs.oracle.com/cd/E13214_01/wli/docs92/xref/xqisocodes.html

		'languages' => = array(
								'ab_AB' => 'abkhazian',
								'en_US' => 'english',
								'tr_TR' => 'turkish',
								'de_DE' => 'deutsch',
								'es_ES' => 'spanish',
								);
);

/* End of file international.php */
/* Location: ./app/config/international.php */