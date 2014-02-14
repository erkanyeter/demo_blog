<?php

/**
 * Log Writer Class
 *
 * @package       packages
 * @subpackage    log_write
 * @category      logging
 * @link          
 */

Class Log_Writer extends Log_Adapter {

    // --------------------------------------------------------------------
    
    /**
    * Write Log File
    *
    * This function will be called using the global logMe() function.
    *
    * @access   public
    * @param    string   the error level
    * @param    string   the error message
    * @param    string   the section of the logs
    * @return   bool
    */
    function dump($level = 'error', $msg = '', $section = '')
    {   
        parent::__construct($level, $msg, $section);

        $logDriver = $this->getDriver(); 

        return $logDriver->dump();       
    }

}

// END log_writer class

/* End of file Log_Writer.php */
/* Location: ./packages/log_writer/releases/0.0.1/log_writer.php */