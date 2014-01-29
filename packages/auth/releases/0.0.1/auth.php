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
    public $bcrypt; // Password hash / verify object
    public $session_prefix     = 'auth_';
    public $login_url          = '/login';
    public $dashboard_url      = '/dashboard';
    public $algorithm          = 'sha256'; // Set algorithm you want to use.
    public $allow_login        = true;     // Whether to allow logins to be performed on this page.
    public $regenerate_sess_id = false;  // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.

    protected $database_password; // password string comes from databas column
    
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

        $this->db = $this->db->connect(); // connect to database and assign db object.

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
    * 
    * @return boolean | object  If auth is fail it returns to false otherwise "Object"
    */
    public function query($password, $closure)
    {
        if($this->getItem('allow_login') == false)
        {
            return 'disabled';
        }

        // Get query function.
        $row = call_user_func_array(Closure::bind($closure, $this, get_class()), array());

        if(empty($this->database_password))
        {
            throw new Exception('Auth class requries database password string for verifiying the password.Use <pre>$this->setPassword(string $password);</pre>');
        }
        
        if($this->verifyPassword($password, $this->database_password))
        {
            return $row;
        }

        return false;
    }

    //---------------------------------------------------------------------

    /**
     * Set database password for verification
     * 
     * @param string $password
     */
    private function setPassword($password)
    {
        $this->database_password = $password; 
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
                return $this->bcrypt->verifyPassword($password, $dbPassword);
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
     * Authorize to User
     * 
     * @return void
     */
    public function authorize($closure)
    {
        if (is_callable($closure))
        {
            call_user_func_array(Closure::bind($closure, $this, get_class()), array());
        
            $this->sess->set('hasIdentity', 'yes', $this->session_prefix);  // Authenticate the user.
        }
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
     * Get User has auth access 
     * if its ok it returns to true otherwise false
     * 
     * @return boolean 
     */
    public function hasIdentity()
    {
        if($this->sess->get('hasIdentity', $this->session_prefix) == 'yes')  // auth is ok ?
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
    * Remove the identity of user
    * 
    * @return void 
    */
    public function clearIdentity()
    {
        $this->sess->remove($this->getAllIdentities());
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get all identity data
     * 
     * @return type
     */
    public function getAllIdentities()
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