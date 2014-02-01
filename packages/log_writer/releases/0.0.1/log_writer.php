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
    * @param    string    the error level
    * @param    string    the error message
    * @return   bool
    */
    function dump($level = 'error', $msg = '')
    {   
        $config = getConfig();

        // Convert new lines to a temp symbol, than we replace it and read for console debugs.
        $msg = trim(preg_replace('/\n/', '[@]', $msg), "\n");

        $threshold = 1;
        $date_fmt  = 'Y-m-d H:i:s';
        $enabled   = true;
        $levels    = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'BENCH' => '4', 'ALL' => '5');
        $level     = strtoupper($level);

        $logPath         = DATA .'logs'. DS;
        $log_threshold   = $config['log_threshold'];
        $log_date_format = $config['log_date_format'];

        if (defined('STDIN') AND defined('TASK'))   // Internal Task Request
        {
            $logPath = rtrim($logPath, DS) . DS .'tasks' . DS;
        } 
        elseif(defined('STDIN'))  // Command Line && Task Requests
        {
            if(isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') //  Do not keep clear command logs.
            {
                return false;
            }

            $logPath = rtrim($logPath, DS) . DS .'cli' . DS; 
        }         

        if ( ! is_dir(rtrim($logPath, DS)))
        {
            $enabled = false;
        }

        if (is_numeric($log_threshold))
        {
            $threshold = $log_threshold;
        }

        if ($log_date_format != '')
        {
            $date_fmt = $log_date_format;
        }

        if ($enabled === false)
        {
            return false;
        }

        if ( ! isset($levels[$level]) OR ($levels[$level] > $threshold))
        {
            return false;
        }

        $filePath = $logPath .'log-'. date('Y-m-d').EXT;
        $message  = '';  

        if ( ! is_file($filePath))
        {
            $message .= "<"."?php defined('ROOT') or die('Access Denied') ?".">\n\n";
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($date_fmt). ' --> '.$msg."\n";  

        if( ! is_writable($logPath))
        {
            throw new Exception("The log path is not writable. Please give write permission to this folder.
                        <pre>+ app\n+ data\n - <b>logs</b>\n+public</pre>");
        }

        if ( ! $fp = fopen($filePath, 'ab'))
        {
            return false;
        }

        flock($fp, LOCK_EX);    
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        if( ! defined('STDIN')) // Do not do chmod in CLI mode, it cause write errors
        {
            chmod($filePath, 0666);
        }

        return true;
    }    
    
}

// END log_writer class

/* End of file Log_Writer.php */
/* Location: ./packages/log_writer/releases/0.0.1/log_writer.php */