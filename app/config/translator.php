<?php

/*
|--------------------------------------------------------------------------
| Translator Config
|--------------------------------------------------------------------------
| 
| Configure set translator package options.
|
*/
$config =  array(
      
      // Http Settings
      'query_string' => array('enabled' => true, 'key' => 'lang'), // Uri query string name e.g. http://example.com/home?lang=en_US
      'uri_segment'  => array('enabled' => false,'key' => 'lang', 'segment_number' => 1), // Uri segment number e.g. http://example.com/home/en_US

      // Cookies
      'cookie_prefix' => 'translator_',
      'cookie_domain' => '',         // Set to .your-domain.com for site-wide cookies
      'cookie_path'   => '',         // Typically will be a forward slash
      'cookie_expire' => (365 * 24 * 60 * 60), // 365 day; //  @see  Cookie expire time.   http://us.php.net/strtotime
      'cookie_secure' => false,      // Cookies will only be set if a secure HTTPS connection exists.


      // Php Intl Settings          // If enabled class will use PHP locale_set_default('en_US'); function
      'locale_set_default' => true, // http://www.php.net/manual/bg/function.locale-set-default.php 
                                           
      // Iso Language Codes
      // http://www.microsoft.com/resources/msdn/goglobal/default.mspx
      'languages' => array(
                              'ab_AB' => 'abkhazian',
                              'en_US' => 'english',
                              'tr_TR' => 'turkish',
                              'de_DE' => 'deutsch',
                              'es_ES' => 'spanish',
                              )
);

/* End of file translator.php */
/* Location: ./app/config/translator.php */