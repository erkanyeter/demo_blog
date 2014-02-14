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

    public $log_driver; // string

    /**
     * Build config and levels
     *
     * @param string $level 
     * @param string $msg 
     * @param string $section 
     * @return void
     */
    public function __construct($level, $msg, $section = '')
    {
        // $this->init($level, $msg, $section);
    }

    // --------------------------------------------------------------------

    /**
     * Init Configurations
     *
     * @param string $level 
     * @param string $msg 
     * @param string $section 
     * @return void
     */
    public function init($level, $msg, $section = '')
    {
        global $config;

        // Convert new lines to a temp symbol, than we replace it and read for console debugs.
        $msg = trim(preg_replace('/\n/', '[@]', $msg), "\n");

        $this->setDriver($config['log_driver']);
        $this->setThreshold(1);
        $this->setEnabled(true);  // default true
        $this->setDefaultLevels(array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'BENCH' => '4', 'ALL' => '5'));
        $this->setLevel($level);

        $this->setLogPath(DATA .'logs'. DS);
        $this->setLogThreshold($config['log_threshold']);
        $this->setLogDateFormat($config['log_date_format']);


        if (defined('STDIN') AND defined('TASK'))   // Internal Task Request
        {


            $logPath = rtrim($logPath, DS) . DS .'tasks' . DS;

            $this->setLogPath();

        } 
        elseif(defined('STDIN'))  // Command Line && Task Requests
        {
            if(isset($_SERVER['argv'][1]) AND $_SERVER['argv'][1] == 'clear') //  Do not keep clear command logs.
            {
                $this->setEnabled(false);
            }

            $logPath = rtrim($logPath, DS) . DS .'cli' . DS; 
        }         




        // $threshold = 1;
        // $date_fmt  = 'Y-m-d H:i:s';
        $enabled   = true;
        $levels    = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'BENCH' => '4', 'ALL' => '5');
        $level     = strtoupper($level);

        $logPath         = DATA .'logs'. DS;
        $log_threshold   = $config['log_threshold'];
        $log_date_format = $config['log_date_format'];
    }

    // --------------------------------------------------------------------

    public function getLogPath()
    {
        return DATA .'logs'. DS;
    }

    // --------------------------------------------------------------------

    public function setDriver($driver)
    {
        $this->log_driver = $config['log_driver'];
    }

    // --------------------------------------------------------------------

    public function getDriver()
    {
        return $this->log_driver;
    }

}