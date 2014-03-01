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

    // Http Settings
    'enable_query_string' => array('enabled' => true, 'key' => 'langCode'), // Uri query string name e.g. http://example.com/home?langCode=en
    'enable_uri_segment'  => array('enabled' => false,'key' => 'langCode', 'segment' => 1), // Uri segment number e.g. http://example.com/home/en   

    // Cookies
    'cookie_prefix' => 'intl_',
    'cookie_domain' => '',          // Set to .your-domain.com for site-wide cookies
    'cookie_path'   => '',          // Typically will be a forward slash
    'cookie_expire' => (365 * 24 * 60 * 60), // 365 day; //  @see  Cookie expire time.   http://us.php.net/strtotime
    'cookie_secure' => false,       // Cookies will only be set if a secure HTTPS connection exists.


    // Php Intl Settings
    'enable_locale_set_default' => true, // If enabled class will use PHP locale_set_default('en_US'); function
                                         // http://www.php.net/manual/bg/function.locale-set-default.php 

    // ISO-639 Language Codes
    // Table 20-2 ISO-639 Language Codes
    // 
    // http://docs.oracle.com/cd/E13214_01/wli/docs92/xref/xqisocodes.html

    'languages' => array(
                            'ab_AB' => 'abkhazian',
                            'en_US' => 'english',
                            'tr_TR' => 'turkish',
                            'de_DE' => 'deutsch',
                            'es_ES' => 'spanish',
                            )
);

/* End of file international.php */
/* Location: ./app/config/international.php */