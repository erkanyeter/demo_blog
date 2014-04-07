<?php

namespace Obullo\Logger\Handler;

/**
 * Disabled Log Handler Class
 * 
 * @category  Logger
 * @package   File
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class Disabled
{
    /**
     * If logger disabled all logger
     * methods returns to null.
     * 
     * @param string $method    name
     * @param array  $arguments array
     * 
     * @return null
     */
    public function __call($method, $arguments)
    {
        $method    = null;
        $arguments = array();
        return false;  // isEnabled function returns to "false".
    }
}

// END Disabled Class

/* End of file Disabled.php */
/* Location: .Obullo/Logger/Handler/Disabled.php */