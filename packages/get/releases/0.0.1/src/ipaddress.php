<?php
    
    /**
    * Fetch the IP Address
    *
    * @access    public
    * @return    string
    */
    function ipAddress()
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
                if ($spoof = Get::fetchFromArray($_SERVER, $header) !== FALSE)
                {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    if (strpos($spoof, ',') !== FALSE)
                    {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }

                    if ( ! validIp($spoof))
                    {
                        $spoof = FALSE;
                    }
                    else
                    {
                        break;
                    }
                }
            }

            $ipAddress = ($spoof !== FALSE && in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, TRUE)) ? $spoof : $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        if ( ! validIp($ipAddress))
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
    function validIp($ip, $which = '')
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