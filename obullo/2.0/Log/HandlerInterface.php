<?php

namespace Obullo\Log;

/**
 * Logger Handler Interface
 * 
 * @category  Logger
 * @package   Log
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log/HandlerInterface
 */
interface HandlerInterface
{
    /**
    * Format log records and build lines
    *
    * @param array $unformatted log record
    * 
    * @return array formatted record
    */
    public function format($unformatted);

    /**
     * Write processor output
     * 
     * @return boolean
     */
    public function write();
}

// END HandlerInterface class

/* End of file HandlerInterface.php */
/* Location: .Obullo/Log/HandlerInterface.php */