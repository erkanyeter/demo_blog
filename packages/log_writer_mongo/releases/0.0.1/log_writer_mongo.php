<?php

/**
 * Log Writer Mongo Class
 *
 * @package       packages
 * @subpackage    log_writer_mongo
 * @category      logging NoSQL
 * @link          
 */

Class Log_Writer_Mongo  extends Log_Adapter {

    // --------------------------------------------------------------------
    
    /**
    * Write Log to File
    *
    * This function will be called using the global logMe() function.
    *
    * @access   public
    * @param    string    the error level
    * @param    string    the error message
    * @param    string    the log folder
    * @return   bool
    */
    public function dump($level = 'error', $message, $folder = '')
    {   
        global $config;

        $this->init($level, $message, $folder);

        $enabled     = $this->getItem('enabled');
        $levels      = $this->getItem('levels');
        $level       = $this->getItem('level');
        $threshold   = $this->getItem('threshold');
        $date_format = $this->getItem('date_format');
        $message     = $this->getItem('message');    // get the filtered log message

        // Is enabled logging from config file ?
        //-------------------------------------------------------------

        if ($enabled === false)
        {
            return false;
        }

        // Is it allowed level ?
        //-------------------------------------------------------------

        if ( ! isset($levels[$level]) OR ($levels[$level] > $threshold))
        {
            return false;
        }

        // Ok ! Build the date format
        //-------------------------------------------------------------

        // $log_message.= $level.' '.date($date_format). ' --> '.$message."\n";  

        // Connect to mongo database
        //-------------------------------------------------------------

        $connection = false;

        // @todo 
        // CONNECT TO MONGO DB
        // USING CONFIG FILE
        // WRITE INTO COLLECTION IF FOLDER VARIABLE IS NOT EMPTY

        if( ! $connection)
        {
            throw new Exception("Mongo db connection error.");
        }

        return true;
    }    
    
}

// END log_writer_mongo class

/* End of file Log_Writer_Mongo.php */
/* Location: ./packages/log_writer_mongo/releases/0.0.1/log_writer_mongo.php */