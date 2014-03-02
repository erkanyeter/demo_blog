<?php

/**
 * Simple Auth Class
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
   
    public $database;       // Databasse object variable name
    public $sess;           // Session Object
    public $algorithm;      // Password hash / verify object
    public $session_prefix     = 'auth_';
    public $allow_login        = true;    // Whether to allow logins to be performed on this page.
    public $regenerate_sess_id = false;   // Set to true to regenerate the session id on every page load or leave as false to regenerate only upon new login.

    protected $database_password; // password string comes from database column
    
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
        global $logger;

        if ( ! isset(getInstance()->auth)) {

            $auth   = getConfig('auth');
            $params = array_merge($auth, $config);

            getInstance()->auth = $this; // Make available it in the controller.
            $this->init($params);
        }

        // initialize to session object
        // because of its global

        $session    = $auth['session'];    // run new Sess object
        $this->sess = $session();

        $logger->debug('Auth Class Initialized');
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
        foreach($params as $key => $val)
        {
            $this->{$key} = $val;     // assign config items
        }

        $db_var = $this->database;    // assign database object if its exists

        if(isset(getInstance()->{$db_var}))
        {
            $this->{$db_var} = getInstance()->{$db_var};
        }

        return ($this);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Send post query to login
    * 
    * @param string $password  manually login password
    * @param string $closure
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
        
        if($row == false) // If query is not object.
        {
            return false;
        }

        if(empty($this->database_password))
        {
            throw new Exception('Auth class requries database password string for verifiying the password. <pre>$this->setPassword(string $password);</pre>');
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
    public function setPassword($password)
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
    public function hashPassword($password)
    {
        $hashPassword = $this->extend['hashPassword']; //  run the closure function
        return $hashPassword($password);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Create Password Hash
     * 
     * @param  string $password
     * @return string $hash
     */
    public function verifyPassword($password)
    {
        $algorithm_closure = $this->algorithm;
        $algorithm         = $algorithm_closure();

        if (is_object($algorithm)) {
            $verifyPassword = $this->extend['verifyPassword'];      //  run the closure function
            return $verifyPassword($password, $this->database_password);
        } 
        else  // if it is a native hash() function
        {
            return $algorithm;
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
    public function setIdentity($key, $val = '')
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
        $this->sess->remove($this->getAllData());
    }
    
    // ------------------------------------------------------------------------

    /**
     * Get all identity data
     * 
     * @return type
     */
    public function getAllData()
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