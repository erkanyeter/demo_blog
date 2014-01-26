## Auth Class

------

Auth Class provides a lightweight and simple auth implementation for user authentication management.

### Initializing the Class

------

```php
new Auth;
$this->auth->method();
```

Once loaded, the Auth object will be available using: $this->auth->method();

### Configuring Auth Options

------

You can set authentication options in <dfn>app/config/auth.php</dfn> file.

```php
<?php

/*
|--------------------------------------------------------------------------
| Auth Package Configuration
|--------------------------------------------------------------------------
| Configure auth library options
|
*/
$auth = array(
    
    'db'                 => new Db,      // Set Database,
    'sess'               => new Sess,  // Session Object
    'get'                => new Get,    // Input Object
    'bcrypt'             => new Bcrypt,  // Bcrypt password hash / verify object
    'session_prefix'     => 'auth_',      // Set a prefix to prevent collisions with original session object.
    'password_col'       => 'user_password', // The name of the database table field that contains the password.
    'login_url'          => '/login',        // Redirect Url for Unsuccessfull logins
    'dashboard_url'      => '/home',  // Redirect Url Successfull logins
    // Security Settings
    'password_salt_str'  => '',        // Password salt string for more strong passwords. * Leave it blank if you don't want to use it.
    'algorithm'          => 'bcrypt',  // Whether to use "bcrypt" or "sha256" or "sha512" hash. ( Do not use md5 )
    'allow_login'        => true,      // Whether to allow logins to be performed on login form.
    'regenerate_sess_id' => false,     // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
    'xss_clean'          => true,      // Do xss clean for username 
);

// Auth Query
// Build your sql ( or nosql query ) using db or crud oject.

$auth['query'] = function($username)
{
    $this->db->prep();
    $this->db->select('user_id, user_username, user_password, user_email');
    $this->db->where('user_email', ':username');
    $this->db->get('users');
    $this->db->bindParam(':username', $username, PARAM_STR, 60); // String (int Length),
    $this->db->exec();
    
    return $this->db->getRow();  // return to object.
};
```

### Sending Data to Auth Query

------

```php
<?php
new Auth;

$row = $this->auth->query($_POST['email'], $_POST['password']);
        
if($row !== false) // validate the auth !
{
    $this->auth->authorizeMe(); // Authorize to user
    $this->auth->setIdentity('username', $row->user_username);    // Set user identity items.
    $this->auth->setIdentity('email', $row->user_email);

    $this->url->redirect('/dashboard');
}

$this->url->redirect('/login');
```

### Checking Identity

------

```php
<?php
new Auth;

if($this->auth->hasIdentity())
{
     echo 'Great you are authorized to view this page !'; 
}
```

### Checking Identity and Redirect

------

Redirect user to <kbd>dashboard_url</kbd> using url object.

```php
<?php

if($this->auth->hasIdentity())
{
    $this->url->redirect('/dashboard');
}
```

### Getting User Data From Sessions

------

```php
$this->auth->getIdentity('username');
```

### Getting All User Identity Data

------

```php
print_r($this->auth->getAllIdentityData());  //  gives array('auth_identity' => 'yes', 'auth_username' => 'John', 'auth_active' => 1);
```

### Logout User

------

This function will destroy the user identity data.

```php
$this->auth->clearIdentity();
```

### Customizing Database SQL Query

Using query config variable in <kbd>app/config/auth.php</kbd>, you can build your own sql queries.

**Note:** The query result must be in row (object) format.


```php
$auth['query'] = function($username)
{
    $this->db->prep();
    $this->db->select('user_id, user_username, user_password, user_email');
    $this->db->where('user_email', ':username');
    $this->db->get('users');
    $this->db->bindParam(':username', $username, PARAM_STR, 60); // String (int Length),
    $this->db->exec();
    
    return $this->db->getRow();  // return to object.
};
```

### Function Reference

------

#### $this->auth->attemptQuery($username = '', $password = '')

Tries authentication attempt and do sql query using username and password combination, if no data provided as parameter , it will look at the $_POST data.

#### $this->auth->authorizeMe()

Authorizes and gives identity to the user.

#### $this->auth->getRow()

Gets authorized user query result in object.

#### $this->auth->setIdentity($key, $val)

Checks the identity of the user.

#### $this->auth->hasIdentity()

Checks if the user is authorized, if so, it returns to true, otherwise false.

#### $this->auth->getIdentity('key')

Retrieves the authenticated user identity data.

#### $this->auth->setIdentity($key, $val)

Sets user identity data.

#### $this->auth->removeIdentity($key)

Removes identity data from identity container.

#### $this->auth->clearIdentity()

Logs out user, destroys all identity data. This method <kbd>does not destroy</kbd> the user <kbd>sessions</kbd>. It will just remove authorization and identity data of the user.

#### $this->auth->getItem($key)

Gets auth config item.

#### $this->auth->setItem($key, $val)

Sets auth config item.