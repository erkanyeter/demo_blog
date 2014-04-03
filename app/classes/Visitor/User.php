<?php

/**
 * Visitor User Class
 * Control visibility of your users
 * 
 * @category  Hooks
 * @package   Visitor
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/hooks
 */
Class Visitor_User
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
        
        $c['Sess'];
        $c['Auth'];

        $this->init();
        $this->logger = $c['Logger'];
        $this->logger->debug('Visitor User Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * This file specifies which functions run by default 
     * in __construct() level of the controller.
     * 
     * In order to keep the framework as light-weight as possible only the
     * absolute minimal resources are run by default. This file lets
     * you globally define the controller action that you like to run
     * by $c->func('index.visitor.user')
     */

    // ------------------------------------------------------------------------
    
    /**
     * Put your codes here
     * for private visitors
     * 
     * @return void
     */
    public function init()
    {
        global $c;

        if ( ! $c['Auth']->hasIdentity()) {  // if user has not identity ?    
            $c['Url']->redirect('membership/login');  // redirect user to login page
        }
    }
}

// END Public_User Class

/* End of file private_user.php */
/* Location: ./packages/controller/releases/0.1/private_user.php */