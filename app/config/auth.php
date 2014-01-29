<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
| Configure auth library options
|
*/
$auth = array(

    'db'             => new Db,          // Set Database,
    'sess'           => new Sess,        // Session Object
    'session_prefix' => 'auth_',         // Set a prefix to prevent collisions with original session object.

    // Security Settings
    'algorithm'           => 'bcrypt',  // Whether to use "bcrypt" or "sha256" or "sha512" hash. ( Do not use md5 )
    'allow_login'         => true,      // Whether to allow logins to be performed on login form.
    'regenerate_sess_id'  => false,     // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
);

/* End of file auth.php */
/* Location: .app/config/auth.php */