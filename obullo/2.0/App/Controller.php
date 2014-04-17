<?php

namespace Obullo\App;

use Obullo\Logger\Logger;

/**
 * Control the application variables
 *
 * @category  Controller
 * @package   App
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/package/app
 */
Class Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;

        if ($c['logger'] instanceof Logger) {
            $c['logger']->debug('App Controller Class Initialized', array(), 9);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set a object to 
     * controller instance 
     * 
     * @param string $key string
     * @param string $val object
     *
     * @return void
     */
    public function __set($key, $val)
    {
        if (is_object($val)) {  // Just store object type variables to Controller
            \Controller::$instance->{$key} = $val;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get application objects
     * 
     * @param string $key class or object name
     * 
     * @return object
     */
    public function __get($key)
    {
        if ($key == 'instance') {
            return \Controller::$instance;
        }
        return $this->{$key};
    }
}

// END App/Controller class

/* End of file Controller.php */
/* Location: .Obullo/App/Controller.php */