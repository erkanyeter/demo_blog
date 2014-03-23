<?php

/*
|--------------------------------------------------------------------------
| Sess Package Configuration
|--------------------------------------------------------------------------
|
| Session Variables
| 
| Prototype: 
|
| 'expiration' =>7200;  // Two hours
| 
*/ 
$config = array(
    
    // General settings
    'cookie_name'     => 'frm_session', // The name you want for the cookie
    'expiration'      => 7200,          // The number of SECONDS you want the session to last. By default two hours. "0" is no expiration.
    'expire_on_close' => true,          // Whether to cause the session to expire automatically when the browser window is closed
    'encrypt_cookie'  => false,         // Whether to encrypt the cookie
    'match_ip'        => false,           // Whether to match the user's IP address when reading the session data
    'match_useragent' => true,            // Whether to match the User Agent when reading the session data
    'time_to_update'  => 1,        // How many seconds between Framework refreshing "Session" Information"

);

$config['native'] = array(
    
    'session.gc_divisor'      => 100,      // Configure garbage collection
    'session.gc_maxlifetime'  => $config['expiration'],
    'session.cookie_lifetime' => 0,
    // 'session.use_cookies' => 1,
    'session.save_handler'    => 'redis',
    'session.save_path'       => 'tcp://10.0.0.154:6379?auth=aZX0bjL',
);

$config['container'] = array(
     // Container Settings
    'db'              => 'Db',          // Db, Cache; // Mongo_Db;   Set any database container   
    'table_name'      => 'frm_sessions',  // The name of the session database table
);

/* End of file sess.php */
/* Location: .app/config/debug/sess.php */