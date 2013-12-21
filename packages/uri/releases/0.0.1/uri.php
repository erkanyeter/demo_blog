<?php

 /**
 * Uri Class
 * Parses URIs and determines routing
 *
 * @package       packages
 * @subpackage    uri
 * @category      url
 * @link
 */

Class Uri
{
    public $keyval       = array();
    public $uri_string;
    public $segments     = array();
    public $rsegments    = array();
    public $extension    = '';
    public $uri_protocol = 'REQUEST_URI';
    
    public static $instance;

    /**
    * Constructor
    *
    * Simply globalizes the $RTR object.  The front
    * loads the Router class early on so it's not available
    * normally as other classes are.
    *
    * @access    public
    */
    public function __construct()
    {
        logMe('debug', 'Uri Class Initialized'); // Warning : Don't load any library in __construct level you may get a Fatal Error.
    }

    // --------------------------------------------------------------------

    /**
     * Call Config Methods If we need them ( Less Memory )
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        global $packages;

        $package = strtolower(getComponent('uri'));

        if( ! function_exists('Uri\Src\\'.$method))
        {
            require PACKAGES .$package. DS .'releases'. DS .$packages['dependencies'][$package]['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Uri\Src\\'.$method, $arguments);
    }

    // --------------------------------------------------------------------
    
    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 

       return self::$instance;
    }
    
    // --------------------------------------------------------------------

    public static function setInstance($object)
    {
        if(is_object($object))
        {
            self::$instance = $object;
        }
        
        return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * When we use HMVC we need to Clean
    * all data.
    *
    * @return  void
    */
    public function clear()
    {
        $this->keyval     = array();
        $this->uri_string = '';
        $this->segments   = array();
        $this->rsegments  = array();
        $this->extension  = 'php';
    }

    // --------------------------------------------------------------------

    /**
     * Get the URI String
     *
     * @access    private
     * @param     $hvmc  boolean
     * @return    string
     */
    public function _fetchUriString()
    {
        if($this->uri_string != '') 
        {
            return;
        }

        if (strtoupper(config('uri_protocol')) == 'AUTO')
        {
            if ($uri = $this->_detectUri()) // Let's try the REQUEST_URI first, this will work in most situations
            {
                $this->uri_protocol = 'REQUEST_URI';
                $this->setUriString($uri);
                return;
            }

            // Is there a PATH_INFO variable?
            // Note: some servers seem to have trouble with getenv() so we'll test it two ways
            $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
            if (trim($path, '/') != '' && $path != "/".SELF)
            {
                $this->uri_protocol = 'PATH_INFO';
                $this->setUriString($path);
                return;
            }

            // No PATH_INFO?... What about QUERY_STRING?
            $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
            if (trim($path, '/') != '')
            {
                $this->uri_protocol = 'QUERY_STRING';
                $this->setUriString($path);
                return;
            }

            // As a last ditch effort lets try using the $_GET array
            if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
            {
                $this->setUriString(key($_GET));
                return;
            }

            // We've exhausted all our options...
            $this->uri_string = '';
            return;
        }

        $uri = strtoupper($this->config->item('uri_protocol'));

        if ($uri == 'REQUEST_URI')
        {
            $this->setUriString($this->_detectUri());
            return;
        }

        $path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
        $this->setUriString($path);
    }

    // --------------------------------------------------------------------

    /**
     * Parse the REQUEST_URI
     *
     * Due to the way REQUEST_URI works it usually contains path info
     * that makes it unusable as URI data.  We'll trim off the unnecessary
     * data, hopefully arriving at a valid URI that we can use.
     *
     * @access    private
     * @return    string
     */
    public function _detectUri()
    {
        if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
        {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
        {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
        {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        // This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
        // URI is found, and also fixes the QUERY_STRING server var and $_GET array.
        if (strncmp($uri, '?/', 2) === 0)
        {
            $uri = substr($uri, 2);
        }

        $parts = preg_split('#\?#i', $uri, 2);
        $uri = $parts[0];
        if (isset($parts[1]))
        {
            $_SERVER['QUERY_STRING'] = $parts[1];
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        }
        else
        {
            $_SERVER['QUERY_STRING'] = '';
            $_GET = array();
        }

        if ($uri == '/' || empty($uri))
        {
            return '/';
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        // Do some final cleaning of the URI and return it
        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

    // --------------------------------------------------------------------

    /**
    * Parse uri string for any possible file
    * extensions
    *
    * @param  string $segment
    * @return string
    */
    public function _parseSegmentExtension($segment)
    {
        if(strpos($segment, '.') !== false)
        {
            $allowed_extensions = config('uri_extensions');
            
            $extension = explode('.', $segment);
            $extension = end($extension);
            
            if(in_array($extension, $allowed_extensions))        
            {
                $this->extension = $extension;
                
                return str_replace('.'.$extension, '', $segment);
            }
        }

        return $segment;
    }

    // --------------------------------------------------------------------

    /**
     * Filter segments for malicious characters
     *
     * @access   private
     * @param    string
     * @return   string
     */
    public function _filterUri($str)
    {
    	if ($str != '' && config('permitted_uri_chars') != '' && config('enable_query_strings') == false)
        {
            // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
            // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
            if ( ! preg_match('|^['.str_replace(array('\\-', '\-'), '-', preg_quote(config('permitted_uri_chars'), '-')).']+$|i', $str))
            {
                $response = new Response;
                $response->showError('The URI you submitted has disallowed characters.', 400);
            }
        }

        // Convert programatic characters to entities and return
        return str_replace(array('$',     '(',     ')',     '%28',   '%29'), // Bad
                           array('&#36;', '&#40;', '&#41;', '&#40;', '&#41;'), // Good
                           $str);
    }
    

    // --------------------------------------------------------------------

    /**
     * Remove the suffix from the URL if needed
     *
     * @access    private
     * @return    void
     */
    public function _removeUrlSuffix()
    {
        if  (config('url_suffix') != "")
        {
            $this->uri_string = preg_replace("|".preg_quote(config('url_suffix'))."$|", "", $this->uri_string);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Explode the URI Segments. The individual segments will
     * be stored in the $this->segments array.
     *
     * @access    private
     * @return    void
     */
    public function _explodeSegments()
    {
        foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val)
        {
            $val = trim($this->_filterUri($val)); // Filter segments for security

            if ($val != '')
            {
                $this->segments[] = $this->_parseSegmentExtension($val);
            }
        }
    }

}
// END URI Class

/* End of file Uri.php */
/* Location: ./packages/uri/releases/0.0.1/uri.php */