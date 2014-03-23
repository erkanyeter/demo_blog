<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
| Configure auth library options
|
*/
$config = array(
    'session_prefix'     => 'auth_',   // Set a prefix to prevent collisions with original session object.
    'allow_login'        => true,      // Whether to allow logins to be performed on login form.
    'regenerate_sess_id' => false,     // Set to true to regenerate the session id on every page load
);

/*
|--------------------------------------------------------------------------
| Dependeny Injection Objects
|--------------------------------------------------------------------------
| Configure dependecies
|
*/
$config['algorithm'] = function () {   // Whether to use "bcrypt" or another custom object 
    return new Bcrypt;                 // return null; or return 'sha1';  if you don't want to use the class.
};

/*
|--------------------------------------------------------------------------
| Dependeny Injection Methods
|--------------------------------------------------------------------------
| Configure Methods
|
*/
$config['extend']['hashPassword']   = function ($password) use ($config) {   //  Whether to use "bcrypt" "sha1","sha256","sha512" type hashes. ( Do not use md5 )
    $algorithm = $config['algorithm']();
    if (is_object($algorithm)) {
        return $algorithm->hashPassword($password);   // returns to hashed string
    }
    return hash($algorithm, $password);  // Default Native hash
};

$config['extend']['verifyPassword'] = function ($password, $hashedPassword) use ($config) {
    $algorithm = $config['algorithm']();                               
    if (is_object($algorithm)) {                                        // Initialize your algorithm class
        return $algorithm->verifyPassword($password, $hashedPassword);  // Returns "true" if password verify success otherwise "false"
    }
    return ($hashedPassword === hash($algorithm, $password));  // Default Native hash
};


/* End of file auth.php */
/* Location: .app/config/auth.php */