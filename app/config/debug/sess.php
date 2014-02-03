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
| 'expiration' => strtotime( '+6 hours' );  @see http://us.php.net/strtotime
| 
*/
$sess = array(
	
	'cookie_name' => 'frm_session',  // The name you want for the cookie
	'expiration'  => strtotime( '+2 hours' ), // The number of SECONDS you want the session to last. By default two hours. "0" is no expiration.
	'expire_on_close' => false, 	// Whether to cause the session to expire automatically when the browser window is closed
	'encrypt_cookie'  => false,		// Whether to encrypt the cookie
	'driver'  => new Sess_Native, 	// Sess_Database
	'cookie'  => new Cookie,		// Set Cookie Object
	'request' => new Request,		// Set Request Object
	'db' => null,					// null, // new Db, new Cache; // new Mongo_Db;   Set any database object
	'table_name' => 'frm_sessions',	// The name of the session database table
	'match_ip' => false,			// Whether to match the user's IP address when reading the session data
	'match_useragent' => true,		// Whether to match the User Agent when reading the session data
	'time_to_update' => 300			// How many seconds between Framework refreshing "Session" Information"
);

/* End of file sess.php */
/* Location: .app/config/debug/sess.php */