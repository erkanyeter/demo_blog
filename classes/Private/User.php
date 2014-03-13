<?php

/**
 * Private User Class
 * Control visibility of your users
 * 
 * @category  User
 * @package   Obullo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/hooks
 */
Class Private_User
{
    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {   
        global $logger;

        if ( ! isset(getInstance()->private_user)) {
            getInstance()->private_user = $this;  // Available it in the contoller $this->private_user->method();
        }

        new Sess;
        new Auth;

        $this->init();
        $logger->debug('Private User Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * This file specifies which functions run by default 
     * in __construct() level of the controller.
     * 
     * In order to keep the framework as light-weight as possible only the
     * absolute minimal resources are run by default. This file lets
     * you globally define the controller action that you like to run
     * by $c->func('index.private_user')
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
        if ( ! getInstance()->auth->hasIdentity()) {  // if user has not identity ?
            $url = new Url;
            $url->redirect('membership/login');  // redirect user to login page
        }
    }
}

// END Public_User Class

/* End of file private_user.php */
/* Location: ./packages/controller/releases/0.1/private_user.php */