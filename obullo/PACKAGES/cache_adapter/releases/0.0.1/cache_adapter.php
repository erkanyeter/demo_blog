<?php

/**
 * Cache Class ( Static )
 *
 * @package       packages
 * @subpackage    cache
 * @category      cache
 * @link
 */
Class Cache_Adapter
{
    public $config;

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct($config)
    {
        global $c;
        $this->config = $config;
        $c['Logger']->debug('Cache Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initalize and grab instance of the auth.
     * 
     * @param array $params config
     * 
     * @return object
     */
    public function connect($params = array())
    {
        if ($this->config['driver'] != 'file' OR $this->config['driver'] != 'apc') {  // connect 

            $paramsKey = array('hostname' => 1, 'port' => 2);
            $servers   = array_intersect_key($this->config['servers'], $paramsKey);

            if ( ! isset($servers['hostname']) OR ! isset($servers['port'])) {

                throw new Exception('A defined hostname could not be found.');

            } else {

                $driver->connectionSet = $this->config;
                $driver->connect($this->config['driver']);
            }

            if ($this->config['driver'] == 'redis') {  // Redis Configuration

                if (isset($this->config['servers']['weight'])) {
                    unset($this->config['servers']['weight']);
                }
            }
        }

        return $driver;
    }
}

/* End of file cache.php */
/* Location: ./packages/cache/releases/0.0.1/cache.php */