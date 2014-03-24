<?php

/**
 * Request Class
 * Get Http Request Headers
 * 
 * @package       packages
 * @subpackage    request
 * @category      http request
 * @link
 */
Class Request
{
    protected $headers;       // Request Headers

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;
        $this->logger = $c['Logger'];
        $this->logger->debug('Request Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the POST array
     *
     * @access   public
     * @param    string
     * @param    bool
     * @param    bool    Use global post values instead of HMVC scope.
     * @return   string
     */
    public function get($index = NULL, $xss_clean = FALSE)
    {
        if ($index === NULL AND ! empty($VAR)) {  // Check if a field has been provided
            $request = array();
            foreach (array_keys($VAR) as $key) {  // loop through the full _REQUEST array
                $request[$key] = Get::fetchFromArray($_REQUEST, $key, $xss_clean);
            }
            return $request;
        }
        return Get::fetchFromArray($_REQUEST, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Get data from $_SERVER variable
     * 
     * @return string | bool
     */
    public function getServer($index = null, $xss_clean = false, $use_global_var = false)
    {
        $VAR = ($use_global_var) ? $GLOBALS['_SERVER_BACKUP'] : $_SERVER;  // People may want to use hmvc or app superglobals.

        if ($index === null AND !empty($VAR)) {  // Check if a field has been provided
            $server = array();
            foreach (array_keys($VAR) as $key) {  // loop through the full _REQUEST array
                $server[$key] = Get::fetchFromArray($VAR, $key, $xss_clean);
            }
            return $server;
        }
        return Get::fetchFromArray($VAR, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Get server request method 
     * 
     * @return string | bool
     */
    public function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        }
        return false;
    }

    /**
     * Get Header
    *  e.g. echo $this->request->getHeader('Host');  // demo_blog
     * 
     * http://tr1.php.net/manual/en/function.getallheaders.php
     * 
     * @param  string $key key of header
     * @return string
     */
    public function getHeader($key = 'Host')
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {  // If http server is not Apache ?
            $headers = '';
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$lowercaseName] = $value;
                }
            }
        }
        foreach ($headers as $name => $val) {  // Backup the lowercase format each of keys
            $name = strtolower($name);
            $headers[$name] = $val;
        }
        if (isset($headers[$key])) { // get selected header
            return $headers[$key];
        }
        return;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the IP Address
     *
     * @access    public
     * @return    string
     */
    public function getIpAddress()
    {
        global $config;
        static $ipAddress = '';

        if ($ipAddress != '') {
            return $ipAddress;
        }
        $proxy_ips = $config['proxy_ips'];

        if ( ! empty($proxy_ips)) {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));

            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header) {
                $spoof = (isset($_SERVER[$header])) ? $_SERVER[$header] : false;

                if ($spoof !== FALSE) {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    if (strpos($spoof, ',') !== FALSE) {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }
                    if (!$this->isValidIp($spoof)) {
                        $spoof = FALSE;
                    } else {
                        break;
                    }
                }
            }
            $ipAddress = ($spoof !== FALSE AND in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, true)) ? $spoof : $_SERVER['REMOTE_ADDR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        if (!$this->isValidIp($ipAddress)) {
            $ipAddress = '0.0.0.0';
        }
        return $ipAddress;
    }

    // ------------------------------------------------------------------------

    /**
     * Validate IP Address
     *
     * @access   public
     * @param    string
     * @param    string  ipv4 or ipv6
     * @return   string
     */
    public function isValidIp($ip, $which = '')
    {
        $which = strtolower($which);
        switch ($which) {
            case 'ipv4':
                $flag = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $flag = FILTER_FLAG_IPV6;
                break;
            default:
                $flag = '';
                break;
        }
        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
    }

    // ------------------------------------------------------------------------

    /**
     * Detect the request is xmlHttp ( Ajax )
     * 
     * @return boolean
     */
    public function isXmlHttp()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Check the request is Hvc
     * 
     * @return boolean
     */
    public function isHvc()
    {
        if (isset($_SERVER['HVC_REQUEST'])) {
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Detect the connection is secure ( Https )
     * 
     * @return boolean
     */
    public function isSecure()
    {
        if (!isset($_SERVER['https']) OR $_SERVER['https'] != 'on') {
            return false;
        }
        return true;
    }

}

// echo $this->request->getHeader('Host');  // demo_blog
// --------------------------------------------------------------------
// 
// EXAMPLE HEADER OUTPUT
// Host: demo_blog 
// User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:26.0) Gecko/20100101 Firefox/26.0 Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/\*;q=0.8 
// Accept-Language: en-US,en;q=0.5 
// Accept-Encoding: gzip, deflate 
// 
// Cookie: frm_session=uqdp8hvjsfhen759eucgp31h74; frm_session_userdata=a%3A4%3A%7Bs%3A10%3A%22session_id%22%3Bs%3A26%3A%22uqdp8hvjsfhen759eucgp31h74%22%3Bs%3A10%3A%22ip_address%22%3Bs%3A9%3A%22127.0.0.1%22%3Bs%3A10%3A%22user_agent%22%3Bs%3A50%3A%22Mozilla%2F5.0+%28X11%3B+Ubuntu%3B+Linux+x86_64%3B+rv%3A26.0%29+G%22%3Bs%3A13%3A%22last_activity%22%3Bi%3A1389947182%3B%7D75f0224d5214efb875c685a30eda7f06
// 
// Connection: keep-alive 

// END Request Class

/* End of file request.php */
/* Location: ./packages/request/releases/0.0.1/request.php */