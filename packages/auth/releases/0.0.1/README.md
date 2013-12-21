## Auth Class

------

Auth Class provides a lightweight and simple auth implementation for user authentication management.

### Initializing the Class

------

```php
new Auth();
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
    
    'db' => new Db(),      // Set Database,
    'session_prefix' => 'auth_',      // Set a prefix to prevent collisions with original session object.
    'username_col' => 'user_email',   // Table field name that contains the username.
    'password_col' => 'user_password', // Table field name that contains the password.
    'login_url'   => '/login',        // Redirect Url for Unsuccessfull logins
    'dashboard_url' => '/dashboard',  // Redirect Url Successfull logins

    // Security Settings
    'password_salt_str'   => '',        // Password salt string for more strong passwords. * Leave it blank if you don't want to use it.
    'algorithm'           => 'bcrypt',  // Whether to use "bcrypt" or "sha256" or "sha512" hash. ( Do not use md5 )
    'allow_login'         => true,      // Whether to allow logins to be performed on login form.
    'xss_clean'           => true,      // Whether to enable the xss clean.
    'regenerate_sess_id'  => false,     // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
);

// Auth Query
// Build your sql ( or nosql query ) using db or crud oject.

$auth['query'] = array(function($username) use($auth)
{
    $this->db->prep();
    $this->db->select('user_id, user_firstname, user_lastname, user_email');
    $this->db->where($auth['username_col'], ':username');
    $this->db->get('users');
    $this->db->bindParam(':username', $username, PARAM_STR, 60); // String (int Length),
    $this->db->exec();
    
    return $this->db->row();  // return to object.
});
```

### Sending Data to Auth Query

------

```php
<?php
new Auth();
$this->auth->attemptQuery(
    $this->get->post('email'),
    $this->get->post('password'),
);
        
if($this->auth->isValid())
{
    $row = $this->auth->getRow();

    if($row)
    {
        $this->auth->authorizeMe(); // Authorize to user
        $this->auth->setIdentity('username', $row->user_username);    // Set user identity items.
        $this->auth->setIdentity('email', $row->user_email);

        $this->url->redirect($this->auth->item('dashboard_url'));
    } 
    else 
    {
       $this->url->redirect($this->auth->item('login_url'));
    }
}
```

### Checking Identity

------

```php
<?php
new Auth();

if($this->auth->hasIdentity())
{
     echo 'Great you are authorized to view this page !'; 
}
```


### Checking Auth Using Quick Access

------

```php
<?php

if($this->auth->hasIdentity())
{
     echo 'Great you are authorized to view this page !'; 
}
```

### Automatically Checking Identity and Redirect ( Check identity and redirect users to login Page )

Redirects user to <b>/login</b> page for unauthenticated page views. Redirect page is a configurable option in auth.php.

```php
$this->auth->identityRedirect(); http://example.com/settings?a=1&b=2 Unauthenticated user request
```

i.e. If an <b>unauthenticated</b> user want to display the <b>/settings</b> page, <b>$this->auth->identityRedirect()</b> will redirect user to the login page.

```php
http://example.com/login?redirect=%2Fsettings%3Fa%3D1%26b%3D2
```

Don't forget to use $this->auth->identityRedirect() which is designed for <b>unauthorized</b> pages.

And you may want to create hidden redirect input element to your login screen.

```php
<?php 
echo $form->open('login/post', array('method' => 'POST'));
echo $form->hidden('redirect', $this->get('redirect'));
?>
```

So now you are able to redirect the user to the redirect url. You need to check redirect input using $this->get('redirect') in your login page.

### IdentityRedirect Parameters

------

```php
$this->auth->identityRedirect('/login',  $urlencode = false);
```

### Checking Identity and Redirect

------

Redirect user to <kbd>dashboard_url</kbd> (<b>/dashboard</b>) page that is configurable in your <dfn>app/config/auth.php</dfn> file.

```php
<?php

if($this->auth->hasIdentity())
{
    $this->url->redirect($this->auth->item('dashboard_url'));
}

It's redirect request to /dashboard url as default.
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
$auth['db']['query'] = array(function()
{
    $this->db->select(implode(',', $this->select_data));
    $this->db->where($this->username_col, $this->username);
    $this->db->limit(1);
    $query = $this->db->get($this->tablename);
    $this->row = $query->row();
});
```

### Database Query for MongoDb

Below the codes show an example database query for mongodb.

```php
$auth['db']['query'] = array(function()
{
    $this->db->select($this->select_data);
    $this->db->where($this->username_col, $this->username);
    $this->db->limit(1);
    $docs = $this->db->get($this->tablename);

    if( ! $docs->hasNext())
    {
        return false;
    }

    $this->row = (object) $docs->getNext();
});
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

#### $this->auth->item($key)

Gets auth config item.

#### $this->auth->setItem($key, $val)

Sets auth config item.