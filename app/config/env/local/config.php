<?php
/*
|--------------------------------------------------------------------------
| Application "local" environment
|--------------------------------------------------------------------------
| Configuration file
|
*/
$config = array(
    /*
    |--------------------------------------------------------------------------
    | Http Url
    |--------------------------------------------------------------------------
    */
    'url' => array(
        'base'   => '/',         // Base Url "/" URL of your framework root, generally a '/' trailing slash. 
        'assets' => '/',         // Assets Url of your framework generally a '/' trailing slash.
        'rewrite' => array(
            'index_page' => '',  // Typically this will be your index.php file, If mod_rewrite enabled is should be blank.
            'suffix'     => '',  // Allows you to add a suffix to all URLs generated by Framework.
        )
    ),
    /*
    |--------------------------------------------------------------------------
    | Error
    |--------------------------------------------------------------------------
    */
    'error' => array(
        'reporting' => 1,       // 'E_ALL ^ E_NOTICE'; // 'E_ALL ^ (E_NOTICE | E_WARNING | E_EXCEPTION | E_DATABASE)';
        'debug'     => array(
                            'enabled' => 'E_ALL',  // Debug backtrace help you to fast development.
                            'padding' => 3
                        ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Http Uri
    |--------------------------------------------------------------------------
    */
    'uri' => array(
        'protocol' => 'AUTO',   // Auto detects the URI protocol * Default option is 'AUTO', 
                                // Options: REQUEST_URI, QUERY_STRING, PATH_INFO
                                // Example Usage of Query Strings- http://example.com/login?param=1&param2=yes
        // Allowed URL Characters
        'permitted_chars' => 'a-z 0-9~%.:_-',  // This lets you specify with a regular expression which characters are permitted within your URLs.
                                               // As a security measure you are STRONGLY encouraged to restrict URLs to as few characters as possible.
                                               // Leave blank to allow all characters -- but only if you are insane.

                                          // Query strings feature is enabled, since Framework is designed primarily to use segment based URLs.

        'query_strings'      => true,     // By default Framework uses search-engine friendly segment based URLs: example.com/who/what/where/
        'directory_trigger'  => 'd',      // You can optionally enable standard query string based URLs: example.com?who=me&what=something&where=here
        'controller_trigger' => 'c',      // The other items let you set the query string "words" that will invoke your controllers and its functions:
        'function_trigger'   => 'm',      // example.com/index.php?d=directory&c=controller&m=function

        'extensions' => array('.json','.xml'),   // e.g. : http://example.com/web_service/example.json
    ),
    /*
    |--------------------------------------------------------------------------
    | Routes 
    |--------------------------------------------------------------------------
    | Typically there is a one-to-one relationship between a URL string and its corresponding controller class/method. The segments in a
    | URL normally follow this pattern:
    |
    |    http://example.com/directory/class/method/id/
    |
    */
    'routes' => array(

            'tag/(:any)'                   => 'tag/$1',
            'post/detail/(:num)'           => 'post/detail/$1',
            'post/preview/(:num)'          => 'post/preview/$1',
            'post/update/(:num)'           => 'post/update/$1',
            'post/delete/(:num)'           => 'post/delete/$1',
            'comment/delete/(:num)'        => 'comment/delete/$1',
            'comment/update/(:num)/(:any)' => 'comment/update/$1/$2',

            'default_controller' => 'welcome/index', // This is the default controller, application call it as default
            '404_override' => '',                    // You can redirect 404 errors to specify controller

             // Controller Default Method
            'index_method' => 'index'                // This is controller default index method for all controllers.
                                                     // You should configure it before the first run of your application.
    ),
    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */
    'database' => array(
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '123456',
            'database' => 'demo_blog',
            'driver'   => '',   // optional
            'prefix'   => '',
            'dbh_port' => '',
            'char_set' => 'utf8',
            'dsn'      => '',
            'options'  => array() // array( PDO::ATTR_PERSISTENT => false ); 
    ),
    /*
    |--------------------------------------------------------------------------
    | Logger
    |--------------------------------------------------------------------------
    | Severities:
    | emergency (0) : Emergency: system is unusable.
    | alert (1)     : Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
    | critical (2)  : Critical conditions. Example: Application component unavailable, unexpected exception.
    | error (3)     : Runtime errors that do not require immediate action but should typically be logged and monitored.
    | warning (4)   : Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
    | notice (5)    : Normal but significant events.
    | info (6)      : Interesting events. Examples: User logs in, SQL logs, Application Benchmarks.
    | debug (7)     : Detailed debug information.
    | ---------------------------------------------------
    | @see Syslog Protocol http://tools.ietf.org/html/rfc5424
    | ---------------------------------------------------
    */
    'logger' =>   array(
            'enabled'   => true,       // On / Off logging
            'output'    => true,       // On / Off logger html output
            'threshold' => array(0,1,2,3,4,5,6,7),  // array(0,1,2) = emergency,alert,critical
            'queries'   => true,        // If true "all" SQL Queries gets logged.
            'benchmark' => true,        // If true "all" Application Benchmarks gets logged.
            'channel'   => 'system',    // Default channel name should be general.
            'line'      => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
            'path'      => array(
                'app'  => 'data/logs/app.log',       // file handler application log path
                'cli'  => 'data/logs/cli/app.log',   // file handler cli log path  
                'task' => 'data/logs/tasks/app.log', // file handler tasks log path
            ),
            'writers' => array(                           
                                'file' => array('handler' => 'File', 'priority' => 0),  // Define your available push handlers.
                                'null' => array('handler' => 'Null', 'priority' => 2),  // and set your Log Queue priorities
                            ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Sessions
    |--------------------------------------------------------------------------
    */
    'session' => array(
            'cookie_name'     => 'session', // The name you want for the cookie
            'expiration'      => 7200,          // The number of SECONDS you want the session to last. By default two hours. "0" is no expiration.
            'expire_on_close' => true,          // Whether to cause the session to expire automatically when the browser window is closed
            'encrypt_cookie'  => false,         // Whether to encrypt the cookie
            'match_ip'        => false,         // Whether to match the user's IP address when reading the session data
            'match_useragent' => true,          // Whether to match the User Agent when reading the session data
            'time_to_update'  => 1,             // How many seconds between Framework refreshing "Session" Information"
            'params' => array(
                'session.gc_divisor'      => 100,   // Configure garbage collection
                'session.gc_maxlifetime'  => 7200,  // REMOVE THIS AND USE "expiration"
                'session.cookie_lifetime' => 0,
                'session.save_handler'    => 'redis',
                'session.save_path'       => 'tcp://10.0.0.154:6379?auth=aZX0bjL',
                // Database container
                // 'session.db'         => 'Db',         // Container Settings, Db, Cache; Mongo;
                // 'session.tablename'  => 'sessions',   // The name of the session database table
            ),
    ),
    /*
    |--------------------------------------------------------------------------
    | I18n
    |--------------------------------------------------------------------------
    | Locale Code Reference
    | http://www.microsoft.com/resources/msdn/goglobal/default.mspx
    */
    'locale' => array(
        'default_translation' => 'en_US',   // This determines which set of language files should be used.
        'translate_notice'    => false,     // puts 'translate:' texts everywhere it is help you for multilingual development.
        'time_reference'      => 'local',   // This pref tells the system whether to use your server's local time as the master "now" reference, or convert it to GMT.
        'charset'             => 'UTF-8',   // This determines which character set is used by default.
     ),
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache' =>  array(
           'servers' => array(
                              'hostname' => '127.0.0.1',
                              'port'     => '6379',
                               // 'timeout'  => '2.5'   // 2.5 sec timeout, just for redis cache
                              'weight'   => '1'         // The weight parameter effects the consistent hashing 
                                                        // used to determine which server to read/write keys from.
                              ),
            'auth' =>  '',                         // Just redis cache for connection password
            'cache_path' =>  '/data/temp/cache/',  // Just cache file .data/temp/cache
    ),
    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    */
    'hooks' => array(
        'enabled' => false,     // If you would like to use the 'hooks' feature you must enable it 
                                // by etting this variable to "true".
    ),
    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => array(
        'encryption_key'   => 'write-your-secret-key',  // If you use the Encryption class you MUST set an encryption key.
        'xss_filtering'    => false,                    // Whether the XSS filter is always active when GET, POST or COOKIE data is encountered
        'csrf' => array(                      
            'protection'  => false,          // Enables a CSRF cookie token to be set. When set to true, token will be
            'token_name'  => 'csrf_token',   // checked on a submitted form. If you are accepting user data, it is strongly
            'cookie_name' => 'csrf_cookie',  // recommended CSRF protection be enabled.
            'expire'      => '7200',         // The number in seconds the token should expire.
         ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Cookies
    |--------------------------------------------------------------------------
    */
    'cookie' => array( 
        'prefix' => '',                          // Set a prefix if you need to avoid collisions
        'domain' => '',                          // Set to .your-domain.com for site-wide cookies
        'path'   => '/',                         // Typically will be a forward slash
        'expire' => (7 * 24 * 60 * 60),          // 1 week - Cookie expire time.
        'secure' => false,                       // Cookies will only be set if a secure HTTPS connection exists.
    ),
    /*
    |--------------------------------------------------------------------------
    | Proxy
    |--------------------------------------------------------------------------
    */
    'proxy' => array(
        'ips' => '',      // Reverse Proxy IPs , If your server is behind a reverse proxy, you must whitelist the proxy IP
    ),                    // addresses from which the Application should trust the HTTP_X_FORWARDED_FOR
                          // header in order to properly identify the visitor's IP address.
                          // Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
    /*
    |--------------------------------------------------------------------------
    | Output
    |--------------------------------------------------------------------------
    */
    'output' => array(
        'compress' => false,  // Enables Gzip output compression for faster page loads.  When enabled,
    ),                        // the Response class will test whether your server supports Gzip.
                              // Even if it does, however, not all browsers support compression
                              // so enable only if you are reasonably sure your visitors can handle it.
);

/* End of file config.php */
/* Location: .app/env/local/config.php */