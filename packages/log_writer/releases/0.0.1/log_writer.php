<?php

/**
 * Log Writer Class
 *
 * @package       packages
 * @subpackage    log_write
 * @category      logging
 * @link          
 */

Class Log_Writer {

    // --------------------------------------------------------------------
    
    /**
    * Write Log File
    *
    * This function will be called using the global logMe() function.
    *
    * @access   public
    * @param    string   the error level
    * @param    string   the error message
    * @param    string   the folder of the logs
    * @return   bool
    */
    public static function dump($level = 'error', $message = '', $folder = '')
    {   
        $log_writer = getConfig('log_writer');

        return $log_writer['driver']->dump($level, $message, $folder);
    }

}

// END log_writer class

/* End of file Log_Writer.php */
/* Location: ./packages/log_writer/releases/0.0.1/log_writer.php */