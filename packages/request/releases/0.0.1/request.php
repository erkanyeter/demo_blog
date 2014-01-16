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

Class Request {
    
    /**
     * Constructor
     */
    public function __construct()
    {
        if( ! isset(getInstance()->request))
        {
            getInstance()->request = $this; // Make available it in the controller $this->request->method();
        }

        logMe('debug', 'Request Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Get server request method 
     * 
     * @return string | bool
     */
    public function getHttpMethod()
    {
        if(isset($_SERVER['REQUEST_METHOD']))
        {
            return $_SERVER['REQUEST_METHOD'];
        }

        return false;
    }

    // --------------------------------------------------------------------
    
    public function getHttpHeader($type = 'host')
    {
        // ....http://symfony.com/doc/current/book/http_fundamentals.html
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
        static $ipAddress = '';

        if ($ipAddress != '')
        {
            return $ipAddress;
        }

        $proxy_ips = config('proxy_ips');

        if ( ! empty($proxy_ips))
        {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));

            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
            {
                $spoof = (isset($_SERVER[$header])) ? $_SERVER[$header] : false;

                if ($spoof !== FALSE)
                {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    if (strpos($spoof, ',') !== FALSE)
                    {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }

                    if ( ! $this->isValidIp($spoof))
                    {
                        $spoof = FALSE;
                    }
                    else
                    {
                        break;
                    }
                }
            }

            $ipAddress = ($spoof !== FALSE AND in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, true)) ? $spoof : $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        if ( ! $this->isValidIp($ipAddress))
        {
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
        switch ($which)
        {
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
        if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Detects the secure connection ( Https )
     * 
     * @return boolean
     */
    public function isSecure()
    {
        if (empty($_SERVER['https']) AND $_SERVER['https'] != 'on')
        {
            return false;
        }

        return true;
    }

}

// END Request Class

/* End of file request.php */
/* Location: ./packages/request/releases/0.0.1/request.php */