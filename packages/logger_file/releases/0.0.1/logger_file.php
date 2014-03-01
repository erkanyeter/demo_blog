<?php

/**
 * Log Writer File Class
 *
 * @package       packages
 * @subpackage    log_write_file
 * @category      logging
 * @link          
 */

Class Logger_File  extends Logger/Src/Adapter {

    // --------------------------------------------------------------------
    
    /**
    * Write Log to File
    *
    * This function will be called using the global $logger->level() function.
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

        $path        = $this->getItem('path');
        $enabled     = $this->getItem('enabled');
        $levels      = $this->getItem('levels');
        $level       = $this->getItem('level');
        $threshold   = $this->getItem('threshold');
        $date_format = $this->getItem('date_format');
        $message     = $this->getItem('message');    // get the filtered log message

        // Is directory ?
        //-------------------------------------------------------------
    
        if ( ! is_dir(rtrim($path, DS)))
        {
            $enabled = false;
        }

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

        // Ok ! Build the file format
        //-------------------------------------------------------------

        $file = $path .'log-'. date('Y-m-d').EXT; 

        $log_message = '';

        // --------------------------------------------------------------------
        // Convert new lines to a temp symbol, 
        // than we replace it and read for console debugs.

        // $message = trim(preg_replace('/\n/', '[@]', $message), "\n"); // clear new lines 

        if ( ! is_file($file)) //  Builf head tag of the log file
        {
            $log_message.= "<"."?php defined('ROOT') or die('Access Denied') ?".">\n\n";
        }

        $log_message.= $level.' '.date($date_format). ' --> '.$message."\n";  

        if( ! is_writable($path))
        {
            throw new Exception("The log path is not writable. Please give write permission to this folder.
                        <pre>+ app\n+ data\n - <b>logs</b>\n+public</pre>");
        }

        if ( ! $fp = fopen($file, 'ab'))
        {
            return false;
        }

        flock($fp, LOCK_EX);    
        fwrite($fp, $log_message);
        flock($fp, LOCK_UN);
        fclose($fp);

        if( ! defined('STDIN')) // Do not do (chmod) in CLI mode, it causes write error
        {
            chmod($file, 0666);
        }

        return true;
    }    
    
}

// END log_writer_file class

/* End of file Log_Writer_File.php */
/* Location: ./packages/log_writer_file/releases/0.0.1/log_writer_file.php */