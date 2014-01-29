<?php

 /**
 * Session Database Driver
 *
 * @package       packages
 * @subpackage    sess_database
 * @category      sessions
 * @link
 */

Class Sess_Cache {
    
    public $db;
    public $database;
    public $cookie;
    public $request;
    public $encrypt_cookie       = false;
    public $expiration           = '7200';
    public $match_ip             = false;
    public $match_useragent      = true;
    public $cookie_name          = 'ob_session';
    public $db_var               = 'db';
    public $cookie_prefix        = '';
    public $cookie_path          = '';
    public $cookie_domain        = '';
    public $time_to_update       = 300;
    public $encryption_key       = '';
    public $flashdata_key        = 'flash';
    public $time_reference       = 'time';
    public $gc_probability       = 5;
    public $sess_id_ttl          = '';
    public $userdata             = array();
    
    // --------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Sess_Cache\Src\\'.$method))
        {
            require (PACKAGES .'sess_cache'. DS .'releases'. DS .$packages['dependencies']['sess_cache']['version']. DS .'src'. DS .mb_strtolower($method). EXT);
        }

        return call_user_func_array('Sess_Cache\Src\\'.$method, $arguments);
    }

    // --------------------------------------------------------------------

    function init($params = array())
    {        
        foreach (array('database','cookie','request','table_name', 'encrypt_cookie','expiration', 'expire_on_close', 'match_ip', 
        'match_useragent','time_to_update', 'time_reference', 'encryption_key', 'cookie_name') as $key)
        {
            $this->$key = (isset($params[$key])) ? $params[$key] : config($key, 'sess');
        }
        
        $this->cookie_path   = (isset($params['cookie_path'])) ? $params['cookie_path'] : config('cookie_path');
        $this->cookie_domain = (isset($params['cookie_domain'])) ? $params['cookie_domain'] : config('cookie_domain');
        $this->cookie_prefix = (isset($params['cookie_prefix'])) ? $params['cookie_prefix'] : config('cookie_prefix');
        
        $this->now = $this->_getTime();
        
        if ($this->expiration == 0) // Set the expiration two years from now.
        {
            $this->expiration = (60 * 60 * 24 * 365 * 2);
        }

        $this->cookie_name = $this->cookie_prefix . $this->cookie_name; // Set the cookie name
        
        $this->cookie  = &$this->cookie;      // Set Cookie object
        $this->request = &$this->request;     // Set Request object
        $this->db      = &$this->database;    // Set Database object

        if ( ! $this->_read())    // Run the Session routine. If a session doesn't exist we'll 
        {                         // create a new one.  If it does, we'll update it.
            $this->_create();
        }
        else
        {    
            $this->_update();
        }
        
        $this->_flashdataSweep(); // Delete 'old' flashdata (from last request)
        $this->_flashdataMark();  // Mark all new flashdata as old (data will be deleted before next request)
        $this->_gC();             // Delete expired sessions if necessary

        logMe('debug', "Session Database Driver Initialized"); 
        logMe('debug', "Session routines successfully run"); 

        return true;
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch the current session data if it exists
    *
    * @access    public
    * @return    array() sessions.
    */
    function _read()
    {
        $session = $this->cookie->get($this->cookie_name); // Fetch the cookie

        if ($session === false)  // No cookie?  Goodbye cruel world!...
        {               
            logMe('debug', 'A session cookie was not found');

            return false;
        }
        
        if ($this->encrypt_cookie == true) // Decrypt the cookie data : ! "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key     = $this->encryption_key;
            $session = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($session), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        }
        else
        {
            $hash    = substr($session, strlen($session)-32);    // encryption was not used, so we need to check the md5 hash
            $session = substr($session, 0, strlen($session)-32); // get last 32 chars

            if ($hash !==  md5($session . $this->encryption_key))  // Does the md5 hash match?  
            {                                                      // This is to prevent manipulation of session data in userspace
                logMe('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt');

                $this->destroy();

                return false;
            }
        }

        $session = $this->_unserialize($session); // Unserialize the session array
        if ( ! is_array($session) OR ! isset($session['session_id'])          // Is the session data we unserialized an array with the correct format?
        OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) 
        OR ! isset($session['last_activity'])) 
        {               
            $this->destroy();
            return false;
        }
    
        if (($session['last_activity'] + $this->expiration) < $this->now)  // Is the session current?
        {
            $this->destroy();

            return false;
        }

        if ($this->match_ip == true AND $session['ip_address'] != $this->request->getIpAddress()) // Does the IP Match?
        {
            $this->destroy();

            return false;
        }
        
        if ($this->match_useragent == true AND trim($session['user_agent']) != trim(substr($this->request->getServer('HTTP_USER_AGENT'), 0, 50)))
        {
            $this->destroy();       // Does the User Agent Match?

            return false;
        }

        $query = $this->db->get($session['session_id']);

        if ($this->match_ip == true AND $session['ip_address'] != $query['ip_address'])
        {
            $this->destroy();
        
            return false;
        }
        
        if ($this->match_useragent == true AND $session['user_agent'] != $query['user_agent'])
        {
            $this->destroy();
        
            return false;
        }
                               // Is there custom data?  If so, add it to the main session array
        if (empty($query) OR $query == '')     // No result?  Kill it! // Obullo changes ..
        {
            $this->destroy();

            return false;
        }
        if (isset($query['user_data']) AND $query['user_data'] != '')
        {
            $custom_data = $this->_unserialize($query['user_data']);

            if (is_array($custom_data))
            {
                foreach ($custom_data as $key => $val)
                {
                    $session[$key] = $val;
                }
            }
        }    

        $this->userdata = $session;   // Session is valid!
        unset($session);

        return true;
    }
    
    // --------------------------------------------------------------------

    /**
    * Write the session data
    *
    * @access    public
    * @return    void
    */
    function _write()
    {
        $custom_userdata = $this->userdata;  // set the custom userdata, the session data we will set in a second
        $cookie_userdata = array();

        // Before continuing, we need to determine if there is any custom data to deal with.
        // Let's determine this by removing the default indexes to see if there's anything left in the array
        // and set the session data while we're at it
        
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            unset($custom_userdata[$val]);
            $cookie_userdata[$val] = $this->userdata[$val];
        }

        // Did we find any custom data?  If not, we turn the empty array into a string
        // since there's no reason to serialize and store an empty array in the DB
        
        if (count($custom_userdata) === 0)
        {
            $custom_userdata = '';
        }
        else
        {    
            $custom_userdata = $this->_serialize($custom_userdata); // Serialize the custom data array so we can store it
        }
        // $this->db->where('session_id', $this->userdata['session_id']);         // Run the update query
        $this->db->set($this->userdata['session_id'], array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata),$this->expiration);

        // Write the cookie.  Notice that we manually pass the cookie data array to the
        // _setCookie() function. Normally that function will store $this->userdata, but 
        // in this case that array contains custom data, which we do not want in the cookie.
        
        $this->_setCookie($cookie_userdata);
    }

    // --------------------------------------------------------------------
    
    /**
    * Create a new session
    *
    * @access    public
    * @return    void
    */
    function _create()
    {
        $sessid = '';
        while (strlen($sessid) < 32)
        {
            $sessid .= mt_rand(0, mt_getrandmax());
        }

        $sessid .= $this->request->getIpAddress(); // To make the session ID even more secure we'll combine it with the user's IP

        $this->userdata = array(
                                'session_id'     => md5(uniqid($sessid, true)),
                                'ip_address'     => $this->request->getIpAddress(),
                                'user_agent'     => substr($this->request->getServer('HTTP_USER_AGENT'), 0, 50),
                                'last_activity'  => $this->now
                                );

        $this->db->set($this->userdata['session_id'], $this->userdata, $this->expiration);
        
        $this->_setCookie(); // Write the cookie 
    }
    
    // --------------------------------------------------------------------

    /**
    * Update an existing session
    *
    * @access    public
    * @return    void
    */
    function _update()
    {
        $cookie  = $this->cookie->get($this->cookie_name);
        $session = $this->_unserialize($cookie);

        if (($this->userdata['last_activity'] + $this->time_to_update) >= $this->now) // We only update the session every five minutes by default
        {
            return;
        }
       
        $old_sessid = $this->userdata['session_id']; // Save the old session id so we know which record to  
        $new_sessid = '';                            // update in the database if we need it
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }

        $new_sessid .= $this->request->getIpAddress();         // To make the session ID even more secure
        $new_sessid = md5(uniqid($new_sessid, true));   // Turn it into a hash
        
        $this->userdata['session_id']    = $new_sessid; // Update the session data in the session data array
        $this->userdata['last_activity'] = $this->now;
        
        $cookie_data = null;    // _setCookie() will handle this for us if we aren't using database sessions
                                // by pushing all userdata to the cookie
        
        // Update the session ID and last_activity field in the DB if needed
        // set cookie explicitly to only have our session data
        
        $cookie_data = array();
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            $cookie_data[$val] = $this->userdata[$val];
        }

        $this->db->replace($session['session_id'], array('last_activity' => $this->now, 'session_id' => $new_sessid), $this->expiration); 

        $this->_setCookie($cookie_data); // Write the cookie
    }

    // ------------------------------------------------------------------------

    /**
    * Identifies flashdata as 'old' for removal
    * when _flashdataSweep() runs.
    *
    * @access    private
    * @return    void
    */
    function _flashdataMark()
    {
        $userdata = $this->getAllData();
        
        foreach ($userdata as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) AND count($parts) === 2)
            {
                $new_name = $this->flashdata_key.':old:'.$parts[1];
                
                $this->set($new_name, $value);
                $this->remove($name);
            }
        }
    }
    
    // ------------------------------------------------------------------------

    /**
    * Removes all flashdata marked as 'old'
    *
    * @access    private
    * @return    void
    */  
    function _flashdataSweep()
    {              
        $userdata = $this->getAllData();
        
        foreach ($userdata as $key => $value)
        {
            if (strpos($key, ':old:'))
            {
                $this->remove($key);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
    * Get the "now" time
    *
    * @access    private
    * @return    string
    */
    function _getTime()
    {
        $time = time();
        if (strtolower($this->time_reference) == 'gmt')
        {
            $now  = time();
            $time = mktime( gmdate("H", $now), 
            gmdate("i", $now), 
            gmdate("s", $now), 
            gmdate("m", $now), 
            gmdate("d", $now), 
            gmdate("Y", $now)
            );
        }
        return $time;
    }
    
    // --------------------------------------------------------------------

    /**
    * Write the session cookie
    *
    * @access    public
    * @return    void
    */
    function _setCookie($cookie_data = null)
    {
        if (is_null($cookie_data))
        {
            $cookie_data = $this->userdata;
        }

        $cookie_data = $this->_serialize($cookie_data); // Serialize the userdata for the cookie
        
        if ($this->encrypt_cookie == true) // Obullo Changes "Encrypt Library Header redirect() Bug Fixed !"
        {
            $key         = $this->encryption_key;
            $cookie_data = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cookie_data, MCRYPT_MODE_CBC, md5(md5($key))));
        }
        else
        {
            $cookie_data = $cookie_data . md5($cookie_data . $this->encryption_key); // if encryption is not used, 
                                                                                     // we provide an md5 hash to prevent userside tampering
        }
        
        $expiration = ($this->expire_on_close) ? 0 : $this->expiration + time();

        // Set the cookie
        setcookie(
                    $this->cookie_name,
                    $cookie_data,
                    $expiration,
                    $this->cookie_path,
                    $this->cookie_domain,
                    0
                );
    }

    // --------------------------------------------------------------------

    /**
    * Serialize an array
    *
    * This function first converts any slashes found in the array to a temporary
    * marker, so when it gets unserialized the slashes will be preserved
    *
    * @access   private
    * @param    array
    * @return   string
    */    
    function _serialize($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                {
                    $data[$key] = str_replace('\\', '{{slash}}', $val);
                }
            }
        }
        else
        {
            if (is_string($val))
            {
                $data = str_replace('\\', '{{slash}}', $data);
            }
        }
        
        return serialize($data);
    }

    // --------------------------------------------------------------------

    /**
    * Unserialize
    *
    * This function unserializes a data string, then converts any
    * temporary slash markers back to actual slashes
    *
    * @access   private
    * @param    array
    * @return   string
    */
    function _unserialize($data)
    {
        $data = unserialize(stripslashes($data));

        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }

            return $data;
        }

        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }

    // --------------------------------------------------------------------

    /**
    * Garbage collection
    *
    * This deletes expired session rows from database
    * if the probability percentage is met
    *
    * @access    public
    * @return    void
    */
    function _gC()
    {
        srand(time());
        
        if ((rand() % 100) < $this->gc_probability)
        {
            $expire = $this->now - $this->expiration;

            $this->db->delete($this->userdata['session_id']); // delete expired key

            logMe('debug', 'Session garbage collection performed');
        }
    }

}

/* End of file sess_database.php */
/* Location: ./packages/sess_database/releases/0.0.1/sess_database.php */