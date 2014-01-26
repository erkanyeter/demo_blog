<?php

/**
 * Trigger Class
 *
 * Controller Trigger ( Initiation of 
 * your anonymous functions in Controller
 * constructor level ).
 *
 * @package       packages
 * @subpackage    trigger
 * @category      hooks
 * 
 */

Class Trigger {

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {   
        $arguments = func_get_args();
        $triggers  = getConfig('triggers');

        foreach($arguments as $trigger_name)
        {
            if(isset($triggers['func'][$trigger_name]))
            {
                 call_user_func_array(Closure::bind($triggers['func'][$trigger_name], getInstance(), 'Controller'), array());
            } 
            else 
            {
                throw new Exception(sprintf('Trigger "%s" function doesn\'t exists
                    in your triggers config file.', $trigger_name)
                );
            }
        }

        logMe('debug', 'Trigger Class Initialized');
    }

}

/* End of file Trigger.php */
/* Location: ./packages/trigger/releases/0.0.1/trigger.php */