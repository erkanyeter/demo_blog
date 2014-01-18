<?php

/**
 * Auth Class
 *
 * A lightweight and simple user authentication control class.
 *
 * @package       packages
 * @subpackage    auth
 * @category      authentication
 * 
 * @link(Safely Store a Password, http://codahale.com/how-to-safely-store-a-password/)
 * @link(Zend Crypt Password, http://framework.zend.com/manual/2.0/en/modules/zend.crypt.password.html)
 * @link(Bcyrpt Class, https://github.com/cosenary/Bcrypt-PHP-Class)
 */

Class Auth {
   
    public $db;     // Databasse object
    public $sess;   // Session Object
    public $table;  // Database table
    public $get;    // Input Object
    public $bcrypt; // Password hash / verify object
    public $session_prefix     = 'auth_';
    public $username_col       = 'username';  // The name of the table field that contains the username.
    public $password_col       = 'password';  // The name of the table field that contains the password.
    public $post_username      = '';     // The name of the form field that contains the username to authenticate.
    public $post_password      = '';     // The name of the form field that contains the password to authenticate.
    public $login_url          = '/login';
    public $dashboard_url      = '/dashboard';
    public $fields             = array();
    public $password_salt_str  = '';     // Password salt string.
    public $algorithm          = 'sha256'; // Set algorithm you want to use.
    public $allow_login        = true;     // Whether to allow logins to be performed on this page.
    public $xss_clean          = true;   // Whether to enable the xss clean features.
    public $regenerate_sess_id = false;  // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.
    public $row                = false;  // SQL Query result as row
    private $isValid           = false;  // Auth validation variable

    public $username;
    protected $password;
    
    /**
    * Constructor
    *
    * Sets the variables and runs the compilation routine
    *
    * @access    public
    * @return    void
    */
    public function __construct($config = array())
    {   
        if( ! isset(getInstance()->auth))
        {
            getInstance()->auth = $this->init($config); // Make available it in the controller.
        }

        $this->db = getInstance()->{Db::$var};

        logMe('debug', "Auth Class Initialized");
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Initalize and grab instance of the auth.
     * 
     * @param array $params
     * @return object
     */
    public function init($params = array())
    {
        $auth   = getConfig('auth');
        $config = array_merge($auth , $params);

        foreach($config as $key => $val)
        {
            $this->{$key} = $val;
        }

        return ($this);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Send post query to login
    * 
    * @param string $username  manually login username
    * @param string $password  manually login password
    * @return mixed
    */
    public function attemptQuery($username = '', $password = '')
    {
        if($this->getItem('allow_login') == false)
        {
            return 'disabled';
        }
        
        $password = ($this->password_salt_str != '') ? ($this->password_salt_str.trim($password)) : trim($password);

        $this->username = trim($username);
        $this->password = $password;

        // Get query function.
        $this->row = call_user_func_array(Closure::bind($this->query, $this, get_class()), array($this->username));

        $password_col = $this->password_col;

        if(is_object($this->row) AND isset($this->row->{$password_col}))
        {
            if($this->verifyPassword($this->password, $this->row->{$password_col}))
            {
                $this->isValid = true;
                return $this->row;
            }
        }
      
        return false;
    }
    
    // --------------------------------------------------------------------

    /**
     * Create Password Hash
     * 
     * @param  string $password
     * @return string $hash
     */
    private function hashPassword($password)
    {
        switch($this->algorithm)
        {
            case 'bcrypt':{
                $hash = $this->bcrypt->hashPassword($password);
                break;
            }
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':{
                $hash = hash($this->algorithm, $password);
                break;
            }
            default: 
            throw new Exception('Security alert: Please set the password encryption algorithm on app/config/auth'. EXT);
                break;
        }

        return $hash;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Create Password Hash
     * 
     * @param  string $password
     * @return string $hash
     */
    public function verifyPassword($password, $dbPassword)
    {
        switch($this->algorithm)
        {
            case 'bcrypt':{
                return $this->bcrypt->verifyPassword($password,$dbPassword);
                break;
            }
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':{
                $hash = hash($this->algorithm, $password);
                return ($dbPassword === $hash);
                break;
            }
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Check attempt query is valid.
     * 
     * @return boolean
     */
    public function isValid()
    {
        $row = $this->getRow();

        if(is_object($row) AND $this->isValid === true)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    // ------------------------------------------------------------------------    
    
    /**
     * Authorize the User
     * 
     * @return type
     */
    public function authorizeMe()
    {
        $this->sess->set('identity', 'yes', $this->session_prefix);  // Authenticate the user.
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Autheticate and set Identity items if login is successfull !
     * 
     * @param array $identities
     * @param bool $fakeAuth authorize to user.
     * @return bool
     */
    public function setIdentity($key, $val)
    {        
        if(is_array($key))
        {
            $this->sess->set($key, '', $this->session_prefix);
            return;
        }
        
        $this->sess->set($key, $val, $this->session_prefix);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get validated user sql query result object
     *
     * @return type 
     */
    public function getRow()
    {
        return $this->row;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get User has auth access 
     * if its ok it returns to true otherwise false
     * 
     * @return boolean 
     */
    public function hasIdentity()
    {
        if($this->sess->get('identity', $this->session_prefix) == 'yes')  // auth is ok ?
        {
            return true;
        }
        
        return false;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Retrieve authenticated user session data
    * 
    * @param string $key
    * @return mixed
    */
    public function getIdentity($key = '')
    {
        if($key == '')
        {
            return;
        }
        
        return $this->sess->get($key, $this->session_prefix);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Unset session auth data from user session container
    * 
    * @param string $key
    * @return void
    */
    public function removeIdentity($key)
    {
        if(is_array($key))
        {
            $this->sess->remove($key, $this->session_prefix);
            return;
        }
        
        $this->sess->remove($key, $this->session_prefix);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Override to auth configuration.
     * 
     * @param string $key
     * @param mixed $val 
     */
    public function setItem($key, $val)
    {
        $this->{$key} = $val;
    }
    
    //-------------------------------------------------------------------------
    
    /**
     * Get auth config item.
     * 
     * @param string $key
     * @return mixed
     */
    public function getItem($key)
    {
        return $this->{$key};
    }
    
    //-------------------------------------------------------------------------
    
    // @todo
    public function setExpire() {}
    public function setIdle() {}
    
    // ------------------------------------------------------------------------
    
    /**
    * Logout user, destroy the sessions.
    * 
    * @param bool $destroy - whether to use session destroy function
    * @return void 
    */
    public function clearIdentity()
    {
        $this->sess->remove('identity', $this->session_prefix);
        $this->sess->remove($this->getAllIdentityData());
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get user all identity data
     * 
     * @return type
     */
    public function getAllIdentityData()
    {
        $identityData = array();
        foreach($this->sess->getAllData() as $key => $val)
        {
            if(strpos($key, $this->session_prefix) === 0) // if key == auth_
            {
                $identityData[$key] = $val;
            }
        }
        
        return $identityData;
    }
    
}

// END Auth Class

/* End of file auth.php */
/* Location: ./packages/auth/releases/0.0.1/auth.php */