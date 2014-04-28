<?php

namespace Obullo\Uri;

/**
 * Uri Class
 *
 * Modeled after Codeigniter Uri class.
 * 
 * @category  Http
 * @package   Uri
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/uri
 */
Class Uri
{
    public $logger;
    public $keyval = array();
    public $uri_string;
    public $segments = array();
    public $rsegments = array();
    public $uri_extension = '';
    public $uri_protocol = 'REQUEST_URI';

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
        global $c;
        $this->config = $c['config'];
        $this->logger = $c['logger'];
        
        $this->logger->debug('Uri Class Initialized', array(), 9); // Warning : Don't load any library in __construct level you may get a Fatal Error.
    }

    /**
     * Get the URI String
     *
     * @access    private
     * @return    string
     */
    public function fetchUriString()
    {
        if ($this->uri_string != '') {
            return;
        }
        $protocol = $this->config['uri']['protocol'];

        if (strtoupper($protocol) == 'AUTO') {
            if ($uri = $this->detectUri()) {           // Let's try the REQUEST_URI first, this will work in most situations
                $this->uri_protocol = 'REQUEST_URI';
                $this->setUriString($uri);
                return;
            }
            // Is there a PATH_INFO variable?
            // Note: some servers seem to have trouble with getenv() so we'll test it two ways
            $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');

            if (trim($path, '/') != '' AND $path != "/" . SELF) {
                $this->uri_protocol = 'PATH_INFO';
                $this->setUriString($path);
                return;
            }
            $path = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING'); // No PATH_INFO?... What about QUERY_STRING?

            if (trim($path, '/') != '') {
                $this->uri_protocol = 'QUERY_STRING';
                $this->setUriString($path);
                return;
            }
            if (is_array($_GET) AND count($_GET) == 1 AND trim(key($_GET), '/') != '') { // As a last ditch effort lets try using the $_GET array
                $this->setUriString(key($_GET));
                return;
            }
            $this->uri_string = ''; // We've exhausted all our options...
            return;
        }
        $uri = strtoupper($protocol);
        if ($uri == 'REQUEST_URI') {
            $this->setUriString($this->detectUri());
            return;
        }
        $path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
        $this->setUriString($path);
    }

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
    public function detectUri()
    {
        if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        if (strncmp($uri, '?/', 2) === 0) {     // This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
            $uri = substr($uri, 2);             // URI is found, and also fixes the QUERY_STRING server var and $_GET array.
        }

        $parts = preg_split('#\?#i', $uri, 2);
        $uri = $parts[0];

        if (isset($parts[1])) {
            $_SERVER['QUERY_STRING'] = $parts[1];
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        } else {
            $_SERVER['QUERY_STRING'] = '';
            $_GET = array();
        }

        if ($uri == '/' || empty($uri)) {
            return '/';
        }
        $uri = parse_url($uri, PHP_URL_PATH);

        return str_replace(array('//', '../'), '/', trim($uri, '/'));  // Do some final cleaning of the URI and return it
    }

    /**
     * Parse uri string for any possible file
     * extensions
     *
     * @param string $segment segment part
     * 
     * @return string
     */
    private function _parseSegmentExtension($segment)
    {
        if (strpos($segment, '.') !== false) {

            //----------- Response Format -------------//

            $extension = explode('.', $segment);
            $uri_extension = end($extension);

            if (in_array('.' . $uri_extension, $this->config['uri']['extensions'])) {
                $this->uri_extension = $uri_extension;  // set extension 

                return preg_replace('#\.' . $uri_extension . '$#', '', $segment); // remove extension from end of the uri segment
            }

            //-----------
        }
        return $segment;
    }

    /**
     * Filter segments for malicious characters
     *
     * @param string $str uri
     * 
     * @return string
     */
    public function filterUri($str)
    {
        global $c;

        // defined STDIN FOR task requests
        // we should not prevent "base64encode" characters in CLI mode
        // the "sync" task controller and some schema libraries use "base64encode" function

        if ($str != '' AND $this->config['uri']['permitted_chars'] != '' AND $this->config['uri']['query_strings'] == false AND !defined('STDIN')) {

            // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
            // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern

            if ( ! preg_match('|^[' . str_replace(array('\\-', '\-'), '-', preg_quote($this->config['uri']['permitted_chars'], '-')) . ']+$|i', $str)) {
                $c['response']->showError('The URI you submitted has disallowed characters.', 400);
            }
        }

        // Convert programatic characters to entities and return
        return str_replace(
            array(
        '$', '(', ')', '%28', '%29'
            ), // Bad
            array(
        '&#36;', '&#40;', '&#41;', '&#40;', '&#41;'
            ), // Good
            $str
        );
    }

    /**
     * Remove the suffix from the URL if needed
     *
     * @return void
     */
    public function removeUrlSuffix()
    {
        if ($this->config['url']['rewrite']['suffix'] != '') {
            $this->uri_string = preg_replace("|" . preg_quote($this->config['url']['rewrite']['suffix']) . "$|", '', $this->uri_string);
        }
    }

    /**
     * Explode the URI Segments. The individual segments will
     * be stored in the $this->segments array.
     *
     * @access    private
     * @return    void
     */
    public function explodeSegments()
    {
        foreach (explode('/', preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val) {
            $val = trim($this->filterUri($val)); // Filter segments for security
            if ($val != '') {
                $this->segments[] = $this->_parseSegmentExtension($val);
            }
        }
    }

    /**
     * Used in Hvc 
     * 
     * @param string  $str    uri str
     * @param boolean $filter option on / off
     *
     * @return void
     */
    public function setUriString($str = '', $filter = true)
    {
        if ($filter) { // Filter out control characters
            $str = removeInvisibleCharacters($str, false);
        }
        $this->uri_string = ($str == '/') ? '' : $str;  // If the URI contains only a slash we'll kill it
    }

    /**
     * Get Assets URL
     * 
     * @param string $uri asset uri
     * 
     * @return string
     */
    public function getAssetsUrl($uri = '')
    {
        return rtrim($this->config['url']['assets'], '/') .'/'. ltrim($uri, '/');
    }

    /**
     * Get the current server uri
     * protocol.
     * 
     * @return string
     */
    public function getProtocol()
    {
        return $this->uri_protocol;
    }

    /**
     * Get the complete request uri like native php
     * $_SERVER['REQUEST_URI'].
     * 
     * @param boolean $urlencode encoded url or not
     * 
     * @return string
     */
    public function getRequestUri($urlencode = false)
    {
        if (isset($_SERVER[$this->getProtocol()])) {
            return ($urlencode) ? urlencode($_SERVER[$this->getProtocol()]) : $_SERVER[$this->getProtocol()];
        }
        return false;
    }

    /**
     * Get Base URL
     * 
     * @param string $uri custom uri
     * 
     * @return string
     */
    public function getBaseUrl($uri = '')
    {
        return rtrim($this->config['url']['base'], '/') .'/'. ltrim($uri, '/');
    }

    /**
     * Site URL
     *
     * @param string  $uri_str the URI string
     * @param boolean $suffix  switch off suffix by manually
     * 
     * @return   string
     */
    public function getSiteUrl($uri_str = '', $suffix = true)
    {
        if (is_array($uri_str)) {
            $uri_str = implode('/', $uri_str);
        }
        if ($uri_str == '') {
            return $this->getBaseUrl() . $this->config['rewrite']['index_page'];
        } else {
            $suffix = ($this->config['url']['rewrite']['suffix'] == false OR $suffix == false) ? '' : $this->config['url']['rewrite']['suffix'];
            return $this->getBaseUrl() . $this->config['url']['rewrite']['index_page'] . trim($uri_str, '/') . $suffix;
        }
    }

    /**
     * Fetch the entire Uri string
     *
     * @return string
     */
    public function getUriString()
    {
        return $this->uri_string;
    }

    /**
     * Fetch the entire Re-routed URI string
     *
     * @return string
     */
    public function getRoutedUriString()
    {
        return '/' . implode('/', $this->getRoutedSegmentArray()) . '/';
    }

    /**
     * Get current url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->getSiteUrl($this->getUriString());
    }

    /**
     * Fetch a URI Segment
     *
     * This function returns the URI segment based on the number provided.
     * 
     * @param integer $number    number
     * @param boolean $no_result custom no result output
     * 
     * @return string
     */
    public function getSegment($number, $no_result = false)
    {
        return (!isset($this->segments[$number])) ? $no_result : $this->segments[$number];
    }

    /**
     * Total number of segments
     *
     * @access    public
     * @return    integer
     */
    public function getTotalSegments()
    {
        return sizeof($this->segments);
    }

    /**
     * Total number of routed segments
     *
     * @access    public
     * @return    integer
     */
    public function getTotalRoutedSegments()
    {
        return sizeof($this->rsegments);
    }

    /**
     * Fetch a URI "routed" Segment ( Sub module isn't a rsegment based.)
     *
     * This function returns the re-routed URI segment (assuming routing rules are used)
     * based on the number provided.  If there is no routing this function returns the
     * same result as $this->segment()
     * 
     * @param integer $number    segment number
     * @param boolean $no_result custom no result output
     * 
     * @return string
     */
    public function getRoutedSegment($number, $no_result = false)
    {
        return (!isset($this->rsegments[$number])) ? $no_result : $this->rsegments[$number];
    }

    /**
     * Routed Segment Array
     *
     * @access    public
     * @return    array
     */
    public function getRoutedSegmentArray()
    {
        return $this->rsegments;
    }

    /**
     * Generate a key value pair from the URI string
     *
     * This function generates and associative array of URI data starting
     * at the supplied segment. For example, if this is your URI:
     *
     *    example.com/user/search/name/joe/location/UK/gender/male
     *
     * You can use this function to generate an array with this prototype:
     *
     * array (
     *            name => joe
     *            location => UK
     *            gender => male
     *         )
     *
     * @param integer $number  the starting segment number
     * @param array   $default an array of default values
     * 
     * @return   array
     */
    public function getUriToAssoc($number = 3, $default = array())
    {
        return $this->_uriToAssoc($number, $default, 'segment');
    }

    /**
     * Generate a URI string from an associative array
     *
     * @param array $array an associative array of key / values
     * 
     * @return array
     */
    public function getAssocToUri($array)
    {
        $temp = array();
        foreach ((array) $array as $key => $val) {
            $temp[] = $key;
            $temp[] = $val;
        }
        return implode('/', $temp);
    }

    /**
     * Identical to above only it uses the re-routed segment array
     * 
     * @param integer $number  integer
     * @param array   $default array
     * 
     * @return array
     */
    public function getRoutedUriToAssoc($number = 3, $default = array())
    {
        return $this->_uriToAssoc($number, $default, 'routedSegment');
    }

    /**
     * Fetch a URI Segment and add a trailing slash
     *
     * @param integer $number number
     * @param string  $where  trailing
     * 
     * @return   string
     */
    public function getSlashSegment($number, $where = 'trailing')
    {
        return $this->_slashSegment($number, $where, 'segment');
    }

    /**
     * Segment Array
     *
     * @access    public
     * @return    array
     */
    public function getSegmentArray()
    {
        return $this->segments;
    }

    /**
     * Fetch a URI Segment and add a trailing slash
     *
     * @param integer $number number
     * @param string  $where  trailing
     * 
     * @return   string
     */
    public function getSlashRoutedSegment($number, $where = 'trailing')
    {
        return $this->_slashSegment($number, $where, 'rsegment');
    }

    /**
     * Get extension of uri
     *
     * @return  string
     */
    public function getExtension()
    {
        return $this->uri_extension;
    }

    /**
     * Fetch a URI Segment and add a trailing slash - helper function
     * 
     * @param integer $number number
     * @param string  $where  trailing
     * @param string  $which  segment function
     * 
     * @return string
     */
    private function _slashSegment($number, $where = 'trailing', $which = 'getSegment')
    {
        if ($where == 'trailing') {
            $trailing = '/';
            $leading = '';
        } elseif ($where == 'leading') {
            $leading = '/';
            $trailing = '';
        } else {
            $leading = '/';
            $trailing = '/';
        }
        return $leading . $this->$which($number) . $trailing;
    }

    /**
     * Generate a key value pair from the URI string or Re-routed URI string
     *
     * @param integer $number  the starting segment number
     * @param array   $default an array of default values
     * @param string  $which   which array we should use
     * 
     * @return array
     */
    private function _uriToAssoc($number = 3, $default = array(), $which = 'getSegment')
    {
        if ($which == 'getSegment') {
            $totalSegments = 'getTotalSegments';
            $segmentArray  = 'getSegmentArray';
        } else {
            $totalSegments = 'getTotalRoutedSegments';
            $segmentArray  = 'getRoutedSegmentArray';
        }
        if (!is_numeric($number)) {
            return $default;
        }
        if (isset($this->keyval[$number])) {
            return $this->keyval[$number];
        }
        if ($this->$totalSegments() < $number) {
            if (count($default) == 0) {
                return array();
            }
            $retval = array();
            foreach ($default as $val) {
                $retval[$val] = false;
            }
            return $retval;
        }
        $segments = array_slice($this->$segmentArray(), ($number - 1));
        $i = 0;
        $lastval = '';
        $retval = array();
        foreach ($segments as $seg) {
            if ($i % 2) {
                $retval[$lastval] = $seg;
            } else {
                $retval[$seg] = false;
                $lastval = $seg;
            }
            $i++;
        }
        if (count($default) > 0) {
            foreach ($default as $val) {
                if ( ! array_key_exists($val, $retval)) {
                    $retval[$val] = false;
                }
            }
        }
        $this->keyval[$number] = $retval;  // Cache the array for reuse
        return $retval;
    }

    /**
     * When we use Hvc we need to Clean
     * all data.
     *
     * @return  void
     */
    public function clear()
    {
        $this->keyval = array();
        $this->uri_string = '';
        $this->segments = array();
        $this->rsegments = array();
        $this->uri_extension = '';
    }

}

// END URI Class
/* End of file Uri.php

/* Location: .Obullo/Uri/Uri.php */