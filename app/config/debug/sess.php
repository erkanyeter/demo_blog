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
$sess = array(
    
    'cookie_name'     => 'frm_session', // The name you want for the cookie
    'expiration'      => 7200,          // The number of SECONDS you want the session to last. By default two hours. "0" is no expiration.
    'expire_on_close' => false,         // Whether to cause the session to expire automatically when the browser window is closed
    'encrypt_cookie'  => false,         // Whether to encrypt the cookie
    'request' => function () { // Set Request Object
        return new Request;
    },
    'db' => function () {  // new Db, new Cache; // new Mongo_Db;   Set any database object
        return new Db;
    },            
    'table_name'      => 'frm_sessions',  // The name of the session database table
    'match_ip'        => false,           // Whether to match the user's IP address when reading the session data
    'match_useragent' => true,            // Whether to match the User Agent when reading the session data
    'time_to_update'  => 1        // How many seconds between Framework refreshing "Session" Information"
);

$sess['driver'] =  function () use($sess) { 
    // return new Sess_Database;
    return new Sess_Native(
        array(
            'session.gc_divisor'      => 100,      // Configure garbage collection
            'session.gc_maxlifetime'  => $sess['expiration'],
            'session.cookie_lifetime' => 0,
            'session.save_handler'    => 'redis',
            'session.save_path'       => 'tcp://10.0.0.154:6379?auth=aZX0bjL',
        )
    );
};

/* End of file sess.php */
/* Location: .app/config/debug/sess.php */