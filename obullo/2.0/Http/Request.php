<?php

namespace Obullo\Http;

use Get;

/**
 * Request Class
 * Get Http Request Headers
 * 
 * @category  Http
 * @package   Request
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/http/request
 */
Class Request
{
    protected $headers;    // Request Headers

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;
        $this->logger = $c['logger'];
        $this->logger->debug('Request Class Initialized');
    }
    
    /**
     * Fetch an item from the $_REQUEST array
     * 
     * @param string  $index     key
     * @param boolean $xss_clean enable xss clean
     * 
     * @return string
     */
    public function get($index = null, $xss_clean = false)
    {
        if ($index === null AND ! empty($VAR)) {  // Check if a field has been provided
            $request = array();
            foreach (array_keys($VAR) as $key) {  // loop through the full _REQUEST array
                $request[$key] = Get::fetchFromArray($_REQUEST, $key, $xss_clean);
            }
            return $request;
        }
        return Get::fetchFromArray($_REQUEST, $index, $xss_clean);
    }

    /**
     * Get data from $_SERVER variable
     * 
     * @param string  $index      key
     * @param boolean $xss_clean  enable xss clean
     * @param boolean $global_var use global request variables not Hvc
     * 
     * @return void
     */
    public function getServer($index = null, $xss_clean = false, $global_var = false)
    {
        $VAR = ($global_var) ? $GLOBALS['_SERVER_BACKUP'] : $_SERVER;  // People may want to use hmvc or app superglobals.

        if ($index === null AND ! empty($VAR)) {  // Check if a field has been provided
            $server = array();
            foreach (array_keys($VAR) as $key) {  // loop through the full _REQUEST array
                $server[$key] = Get::fetchFromArray($VAR, $key, $xss_clean);
            }
            return $server;
        }
        return Get::fetchFromArray($VAR, $index, $xss_clean);
    }

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
     * e.g. echo $this->request->getHeader('Host');  // demo_blog
     *
     * @param string $key header key
     *
     * @link http://tr1.php.net/manual/en/function.getallheaders.php
     * 
     * @return string | boolean
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
                    $headers[$name] = $value;
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
        return false;
    }
    
    /**
     * Get ip address
     * 
     * @return string
     */
    public function getIpAddress()
    {
        global $c;
        static $ipAddress = '';

        $REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

        if ($ipAddress != '') {
            return $ipAddress;
        }
        
        $ipAddress = $REMOTE_ADDR;
        $proxy_ips = $c['config']['proxy']['ips'];
        
        if ( ! empty($proxy_ips)) {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));

            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header) {
                $spoof = (isset($_SERVER[$header])) ? $_SERVER[$header] : false;

                if ($spoof !== false) {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    if (strpos($spoof, ',') !== false) {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }
                    if (!$this->isValidIp($spoof)) {
                        $spoof = false;
                    } else {
                        break;
                    }
                }
            }
            $ipAddress = ($spoof !== false AND in_array($REMOTE_ADDR, $proxy_ips, true)) ? $spoof : $REMOTE_ADDR;
        }
        if ( ! $this->isValidIp($ipAddress)) {
            $ipAddress = '0.0.0.0';
        }
        return $ipAddress;
    }
    
    /**
     * Validate IP adresss
     * 
     * @param string $ip    ip address
     * @param string $which flag
     * 
     * @return boolean
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

// END Request class

/* End of file Request.php */
/* Location: .Obullo/Http/Request.php */