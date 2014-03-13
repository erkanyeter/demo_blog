<?php

/*
|--------------------------------------------------------------------------
| Framework Configuration
|--------------------------------------------------------------------------
|
| Configure General Options
|
*/
$config = array(
    
    // Url
    'base_url'     => '/',        // Base Url "/" URL of your framework root, generally a '/' trailing slash.   
    'assets_url'   => '/',        // Assets Url of your framework generally a '/' trailing slash.
    
    // Uri Protocol
    'uri_protocol' => 'AUTO',     // Auto detects the URI protocol * Default option is 'AUTO', 
                                  // other options: REQUEST_URI, QUERY_STRING, PATH_INFO.

                                  // Note : If your links do not seem to work and try to change your uri_protocol 
                                  // with one of these options: REQUEST_URI, QUERY_STRING, PATH_INFO
                                  // Example Usage of Query Strings- http://example.com/login?param=1&param2=yes
    // Errors & Debugging
    'error_reporting' => 1,                                           // 'E_ALL ^ E_NOTICE'; // 'E_ALL ^ (E_NOTICE | E_WARNING | E_EXCEPTION | E_DATABASE)';
    'debug_backtrace' => array('enabled' => 'E_ALL', 'padding' => 3), // Debug backtrace help you to fast development.
    
    // Environments
    'environment_config_files' => array(             // Defined config files for each environments.
                                        'config',
                                        'database',
                                        'routes',
                                        'sess',
                                        'logger',
                                        'logger_mongo',
                                        'mongo'
                                ),
    // Log Severities:
    // ---------------------------------------------------
    // emergency (0) : Emergency: system is unusable.
    // alert (1)     : Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
    // critical (2)  : Critical conditions. Example: Application component unavailable, unexpected exception.
    // error (3)     : Runtime errors that do not require immediate action but should typically be logged and monitored.
    // warning (4)   : Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
    // notice (5)    : Normal but significant events.
    // info (6)      : Interesting events. Examples: User logs in, SQL logs, Application Benchmarks.
    // debug (7)     : Detailed debug information.
    // ---------------------------------------------------
    // @see Syslog Protocol http://tools.ietf.org/html/rfc5424
    // ---------------------------------------------------

    // Logging
    'log_enabled'         => true,           // On / Off logging
    'log_output'          => false,           // On / Off logger html output
    'log_threshold'       => array(0,1,2,3,4,5,6,7),          // array(0,1,2) = emergency,alert,critical
    'log_handler'         => array('file' => 'Logger_File'),  // Handler name & Package name of your driver: Logger_File, Logger_Mongo ...
    'log_queries'         => true,           // If true "all" SQL Queries gets logged.
    'log_benchmark'       => true,           // If true "all" application benchmarks gets logged.
    'log_default_channel' => 'system',       // Default channel name should be general.
    'log_line'            => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
    'log_push_handlers'   => array(                                 // Define your available push handlers.
                                    'email' => 'Logger_Email',  
                                    'mongo' => 'Logger_Mongo'
                                    ),
    // Push Example
    // ---------------------------------------------------
    // $this->logger->channel('security');
    // $this->logger->alert('Possible hacking attempt !', array('username' => $username));
    // $this->logger->push('email');  // send log data using email handler
    // $this->logger->push('mongo');  // send log data to mongo db handler
    // $this->logger->clear();  // reset to default logger configuration, default channel etc ..
    // ---------------------------------------------------

    // VERY IMPORTANT: * For a live site you'll usually only enable "Errors (1)" to be logged
    // otherwise your log files will fill up very fast.
    
    // Localization
    'translate_notice'    => false,     // If enabled translate function put 'translate:' word each of texts to help multilingual development.
    'time_reference'      => 'local',   // This pref tells the system whether to use your server's local time as the master "now" reference, or convert it to GMT.
    'default_translation' => 'en_US',   // This determines which set of language files should be used.
    'charset'             => 'UTF-8',   // This determines which character set is used by default.

                                        // Note: Make sure there is an available translation if you intend to use something other than "en_US". 

    // Rewrite Settings
    'index_page' => '',            // Typically this will be your index.php file, If mod_rewrite enabled is should be blank.
    'url_suffix' => '',            // Allows you to add a suffix to all URLs generated by Framework.

    // Uri extensions
    'uri_extensions' => array('.json','.xml'),   // e.g. : http://example.com/web_service/example.json

    // Hooks
    'enable_hooks' => false, // If you would like to use the 'hooks' feature you must enable it by etting this variable to "true".

    // Allowed URL Characters
    'permitted_uri_chars' => 'a-z 0-9~%.:_-=',   // Do not remove the "=" character other wise model_auto_sync mode will not work.
                                                // This lets you specify with a regular expression which characters are permitted within your URLs.
                                                // As a security measure you are STRONGLY encouraged to restrict URLs to as few characters as possible.
                                                // Leave blank to allow all characters -- but only if you are insane.
    // Enable Query Strings
    'enable_query_strings' => true,     // By default Framework uses search-engine friendly segment based URLs: example.com/who/what/where/
    'directory_trigger'    => 'd',      // You can optionally enable standard query string based URLs: example.com?who=me&what=something&where=here
    'controller_trigger'   => 'c',      // The other items let you set the query string "words" that will invoke your controllers and its functions:
    'function_trigger'     => 'm',      // example.com/index.php?d=directory&c=controller&m=function
    
    // Please note that some of the functions won't work as expected when
    // this feature is enabled, since Framework is designed primarily to use segment based URLs.
    
    // Secret Encryption Key
    'encryption_key' => 'write-your-secret-key',    // If you use the Encryption class you MUST set an encryption key.
    
    // Cookies
    'cookie_prefix' => '',                          // Set a prefix if you need to avoid collisions
    'cookie_domain' => '',                          // Set to .your-domain.com for site-wide cookies
    'cookie_path'   => '/',                         // Typically will be a forward slash
    'cookie_expire' => (7 * 24 * 60 * 60),          // 1 week - Cookie expire time.
    'cookie_secure' => false,                       // Cookies will only be set if a secure HTTPS connection exists.

    // VERY IMPORTANT: For all cookie_time expirations, time() function must 
    // be at the end. Otherwise session cookie functions does not work correctly.

    'global_xss_filtering' => false,                // Whether the XSS filter is always active when GET, POST or COOKIE data is encountered
    
    // Cross Site Request Forgery
    'csrf_protection'  => false,                    // Enables a CSRF cookie token to be set. When set to true, token will be
    'csrf_token_name'  => 'csrf_token_name',        // checked on a submitted form. If you are accepting user data, it is strongly
    'csrf_cookie_name' => 'csrf_cookie_name',       // recommended CSRF protection be enabled.
    'csrf_expire'      => '7200',                   // The number in seconds the token should expire.
    
    // Models & Schemas
    'model_auto_sync' => false,         // Auto sync should be enabled in development mode.
                                        // Sync tool automatically show a sync edit screen for the schema & database synchronization.
                                        // In LIVE mode you need set it to "false" because of the "database query" performance & security.
    // Reverse Proxy IPs
    'proxy_ips'       => '',

    // If your server is behind a reverse proxy, you must whitelist the proxy IP
    // addresses from which the Framework should trust the HTTP_X_FORWARDED_FOR
    // header in order to properly identify the visitor's IP address.
    // Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
    
    // Output Compression
    'compress_output' => false

    // Enables Gzip output compression for faster page loads.  When enabled,
    // the output class will test whether your server supports Gzip.
    // Even if it does, however, not all browsers support compression
    // so enable only if you are reasonably sure your visitors can handle it.
);

/* End of file config.php */
/* Location: .app/config/debug/config.php */