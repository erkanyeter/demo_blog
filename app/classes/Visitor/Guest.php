<?php

/**
 * Visitor Guest Class
 * Control visibility of your users
 * 
 * @category  Hooks
 * @package   Visitor
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/hooks
 */
Class Visitor_Guest
{
    public $logger;

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {   
        global $c;

        $c['sess'];
        $c['auth'];

        $this->init();
        $this->logger = $c['logger'];

        $this->logger->debug('Visitor_Guest Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * This file specifies which functions run by default 
     * in __construct() level of the controller.
     */

    // ------------------------------------------------------------------------
    
    /**
     * Put your codes here
     * for public visitors
     * 
     * @return void
     */
    public function init()
    {
        // initalize
    }
}


// END Call_Guest.php File
/* End of file Guest.php

/* Location: .app/classes/Visitor/Visitor_Guest.php */