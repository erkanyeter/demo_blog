<?php

namespace Obullo\Logger;

/**
 * PriorityQueue Class
 * 
 * @category  Logger
 * @package   Logger_Output
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/logger
 */
Class PriorityQueue extends \SplPriorityQueue 
{
    /**
     * Priority fix
     * 
     * @param integer $priority1 priority level
     * @param integer $priority2 priority2 level
     *
     * @return integer
     */
    public function compare($priority1, $priority2) 
    { 
        if ($priority1 === $priority2) return 0; 
        return $priority1 < $priority2 ? -1 : 1; 
    }

}

// END Output class
/* End of file Output.php */

/* Location: .Obullo/Logger/Output.php */