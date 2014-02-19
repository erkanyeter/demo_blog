<?php

/**
 * Log Adapter
 *
 * @package       packages
 * @subpackage    log_adapter
 * @category      logging
 * @link          
 */

Class Log_Adapter {

    public $enabled;          // enable or disable logs usign log levels
    public $threshold;        // log threshold
    public $levels = array(); // default log levels
    public $date_format;      // date format
    public $path;             // current log path
    public $folder;           // current log folder
    public $level;            // current log level
    public $message;          // current log message

    // --------------------------------------------------------------------

    /**
     * Init Configurations
     *
     * @param string $level 
     * @param string $msg 
     * @param string $folder 
     * @return void
     */
    public function init($level, $message = '', $folder = '')
    {
        global $config;

        //---------------------------------------------------------------------
        // Get log writer config
        // --------------------------------------------------------------------
        
        $log_writer = getConfig('log_writer');
        
        // --------------------------------------------------------------------
        // Set Defaults
        // --------------------------------------------------------------------
        
        $this->enabled     = true;
        $this->threshold   = $config['log_threshold'];
        $this->levels      = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'BENCH' => '4', 'ALL' => '5');
        $this->date_format = $log_writer['date_format'];
        $this->path        = DATA .'logs'. DS;
        $this->folder      = $folder;
        $this->level       = strtoupper($level);
        $this->message     = $message;

        // Task & Cli logs
        // --------------------------------------------------------------------

        if (defined('STDIN') AND defined('TASK'))  // Cli Task Request
        {
            $this->path   = rtrim($this->path, DS) . DS .'tasks' . DS;
            $this->folder = 'tasks_'.$folder;  // Change the foldername
        } 
        elseif(defined('STDIN'))  // Command Line && Task Requests
        {
            if(isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') //  Do not keep clear command logs.
            {
                $this->enabled = false;
            }

            $this->path   = rtrim($this->path, DS) . DS .'cli' . DS;
            $this->folder = 'cli_'.$folder;  // Change the foldername
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get property value from log 
     * adapter class
     * 
     * @param  string $key
     * @return mixed 
     */
    public function getItem($key)
    {
        return $this->{$key};
    }

    // --------------------------------------------------------------------

    /**
     * Set property value to log 
     * adapter class
     * 
     * @param string $key
     * @param mixed $val
     */
    public function setItem($key, $val)
    {
        $this->{$key} = $val;
    }

}

// END log_adapter class

/* End of file Log_Adapter.php */
/* Location: ./packages/log_adapter/releases/0.0.1/log_adapter.php */