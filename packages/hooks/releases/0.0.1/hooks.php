<?php

/**
 * Hook Class
 * Provides a mechanism to extend the core without hacking.
 *
 * @package       packages
 * @subpackage    hooks
 * @category      hooks
 * @link        
 */

Class Hooks {
    
    public $enabled        = false;    // Determines wether hooks are enabled
    public $hooks          = array();  // List of all hooks set in config/hooks.php
    public $in_progress    = false;    // Determines wether hook is in progress, used to prevent infinte loops

    private static $instance;

    // --------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = getConfig();

        if ($config['enable_hooks'] == FALSE)  // If hooks are not enabled in the config
        {                                            // file there is nothing else to do
            return;
        }

        $hooks = getConfig('hooks');
        
        if ( ! isset($hooks) OR ! is_array($hooks) OR count($hooks) == 0)
        {
            return;
        }

        $this->hooks   =& $hooks;
        $this->enabled = true;

        logMe('debug', "Hooks Class Initialized");
    }

    // --------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }

    // --------------------------------------------------------------------

    /**
     * Call Hook
     *
     * Calls a particular hook
     *
     * @access  private
     * @param   string  the hook name
     * @return  mixed
     */
    public function _callHook($which = '')
    {
        if ( ! $this->enabled OR ! isset($this->hooks[$which]))
        {
            return false;
        }

        $this->_runHook($this->hooks[$which]);

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Run Hook ( Closure Function )
     *
     * Runs a particular hook
     *
     * @access  private
     * @param   Closure the hook function
     * @return  bool
     */
    public function _runHook($closure)
    {
        if( ! is_callable($closure))
        {
            logMe('debug', 'Hooks closure isn\'t callable');

            return false;
        }

        // -----------------------------------
        // Safety - Prevents run-away loops
        // -----------------------------------
        // If the script being called happens to have the same
        // hook call within it a loop can happen

        if ($this->in_progress == true)
        {
            return;
        }
        
        // -----------------------------------
        // Set the in_progress flag
        // -----------------------------------

        $this->in_progress = true;

        // -----------------------------------
        // Call the requested Closure function
        // -----------------------------------

        $closure();

        logMe('debug', 'Hooks closure called succesfully');
        
        $this->in_progress = false;

        return true;
    }

}
// END Hooks Class

/* End of file Hooks.php */
/* Location: ./packages/hooks/releases/0.0.1/hooks.php */